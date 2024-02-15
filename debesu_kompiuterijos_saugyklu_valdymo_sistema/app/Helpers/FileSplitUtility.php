<?php
namespace App\Helpers;
/**
 *
 * @author Edvin
 *
 */

/***
 * Class
 *
 * @package App\Helpers
 *
 */
use Illuminate\Support\Facades\Auth;
use App\Services\CombinedCloudService;
use File;
use App\File as FileEntity;
use App\FileChunk;
use Storage;
use Log;

class FileSplitUtility {



    private $combinedCloudService;

    function __construct(CombinedCloudService $cloudService){
        $this -> combinedCloudService = $cloudService;

    }

    public  function mergeAndSave($relPath, $file){

        $fileChunks = FileChunk::where('file_id',$file->id)->orderBy('order')->get();
        $store_path= storage_path().'/'.$relPath;
        foreach($fileChunks as $singleChunk){
            $cloudService =  $this -> combinedCloudService -> getFileServiceInstance( $singleChunk ->cloud_service );
            if($cloudService != null){
                $dir =  $this -> combinedCloudService -> getDirectory($singleChunk ->cloud_service,$file -> parent_id );  
                $chunk = $cloudService -> download($dir, $singleChunk-> id_on_cloud )['raw_data'];        
                $bytesWritten = File::append($store_path.$file->name,$chunk ); 
                
            }
        }
        return true;
    }
    public  function splitAndUpload($file, $parentId, $chunk_size=1024){
        //Atidaromas failas skaitymui
        $file_handle = fopen($file,'r');
        //gaunamas failo dydis
        $file_size = filesize($file);
        //skaičiuojama į kiek dalių reikės dalinti
        $parts = $file_size / $chunk_size;
        //rinkmėnų dalys saugmos masyve
        $file_parts = [];
        //vieta kurioje bus išsaugomos dalys
        $store_path = "splits/"; 
        //name of input file
        $file_name = $withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '',$file -> getClientOriginalName());  
        //Sukuriame laikina direktorija tam kad išsaugoti rinkmenos dalį serveryje
     
      
        //sukuriame įrašą File lentelėje
        $mainFileEntity = FileEntity::create(  
            [   "extension" =>  $file ->  getClientOriginalExtension(),
                "name" => $file -> getClientOriginalName(),
                "storage_service" =>  null,
                "storage_service_id" =>  null,
                "size" =>  $file->getClientSize(),
                "parent_id" => $parentId,
                "chunked" => 1,
                "user_id" => Auth::user()->id,
        ]);
        // Einame per kiekvieną dalį išsaugojame tą dalį serveryje ir įkeliame į labiausiai atlaisvinta saugyklą, po to ištriname visus leikinus failus


        for($i=0;$i<$parts;$i++){
            $store_path= storage_path().'/chunks/'. $this -> combinedCloudService -> getUser().'/';
            // 777 reiškia pilnas teises: read write execute
            File::makeDirectory($store_path, $mode = 0777, true, true);
            $file_part = fread($file_handle, $chunk_size);
            // rinkmena bus išsaugoma su plėtiniu part ir indeksas, tam kad vėliau butų lengviau tokią rinkmeną sujungti
            $file_part_path = $store_path.$file_name."_part$i.part";
            $file_new = fopen($file_part_path,'w+');
            fwrite($file_new, $file_part);
            fclose($file_new);
            $chunk =  $this -> getFileInstance($store_path,$file_name."_part$i.part");

            // neišėjo nuskaityti failo , nutriaukiamas visas procesas
            if($chunk === null){
                $mainFileEntity -> delete();
                $this -> cleanUserTempData();
                return;
            }
            // Įrašoma rinkmeną į geriausiai tam tinkančią saugyklą
            $info =  $this -> combinedCloudService-> getBestDisk($chunk_size);
            $instace = $info['service_instance'];
            $folderDirOnClod =  $this -> combinedCloudService -> getDirectory($instace -> getId(), $parentId);
            $result =  $instace -> upload( $folderDirOnClod, $chunk ,$file_name."_part".$i,"part" );
            if($result['success']){
                FileChunk::create([
                    'file_id' => $mainFileEntity  -> id, 
                    'cloud_service' => $instace -> getId(), 
                    'id_on_cloud' => $result['id_on_cloud'], 
                    'name' => $file_name."_part$i.part", 
                    'order' => $i,
                ]);
                $this -> cleanUserTempData();
            }else{
                // Įvyko klaidos nutraukiamas visas procesas, o kadangi FileChunk modelis yra įgyvendintas per išorinius raktus visos dalys automatiškai bus ištrintos
                $mainFileEntity -> delete();
                $this -> cleanUserTempData();
                return;
            }

    
        }    
        fclose($file_handle);
        return $file_parts;
    }

    private function cleanUserTempData(){
        return File::deleteDirectory(storage_path().'/chunks/'. $this -> combinedCloudService -> getUser().'/');
    }

    private function getFileInstance($dir , $name){
        foreach (File::allFiles($dir) as $file)
        {
            if($file->getFilename() == $name) 
                return $file;
        }
        return null;
    }
}