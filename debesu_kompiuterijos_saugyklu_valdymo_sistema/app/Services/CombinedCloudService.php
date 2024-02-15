<?php

namespace App\Services;
use App\Interfaces\FileService;
use Storage;
use App\File;
use App\CloudService;
use Auth;
use Config;
use Log;
use App\Helpers\DisksConfigurationUtility;
use Redirect;
use App\Helpers\Oauth;
use App\Folder;

class CombinedCloudService {

    private $userId = null;
    public function __construct($userId){
        $this -> userId = $userId;
        $this ->  registerDisks();
    } 

    public function getUser(){
        return $this -> userId;
    }

    public function registerDisks(CloudService $instance = null){
        $disks = [];
        $diskConfig = new DisksConfigurationUtility();
    
        if($instance == null){
            $diskConfig -> registerDisksInConfig($this -> userId);
            $disks = CloudService::where('user_id',$this -> userId)->where('deleted',0)->where('activated',1)->get();

        }else{
            $diskConfig -> registerDisksInConfig($this -> userId,$instance -> id);
            array_push($disks,$instance);
        }
    }

	public function createDirectory($file, $parent, $name){
        // Aplankalas sukuriamas visose saugyklose noritn užtikrinti sklandžią sinchronizaciją
        $saugyklos = CloudService::where('user_id',$this -> userId)->where('deleted',0)->where('activated',1)->get();
        foreach($saugyklos as  $saugykla){
            // Gaunamas atitinkamas failų servisas prikalusomai nuos saugyklos tipo
            $fileService =  $saugykla -> getServiceInstance();
            if($fileService != null){
                $savePathOnCloud = $this -> getDirectory($saugykla->id, $parent);  
                $fileService -> makeDirectory($savePathOnCloud, $name, $file -> id);
            }
        }
    }

    public function deleteDirectory($directoryId){
        $saugyklos = CloudService::where('user_id',$this -> userId)->where('deleted',0)->where('activated',1)->get();
        foreach($saugyklos as  $saugykla){
            // Gaunamas atitinkamas failų servisas prikalusomai nuos saugyklos tipo
            $fileService =  $saugykla -> getServiceInstance();
            if($fileService != null){
                try{
                    $directoryPath = $this -> getDirectory( $saugykla -> id, $directoryId);  
                    $fileService -> deleteDirectory($directoryPath);
                }catch(\Exception $ex){
                    continue;
                }          
            }
        }
    }

    public function renameDirectoriesInAllServices($dirId, $name){
        $saugyklos = CloudService::where('user_id',$this -> userId)->where('deleted',0)->where('activated',1)->get();
        $file = File::findOrFail($dirId);
        foreach($saugyklos as  $saugykla){
            // Gaunamas atitinkamas failų servisas prikalusomai nuos saugyklos tipo
            $fileService =  $saugykla -> getServiceInstance();
            if($fileService != null){
                $folderOnCloud = $file -> folder($saugykla -> id);
                $dirOnCloud = $this -> getDirectory($saugykla -> id, $file -> parent_id);  
                $fileService  -> rename($dirOnCloud ,  $folderOnCloud ->id_on_cloud  , $name, $file->id);          
            }
        }
        return true;
    }

    public function moveAllDirectories($sourceDirId, $destanationDirId){
        $saugyklos = CloudService::where('user_id',$this -> userId)->where('deleted',0)->where('activated',1)->get();
        $fileSource = File::findOrFail($sourceDirId);
        $fileDest = File::findOrFail($destanationDirId);
        if($fileDest -> extension != 'a_folder') {
            Log::error('Destanation can be only folder');
            return false;
        }
           
        foreach($saugyklos as  $saugykla){
            // Gaunamas atitinkamas failų servisas prikalusomai nuo saugyklos tipo
            $fileService =  $saugykla -> getServiceInstance();
            if($fileService != null){
                $destFolder =    $this -> getDirectory($saugykla -> id,  $fileDest -> id);  
                $folderOnCloud =  $fileSource -> folder($saugykla -> id) -> id_on_cloud;
                $dirOnCloud = $this -> getDirectory($saugykla -> id, $fileSource -> parent_id);  
                $fileService  -> move( $dirOnCloud, $folderOnCloud, $destFolder);          
            }
        }
        return true;
    }
    

