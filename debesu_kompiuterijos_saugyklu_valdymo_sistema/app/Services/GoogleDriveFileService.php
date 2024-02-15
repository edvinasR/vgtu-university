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

class GoogleDriveFileService extends AbstractFileService{

    public function __construct($diskId){
        parent::__construct($diskId);
    }

    public function about(){

        // Objekto gyvavimo laikas yra viena užklausa todėl gaima išsaugoti informacija ir nesikreipti į API kelis kartus, per keletą skeundžių informacija drastiškai nepasikeis
        if($this -> about != null){
            return   $this -> about;
        }

        if( $this -> diskId != null){
            $oauth =  new Oauth($this -> diskId);
            $httpClient = $oauth -> getAutorizedHTTPInstance();
            $response = $httpClient->get('https://www.googleapis.com/drive/v2/about')->getBody();
            $responseJSON = json_decode($response); 
            $this -> about = $responseJSON;
            return $responseJSON;

        }
        return null;
    }

    public function freeSpace(){
        // FIXME  Magiškas skaičius perkelti į konfigūraciją
        $bytesUsed = 16106127360  - $this->about()->quotaBytesUsed;
        CloudService::where('id',$this -> diskId )->update(['free_storage' => $bytesUsed  , 'owner'=> $this->about()->user->emailAddress ]);
        return Formatters::formatBytes($bytesUsed  ,2);
    }
    public function usedSpace(){
        return  Formatters::formatBytes($this->about()->quotaBytesUsed,2);
    }
    public function getBelongsTo(){
        return $this->about()->user->emailAddress;
    }
}