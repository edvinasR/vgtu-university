<?php

namespace App\Services;
use App\Interfaces\FileService;
use Storage;
use App\File;
use App\CloudService;
use Log;
use App\Folder;
use App\Helpers\Oauth;
use App\Helpers\Formatters;

abstract class AbstractFileService implements FileService{

    protected $diskId = null;
    protected $about = null;
    
    protected function __construct($diskId){
        $this -> diskId = $diskId;
    }

    public function getId(){
        return $this -> diskId;
    }
    abstract public function about();
    abstract public function freeSpace();
    abstract public function usedSpace();
    abstract public function getBelongsTo();

	public function download($dir, $fileId){
        // Move from abstract
        $contents = collect( Storage::disk($this -> diskId)->listContents($dir, false));
        $file = $contents
            ->where('type', '=', 'file')
            ->where('basename', '=', $fileId)
            ->first(); 
        if($file != null){
            $rawData =  Storage::disk($this -> diskId)->get($file['path']);
            $mime = Storage::disk($this -> diskId)->mimeType($file['path']);
            return [
                "raw_data" => $rawData,
                "mimetype" =>   $mime ,
            ];
        }
        return false;
    }


    public function downloadStream($dir, $fileId){
    
        $contents = collect( Storage::disk($this -> diskId)->listContents($dir, false));
        $file = $contents
            ->where('basename', '=', $fileId)
            ->first(); 
        if($file != null){
            $rawDataStream =  Storage::disk($this -> diskId)->getDriver()->readStream($file['path']);
            $mime = Storage::disk($this -> diskId)->mimeType($file['path']);
            return response()->stream(function () use ($rawDataStream) {
                fpassthru($rawDataStream);
            }, 200, [
                'Content-Type' =>  $mime ,
                'Content-Disposition' => 'inline; filename='.$file['filename'].'.'.$file['extension'],
            ]);
        }
        return false;

    }

	public function upload($dir, $file, $fName = null, $fExtension = null){
        if( $this -> diskId != null){
            $recursive = false; 
            $filename = $fName == null ? $file->getClientOriginalName(): $fName;
            $fileExtension =   $fExtension == null ?$file->getClientOriginalName(): $fExtension;
           
            
            Storage::disk($this -> diskId)->putFileAs($dir,$file,$filename);
            $contents = collect(Storage::disk($this -> diskId)->listContents($dir, $recursive));
            $savedFile = $contents
                ->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
                ->where('extension', '=', pathinfo($fileExtension, PATHINFO_EXTENSION))
                ->first(); 
            return [
                "errors" => null,
                "id_on_cloud" => $savedFile != null ?  $savedFile['basename'] : null,
                "success" => true,
            ];

        }
        return [
            "errors" => "Neinicializuotas debesų saugyklos rinkmenų servisas",
            "success" => null,
        ];
       
    }
	public function delete($dir, $fileId){
        return  Storage::disk($this -> diskId)->delete($dir.'/'.$fileId);
    }

	public function rename($dirPath,  $fileId, $name, $fileLocal){

        $contents = collect(Storage::disk($this -> diskId)->listContents($dirPath, false));
        $item = $contents
            ->where('basename', '=', $fileId)
            ->first(); 
   
        if($item != null){
            return  Storage::disk($this -> diskId)->move($item ['path'], $dirPath.'/'.$name);
        }
        return false;
        
    }
    public function move($dir, $id, $destanationDir){

        $contents = collect(Storage::disk($this -> diskId)->listContents($dir, false));
        $isDirectory = Folder::where('id_on_cloud',$id)->where('cloud_service',$this ->  getId())->count();
        $fileName = null;
        $item = $contents
            ->where('basename', '=', $id)
            ->first(); 
        
        if($item != null){
            if($isDirectory){
                $fileName =  $item['filename'];
            } else{
                $fileName =  $item['filename'].'.'. $item['extension'];
            }
           return  Storage::disk($this -> diskId)->move( $item['path'],$destanationDir.'/'.$fileName);
        }
        return false;
    }

    public function deleteDirectory($dir){

        if( $this -> diskId != null){
            return Storage::disk($this -> diskId)->deleteDirectory($dir);
        }
        return false;       
    }

	public function makeDirectory($parentOnCloud, $name, $fileId){

        if( $this -> diskId != null){
            $savedOnCloud = Storage::disk($this -> diskId)->makeDirectory( $parentOnCloud.'/'.$name);
            $folder = $this -> getContentsOfDirectory($parentOnCloud)->where('filename',$name)->where('extension','')->first();
            if( $folder ){
                Folder::create([
                    'file_id' => $fileId,
                    'cloud_service' => $this -> diskId,
                    'id_on_cloud'=>  $folder['basename'],
                ]);
                return $savedOnCloud;
            }
        }
        return false;
    }

    public function getContentsOfDirectory($dir, $recursive = false){
        if( $this -> diskId != null){
            return collect(Storage::disk($this -> diskId)->listContents($dir, $recursive));
        }
        return false;   
    }
}