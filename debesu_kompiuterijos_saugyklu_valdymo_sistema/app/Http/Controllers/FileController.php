<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\HttpUtility;
use App\File;
use App\FileChunk;
use Log;
use App\Services\CombinedCloudService;
use Redirect;
use Auth;
use Artisan;
use App\Helpers\DisksConfigurationUtility;
use App\Helpers\Formatters;
use App\Helpers\FileSplitUtility;
use App\Folder;
use App\Jobs\DownloadFromMultipleServices;
use App\Jobs\MakeDirectory;
use App\Jobs\DeleteDirectory;
use App\Jobs\RenameDirectory;


class FileController extends Controller
{

    public function __construct(){

    }
    public function delete(Request $request){
        
        $cloudService = new CombinedCloudService(Auth::user()->id);
        $cloudService ->registerDisks();
        $files = $request -> get('files');
        foreach($files  as $fileId ){

            $file = File::findOrFail($fileId);
            if($file -> extension == 'a_folder'){
                // Ištriname aplankalą iš kiekvienos SAAS saugyklos naudojant darbų eilę
                $job = (new DeleteDirectory(Auth::user()->id, $file -> id ));
                $this->dispatch($job);
                return HttpUtility::buildSuccessfullResponse("Aplankalas bus ištrintas iš kiekvienos debesų kompiuterijos saugyklos artimiausiu minučių bėgyje",["queued" => true]);

            }else{
                //Jeigu rinkmena susideda iš daliu ištriname visas dalis, priešingu atveju ištriname tik vieną vienetą
                if($file ->chunked){
                    $fileChunks = FileChunk::where('file_id',$file->id)->orderBy('order')->get();
                    foreach($fileChunks as $chunk){
                        $service = $cloudService -> getFileServiceInstance($chunk -> cloud_service);
                        $dir = $cloudService -> getDirectory($chunk -> cloud_service ,$file -> parent_id);
                        $service -> delete($dir,$chunk  -> id_on_cloud);
                    }
                    $file -> delete();

                }else{
                    $service = $cloudService -> getFileServiceInstance($file -> storage_service);
                    $dir = $cloudService -> getDirectory($file -> storage_service ,$file -> parent_id);
                    $service -> delete($dir,$file -> storage_service_id);
                }
                $this -> deleteFile($fileId);

            }
            
         
        }     
        return HttpUtility::buildSuccessfullResponse("Rinkmenos buvo ištrintos",["queued" => false]);
    }
    public function get($fileId){

        try{
            $combinedCloudService = new CombinedCloudService(Auth::user()->id);   
            $file = File::findOrFail($fileId);
            $serviceInstance = $combinedCloudService -> getFileServiceInstance( $file -> storage_service );
            $serviceEntity = $combinedCloudService  -> getStorageServiceEntity($file -> storage_service);
            $size = $file -> size;
            $isImage = in_array ( $file -> extension , ['png','jpg','jpeg','gif','bmp'] ) ? true : false;
            if($serviceInstance != null){
                $data = [
                    "saugykla" =>  $serviceEntity  -> type(),
                    "priklauso" => $serviceInstance -> getBelongsTo() ,
                    "atsisuti" => '/file/'.$fileId.'/content',
                    "saugyklos_pavadinimas" => $serviceEntity  -> name,
                    "paveikslelis" => $isImage,
                ];
                $file -> {'advanced_info'} = $data;
            }
            
    
            if($file -> extension == "a_folder"){
                $size = $this -> getSizeRecursively($file -> id, 0);
               // $size = File::where('parent_id',$file->id)->sum('size');  
            }
            return response()->json(["file_info" =>$file, "size" => Formatters::formatBytes($size)]);
        }catch(\Exception $ex){
            Log::error( $ex );
            return HttpUtility::buildInternalServerErrorResponse('Serverio klaida, paršome papbandyti vėliau.');
        }
        
    }