    public function getDirectory($serviceId,$fileId){
        $result = $this -> getDirectoryOfFile($serviceId,$fileId);
        $exp = explode('/',$result);
         return implode('/',array_reverse($exp));
    }

    private function getDirectoryOfFile($serviceId,$fileId){
        $item = File::select('name','id','parent_id')->where('id',$fileId)->first();
        $itemOnCloud =  Folder::where('file_id', $item ->id)->where('cloud_service',$serviceId)->first();
        if($item != null && $itemOnCloud != null){
            if( $item -> parent_id != null)
                return  $itemOnCloud -> id_on_cloud.'/'.$this -> getDirectoryOfFile($serviceId, $item->parent_id);
            return $itemOnCloud -> id_on_cloud;
        }
        return '';

    }

    public function getFileHierarchy($startFile){
         $result = $this -> getTreePath($startFile);
         $exp = explode('/',$result);
         return array_reverse($exp);
    }

    private function getTreePath($fileId){
        $item = File::select('name','id','parent_id')->where('id',$fileId)->first();

        if($item  != null){
                if( $item -> parent_id != null)
                    return   $item -> id.'/'.$this -> getTreePath($item->parent_id);
                return  $item -> id;
        }
        return ''; 
    }

    public function getStorageServiceEntity($diskId){
        return  CloudService::where('id',$diskId)->first();
    }
    public function getFileServiceInstance($diskId){
        $serviceEntity  = CloudService::where('id',$diskId)->first();
        if($serviceEntity != null){
            return $serviceEntity -> getServiceInstance();
        }
        return null;
    }
    public function getBestDisk($fileSize,$service = null){

        $dataForReturn =[
            "mode" => "single", // gali buti singe , chunked arba no_memory
            "chunk_size" => null, // Vienos rinkmeonos dalies dydis baitias
            "service_instance" => null, // gali būti null jei mode kintamasis yra chunked
        ];

        // jeigu yra nurodyta konkreti saugykla, grąžiname duommenis kokrečiai saugyklai
        if($service != null){
            $serviceEntity = CloudService::findOrFail($service);
            if($serviceEntity -> free_storage > $fileSize){
                $dataForReturn['service_instance'] = $serviceEntity -> getServiceInstance();
            }
            return $dataForReturn;
        }
        // Jeigu nenurodyta konkreti saugykla grąžinamata  kurioje yra dugiausiai vietos
        $serviceEntity = CloudService::where('free_storage','>=',$fileSize)->where('user_id',$this -> userId)->where('deleted',0)->where('activated',1)->orderBy('free_storage','DESC')->first();
        if($serviceEntity != null){
            $dataForReturn['service_instance']  =   $serviceEntity -> getServiceInstance();
        }else{
            //Jeigu vietos neužtenka skaičiuojam koks rinkmenos dalies dydis turi buti nornt išdalinti rinkmeną ir išsaugoti atskiras dalis
            $minChunk = 5242880; // Minimalus įmanomas rinkmenos dalies dydis yra 5 Megabaitai, tokiu būdu išvengiama situacijos kuomet pasirenkama saugykla turinti 0 < X < 5242880 baitų atminties
            //nes tada turint kilobaito dydžio dalis neapsimoka skladyt rinkmenos -> tai užima begale daug laiko ir resursų
          
           // suranadame kiek yra atminties kartu sudėjus visas saugyklas turinčas ne mažiau nei 5MB atminties
            $totalFreeSpace = CloudService::where('free_storage','>=', $minChunk)->where('user_id',$this -> userId)->where('deleted',0)->where('activated',1)->sum('free_storage');
           //Pasirenkama mažiausiai atminties turinti saugykla kaip dalies dydis
            $chunkSize = CloudService::where('free_storage','>=', $minChunk)->where('user_id',$this -> userId)->where('deleted',0)->where('activated',1)->min('free_storage');
            if($totalFreeSpace > $fileSize){
                $dataForReturn['mode'] = 'chunked';
                $dataForReturn['chunk_size'] = $chunkSize;
            }
        }
        return $dataForReturn;
    }
}