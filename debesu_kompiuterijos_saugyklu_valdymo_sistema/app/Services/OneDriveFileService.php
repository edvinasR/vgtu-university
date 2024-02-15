<?php

namespace App\Services;
use App\Services\AbstractFileService;
use Storage;
use App\File;
use App\CloudService;
use Log;
use App\Folder;
use App\Helpers\Oauth;
use App\Helpers\Formatters;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class OneDriveFileService extends AbstractFileService{

    public function __construct($diskId){
        parent::__construct($diskId);
    }

    public function about(){
        // Objekto gyvavimo laikas yra viena užklausa todėl gaima išsaugoti informacija ir nesikreipti į API kelis kartus, per keletą skeundžių informacija drastiškai nepasikeis
        if($this -> about != null){
            return   $this -> about;
        }

        $result = [];
        if( $this -> diskId != null){
            $service = CloudService::where('id',$this -> diskId)->first();
            $client = new Client();       
            $results = $client->get('https://graph.microsoft.com/v1.0/me/drive', [
                'headers' => [
                    'Authorization' => 'Bearer '. $service->access_token
                ]
                
            ]);
            $json = json_decode( $results->getBody()->getContents(), true);
            $result['used'] =  $json['quota']['used'];
            $result['total'] =  $json['quota']['total'];
            $result['remaining'] =  $json['quota']['remaining'];
            $result['user'] =  $json['owner']['user']['displayName'];

        }
        return $result;
    }

    public function freeSpace(){
        $info =  $this->about();
        $bytesUsed =  $info['remaining'];
         CloudService::where('id',$this -> diskId )->update(['free_storage' => $bytesUsed , 'owner'=> $info['user']]);
         return Formatters::formatBytes($bytesUsed  ,2);
    }
    public function usedSpace(){
        $info =  $this->about();
         return  Formatters::formatBytes( $info['used'],2);
    }
    public function getBelongsTo(){
        $info =  $this->about();
         return  $info['user'];
    }

	public function rename($dirPath,  $fileId, $name, $fileLocal){
        parent::rename($dirPath,  $fileId, $name, $fileLocal);
        $fileInfo = File::where("id",$fileLocal)->first();
        if($fileInfo -> extension == "a_folder"){
            Folder::where('file_id',$fileLocal)->where('cloud_service',$this->getId())->update(["id_on_cloud" => $name]);   
        }else{
            $fileInfo->update(["storage_service_id" => $name]);        
        }
    }

}