    public function downloadUsingQueue(Request $request,$service = null){
        $files = $request->get('files');
        $job = (new DownloadFromMultipleServices( $files, Auth::user(), $service ));
        $this->dispatch($job);
        return HttpUtility::buildSuccessfullResponse("Jūsų pasirktos rinkmenos atsisiuntimo nuoroda atsiras skiltyje 'Atsisiuntimai' artimiausių 5 minučių bėgyje.",["queued" => true]);
    }
    public function download($fileId){
        try{
            $combinedCloudService = new CombinedCloudService(Auth::user()->id);
            $file = File::findOrFail($fileId);
            // Jeigu rinkemna išskalydta  į kelias debesų kompiuterijos saugyklas atsisiuntimu vykdymą padedame į eilę;
            if($file -> extension == 'a_folder' ||  $file ->chunked){
                return HttpUtility::buildErroreusResponse("Šiuo API metodu negalima atsiusti tokio pobūdžio rinkmenų.  Nuadokite eilės pricipo atsisiuntimo API metodą POST file/content/archived/{service?}");     
            }
            // Jeigu rinkmena yra vientisa  jinai tiesiog atsiunčiama naudojant vieną srautą
            $serviceInstance = $combinedCloudService -> getFileServiceInstance( $file -> storage_service );
            $dir =  $combinedCloudService -> getDirectory($file -> storage_service,$file -> parent_id );
            if($serviceInstance != null)
            {
                // Atsiunčiame rinkmenas per srautą
                return $serviceInstance -> downloadStream($dir, $file -> storage_service_id );
            }
            Log::error('Nepavyko inicializuoti saugyklos failų serviso '. $file -> storage_service);
            return HttpUtility::buildInternalServerErrorResponse('Klaidos serverio pusėje');
        }catch(\Exception $ex){
            Log::error($ex);
            return HttpUtility::buildInternalServerErrorResponse('Klaidos serverio pusėje');
        }
    }
  
    public function post(Request $request, $parentId, $service = null){
    
        $combinedCloudService = new CombinedCloudService(Auth::user()->id);
           // Užskausos validacija 
        $validatedData = $request->validate([
            'file'=>  'required'
        ]);
        // Užsklausa yra tvarkinga
        $file =  $request->file('file');
        // Gaunama geriausią diską jei egizstuoja rinkmenai išssaugoti, jeigu toks diskas neegizstuoja grąžinamas dydis baitais į kurį rinkemną reikią suskalduti norint ją atsiųsti
        $uploadModeInfo = $combinedCloudService -> getBestDisk($file->getClientSize(),$service);
        $cloudFileService =  $uploadModeInfo['mode'] == 'single' ?  $uploadModeInfo['service_instance'] : null;
        try{
            // Jeigu egzistuoja tokia debesų saugyklą į kurią telpa rinkmena
            if( $cloudFileService != null && $uploadModeInfo['mode'] == 'single' ){
            
                    $folderDirOnClod = $combinedCloudService-> getDirectory($cloudFileService -> getId(),$parentId);
                    $result = $cloudFileService -> upload( $folderDirOnClod, $file );
                    if( $result['errors'] != null){
                        return response()-> json($result['errors'] , 400);
                    }
                   
                    //išsaugojamas naujas įrašas duomenų bazėje
                    File::create(  
                        [   "extension" =>  $file ->  getClientOriginalExtension(),
                            "name" => $file -> getClientOriginalName(),
                            "storage_service" =>  $cloudFileService -> getId(),
                            "storage_service_id" =>  $result['id_on_cloud'],
                            "size" =>  $file->getClientSize(),
                            "parent_id" => $parentId,
                            "user_id" => Auth::user()->id,
                    ]);

        
            
            }else if($uploadModeInfo['mode'] == 'chunked' ){
                // jeigu neegzistuoja tada bandoma dalyti rinkmeną į dalis
                $fileSplitUtility = new FileSplitUtility($combinedCloudService);           
                $fileSplitUtility -> splitAndUpload( $file , $parentId, $uploadModeInfo['chunk_size'] );
                return response()-> json('Įkelta sėkmingai', 200);       
            }else{
                return response()-> json('Nepavyko rasti saugyklos į kurią telpa Jūsų įkeliama rinkmena', 400);
            }
            return response()-> json('Įkelta sėkmingai', 200);

         }
         catch(\Exception $ex){
             Log::error($ex);
             return response()-> json('Klaida serverio pusėje', 500);
         }
    }

    public function createFolder(Request $request, $parentId){

        $aplankaloPavadinimas = $request -> get('folder_name');
        try{
            $file = File::create([
                "name" => $aplankaloPavadinimas,
                "parent_id" => $parentId,
                "extension" => "a_folder",
                "user_id" => Auth::user()->id,
            ]);
            $job = (new MakeDirectory(Auth::user()->id, $file, $parentId, $aplankaloPavadinimas ));
            $this->dispatch($job);
            return HttpUtility::buildSuccessfullResponse("Naujas aplnakalas bus sukurtas kiekvienoje debesų kompiuterijos saugykloje artimiausiu minučių bėgyje");

        }catch(\Exception $ex){
            Log::error($ex);
            return HttpUtility::buildInternalServerErrorResponse('Klaidos serverio pusėje');
        }
    }

