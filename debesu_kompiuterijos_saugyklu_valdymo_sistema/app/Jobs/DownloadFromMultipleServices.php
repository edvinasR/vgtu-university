<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\CombinedCloudService;
use App\Folder;
use File;
use Log;
use Zip;
use App\Download;
use App\CloudService;
use App\File as FileEntity;
use App\Helpers\FileSplitUtility;

class DownloadFromMultipleServices implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private $files;
    private $user;
    private $serviceId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($files, $user, $serviceId = null )
    {
        $this ->  files = $files;
        $this ->  user =  $user;
        $this ->  serviceId =  $serviceId;

        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Užregistruojame saugyklų caldymo servisą bei ukuriame tris direktorijas pagal dabartini laiką naudotojo direktorjoje
        $combinedCloud   = new CombinedCloudService(   $this ->  user -> id);
        $combinedCloud  -> registerDisks();
        $timestamp = time ();
        $rootPath = $this -> makeLocalDorectory($this ->  user -> id,  $timestamp  );
        $pathForFinalFile= $this -> makeLocalDorectory($this ->  user -> id,  $timestamp  ,'final');
        $pathForProcessing= $this -> makeLocalDorectory($this ->  user -> id,  $timestamp  ,'temp');

        // Einame per rinkmenas kurias reikia atsiųsti
        foreach($this ->  files as $fileId){
            try{
                    // Ištraukiama rinkmenų informaciją iš DB
                    $file = FileEntity::findOrFail($fileId);
                    // Jeig rinkmena tai aplankalas
                    if( $file -> extension == 'a_folder'){

                            //Žiurima ar reikia gauti tik tai konkrečios rinkmenos aplankalo turinį ar iš visų saugyklų
                            $services = [];
                            // Užpildomas masyvas saugyklų iš kurių reikia atsiųsti rinkmenas
                            if( $this ->  serviceId != null){
                                $services = [$this ->  serviceId];
                            }else{
                                $services =CloudService::where('user_id',$this ->  user -> id)->where('deleted',0)->where('activated',1)->get();
                            }
                            // Einame per atrinktas saugyklas ir ištraukiama informacija apie aplankalą konkrečioje saugykloje
                            foreach( $services as $service){

                                $folder = Folder::where('file_id',  $file -> id)->where('cloud_service',  $service -> id)->first();
                                $serviceInstance =  $combinedCloud  -> getFileServiceInstance( $service -> id);            
                                $dir = $service-> type == "dropbox" || $service -> type == "onedrive" ? $combinedCloud->getDirectory($service -> id,   $file -> id) : $folder -> id_on_cloud;
                                if($serviceInstance != null)
                                { 
                                    //Atsiunčiamas rekursyviai aplankalo turinys
                                    $this -> downloadRecursively($serviceInstance,$dir,  $pathForProcessing.$file -> name.'/');   
                                }
                            }
                    }
                    //Jeigu rinkmena nėra apankalas ja tiesiog atsiunčiame į direktoriją
                    else if($file -> chunked){
                        $fileSplitAndMergeUtility =  new FileSplitUtility($combinedCloud );
                        $relPath =   substr ($pathForProcessing , strpos (  $pathForProcessing , '/downloads/'));
                        $fileSplitAndMergeUtility ->  mergeAndSave($relPath, $file);

                    }else
                    {   
                        $serviceInstance =  $combinedCloud  -> getFileServiceInstance( $file -> storage_service);
                        if($serviceInstance != null){
                            $dir =   $combinedCloud  -> getDirectory( $file -> storage_service,   $file -> parent_id);
                            $fileData =  $serviceInstance -> download($dir, $file -> storage_service_id);
                            File::put($pathForProcessing.$file->name,$fileData['raw_data']);
                        }

                    }
            }catch(\Exception $ex){
                    Log::error($ex);
                    continue;
            }
        }
        // Galiausiai supakuojjame visius atsiustus failus į zip archyvą
        try{
            $name = 'atsisuntimas_'.$timestamp.'.zip';
            $zipName = $pathForFinalFile.$name;
            $zipRelPath = substr ($zipName , strpos (  $zipName , '/downloads/') );
            $zip = Zip::create($zipName);
            $zip->add( $pathForProcessing, true);
            $zip->close();
           $success = File::deleteDirectory( $pathForProcessing);
           // Įrašomas įrašas į DB tam kad naudotojas matytu, jog jo pasiriktos rinkmenos jau yra paruoštos atsisiuntimui
            Download::create([
                'user_id' =>  $this ->  user -> id,
                'path' =>  $zipRelPath,
                'mimetype' => 'application/zip',
                'file_count' =>  count($this ->  files),
                'name' => $name,
            ]);
        }catch(\Exception $ex){
            Log::error($ex);           
            return;
        }
    }

    //  Sukuriama direkotrija kurioje bus išsaugomas lokalus failas
    // Visos lokalios diraktorijos bus ištrinamos iškart baigus darbą su tą direktorija
    private function makeLocalDorectory($userId, $fileId , $append =''){
        $store_path = storage_path().'/downloads/'. $userId.'/'.$fileId.'/'.$append.'/';
        File::makeDirectory($store_path, $mode = 0777, true, true);
        return $store_path;
    }

    private function downloadRecursively($serviceInstance,$dir, $currentPath){
        $directoryContents = [];
        try{
            File::makeDirectory( $currentPath, $mode = 0777, true, true);
            $directoryContents = $serviceInstance -> getContentsOfDirectory($dir, false );
        }catch(\Exception $ex){
          return;
        }

        foreach( $directoryContents as $file){
            try{
                if($file['type']=='dir'){
                    $currentPathNew = $currentPath.$file['filename'].'/';
                    File::makeDirectory($currentPathNew, $mode = 0777, true, true);
                    $this -> downloadRecursively($serviceInstance,$file['path'],$currentPathNew);
                }else{
                    $fileData =  $serviceInstance -> download($file['dirname'],$file['basename']);
                    File::put($currentPath.$file['filename'].'.'.$file['extension'],$fileData['raw_data']);
                }
     
            }catch(\Exception $ex){
                continue;
            }
        }
    }
    
}
