<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\CloudService;
use App\File;
use App\Folder;
use App\Interfaces\FileService;
use Log;
use App\Services\CombinedCloudService;

class CreateFolderStructureOnNewStorageService implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $serviceId;
    private $serviceEntity;

    public function __construct($serviceId)
    {
        $this -> serviceId = $serviceId;
        $this -> serviceEntity = CloudService::where('id',$serviceId)->first();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this -> serviceEntity != null){
            $cloudService = new CombinedCloudService( $this -> serviceEntity -> user_id);
            $cloudService -> registerDisks();
            $serviceInstance = $cloudService  -> getFileServiceInstance( $this -> serviceEntity -> id);
            if($serviceInstance != null){
                $file = new File();
                $rootFile = $file -> root($this -> serviceEntity -> user_id);
                $this -> createFolder($rootFile -> id, $serviceInstance);
            }
        }
    }

    private function  createFolder($fileId, FileService $instance){
        $file = File::where('id',$fileId)->first();
        $isDirectory = $file -> extension == "a_folder"  ? true : false;
        if($isDirectory){
            $childFiles = File::where('parent_id',$fileId )->where('extension','a_folder') -> get();

            foreach($childFiles as $singleChild){
                $parent = Folder::where('cloud_service',$instance->getId())->where('file_id',$file -> id)->first();
                $dir = $this -> serviceEntity -> type == "dropbox" || $this -> serviceEntity -> type == "onedrive" ? $this->getDirectory($instance->getId(),$file -> id) :$parent -> id_on_cloud;
                $instance ->  makeDirectory($dir,  $singleChild -> name, $singleChild -> id);
                $this -> createFolder($singleChild -> id, $instance);
            }
        }
        return true;
    }

    private function getDirectory($serviceId,$fileId){
        $result = $this -> getDirectoryOfFile($serviceId,$fileId);
        $exp = explode('/',$result);
         return implode('/',array_reverse($exp));
    }

    private function getDirectoryOfFile($serviceId,$fileId){
        $item = File::select('name','id','parent_id')->where('id',$fileId)->first();
        $itemOnCloud =  Folder::where('file_id', $item ->id)->where('cloud_service',$serviceId)->first();
        if( $item -> parent_id != null)
            return  $itemOnCloud -> id_on_cloud.'/'.$this -> getDirectoryOfFile($serviceId, $item->parent_id);
        return $itemOnCloud -> id_on_cloud;
    }
}