    public function rename(Request $request, $fileId){
        $cloudService = new CombinedCloudService(Auth::user()->id);
        $cloudService ->registerDisks();
        $name = $request -> get('pavadinimas');
        $file = File::findOrFail($fileId);
        $newName = $name.($file -> extension == "a_folder" ? "":".".strtolower($file -> extension));  
        $file -> name =  $newName; 
        $file  -> save();

        if($file -> extension == "a_folder"){
            $job = (new RenameDirectory(Auth::user()->id, $file, $newName));
            $this->dispatch($job);
            return HttpUtility::buildSuccessfullResponse("Aplankalai bus pervardinti kiekvienoje debesų kompiuterijos saugykloje artimiausių minučių bėgyje",["queued"=>true]);
        }else{
            // Jeigu rinkmena susideda iš dalių nereikia kreiptis į saugyklas kadangi rinkmenos pavadinimas yra gaunamas ne iš saugyklos o lokalios DB dalių apjungimo metu
            $file -> save();
            if(!$file ->chunked){
                
                $serviceInstance = $cloudService  -> getFileServiceInstance( $file -> storage_service );
                $dirPath =  $cloudService ->   getDirectory($file -> storage_service,$file -> parent_id);
                $serviceInstance  -> rename($dirPath,  $file -> storage_service_id, $newName, $file -> id);

            }
          
        }
      
        return HttpUtility::buildSuccessfullResponse("Rinkmenos pavadinimas buvo pakeistas sėkmingai",["queued"=>false]);
    }

    public function move(Request $request, $directoryId){
        $cloudService = new CombinedCloudService(Auth::user()->id);
        $cloudService ->registerDisks();
        $directory = null;   
        $files = $request -> get('files');
        try{
            $directory = File::findOrFail($directoryId);
            if($directory -> extension != "a_folder"){
                return HttpUtility::buildErroreusResponse("Neteisinga tikslo direktorija");
            }
        }catch(Illuminate\Database\Eloquent\ModelNotFoundException $ex){
          
            return HttpUtility::buildNotFoundServerErrorResponse("Toks tikslo aplankalas neegizsuoja");
        }
       
        $files = $request -> get('files');
        foreach($files  as $fileId ){
            try{
                $file = File::findOrFail($fileId);         
                if($file -> extension == 'a_folder'){
                    $cloudService -> moveAllDirectories($fileId, $directory -> id);                            
                }else{
                    //Jeigu rinkmena susideda iš daliu perkeliame keiveiną dalį į atitnkamą direktoriją
                    if($file ->chunked){
                        $fileChunks = FileChunk::where('file_id',$file->id)->orderBy('order')->get();
                        foreach($fileChunks as $chunk){
                            $this ->  moveFile($cloudService, $chunk -> cloud_service,  $file -> parent_id,  $directory-> id, $chunk  -> id_on_cloud );
                        }
                    }else{
                        $this ->  moveFile($cloudService, $file -> storage_service,  $file -> parent_id,  $directory-> id, $file -> storage_service_id );
                    }
                }
                $file -> parent_id = $directory -> id; 
                $file -> save();
            }catch(\Exception $ex){
                Log::error($ex);   
            }

        } 
        return HttpUtility::buildSuccessfullResponse('Rinkmenos buvo perkeltos sėkmingai');
    }

    //----------------------------------------------
    private function deleteFile($fileId){
        $file = File::where('id',$fileId)->first();
        $isDirectory = $file -> extension == "a_folder"  ? true : false;
        if($isDirectory){
            $childFiles = File::where('parent_id',$fileId ) -> get();
            foreach($childFiles as $singleChild){
                    $this -> deleteFile($singleChild -> id);
            }
        }
        $file -> delete();
        return true;
    }

    private function moveFile($cloudService, $cloudServiceId,  $parentId, $directoryId, $idOnCloud ){
        $serviceInstance = $cloudService -> getFileServiceInstance( $cloudServiceId);
        $dirOnCloud = $cloudService -> getDirectory($cloudServiceId,   $parentId); 
        $destFolder = $cloudService -> getDirectory($cloudServiceId,  $directoryId);   
        $serviceInstance -> move( $dirOnCloud, $idOnCloud, $destFolder);  

    }

    private function getSizeRecursively($parent, $size){
        $sizeLocal = $size;
        $children =  File::select('id','size','extension')->where('parent_id',$parent)->get();
        $sizeLocal +=  collect($children)->sum('size'); 
        foreach($children as $child){
             if($child -> extension == 'a_folder')
             $sizeLocal = $this -> getSizeRecursively($child -> id,$sizeLocal );
        }
        
        return $sizeLocal;

    }
}
