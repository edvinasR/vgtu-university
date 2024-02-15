<?php

namespace App\Helpers;


use Illuminate\Support\Facades\Auth;
use Google_Client;
use Google_Service_Drive;
use App\CloudService;
use Log;
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
class Oauth {

    private $client;
    private $service;
    function __construct($serviceId){
        $this -> service = CloudService::findOrFail($serviceId);
        $this -> registerCloudDisk();
    }

    public function getClient(){
 
        return $this -> client ;
    }

    public function getAutorizedHTTPInstance(){
        
        
        $this -> client -> refreshToken($this -> service ->refresh_token);
        return $this -> client ->authorize();
    }

    public function createOauthUrl(){

        $type = $this -> service -> type;
        if($type == 'google'){
            return $this->createGoogleDriveOauth();
        }else if($type == 'dropbox'){
            return $this->createDropBoxDriveOauth();
        }else if($type == 'onedrive'){
            return $this->createOneDriveOauth();         
        }
        
    }
    //-------------Inkapsuliuoti metodai
    
    private function registerCloudDisk(){
        $type = $this -> service -> type;
        if($type == 'google'){
            $this -> client = new Google_Client();
            $this -> client ->setClientID(env('GOOGLE_CLIENT'));
            $this -> client ->setClientSecret(env('GOOGLE_SECRET'));
            $this -> client ->addScope(Google_Service_Drive::DRIVE);
            $this -> client ->setState($this -> service -> id);
            $this -> client ->setRedirectUri(env('APP_URL').'/oauth/google');
            $this -> client ->setAccessType(env('OAUTH'));        // offline access
            $this -> client ->setIncludeGrantedScopes(true);   // incremental auth
           // $this -> client->setApprovalPrompt('force');
        }else if($type == 'dropbox'){

        }
    }

    private function createGoogleDriveOauth(){
        $auth_url = $this -> client->createAuthUrl();
        return   $auth_url ;
    }
    private function createDropBoxDriveOauth(){
        return  "https://www.dropbox.com/oauth2/authorize?client_id=".env('DROPBOX_CLIENT')."&response_type=code&force_reauthentication=true&force_reapprove=false&redirect_uri=".env('APP_URL')."/oauth/dropbox&state=".$this -> service -> id;
    }

    private function createOneDriveOauth(){

        $auth_url = "https://login.live.com/oauth20_authorize.srf?client_id=".env('ONEDRIVE_CLIENT')."&scope=files.readwrite offline_access&response_type=code&redirect_uri=".env('APP_URL')."/oauth/onedrive&state=".$this -> service -> id;
        return $auth_url;

    
    }
}