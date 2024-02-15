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

class DropBoxFileService extends AbstractFileService{

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
            $results = $client->post('https://api.dropboxapi.com/2/users/get_space_usage', [
                'headers' => [
                    'Authorization' => 'Bearer '. $service->access_token
                ]
                
            ]);
            $json = json_decode( $results->getBody()->getContents(), true);
            $result['used'] =  $json ['used'];
            $result['total'] =  $json ['allocation']['allocated'];
            $resultsUserInfo =$client->post('https://api.dropboxapi.com/2/users/get_account', [
                'headers' => [
                    'Authorization' => 'Bearer '. $service->access_token,
                    'Content-Type' => 'application/json'
                ],
                "body" => json_encode(["account_id" => $service -> identity])      
            ]);
            $json = json_decode( $resultsUserInfo->getBody()->getContents(), true);
            $result['user'] =  $json;
            $this -> about =  $result;
        }
        return $result;
    }

    public function freeSpace(){
        $info =  $this->about();
        $bytesUsed =  $info ['total'] - $info['used'];
         CloudService::where('id',$this -> diskId )->update(['free_storage' => $bytesUsed, 'owner'=> $info['user']['email'] ]);
         return Formatters::formatBytes($bytesUsed  ,2);
    }
    public function usedSpace(){
        $info =  $this->about();
         return  Formatters::formatBytes( $info['used'],2);
    }
    public function getBelongsTo(){
        $info =  $this->about();
         return  $info['user']['email'];
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