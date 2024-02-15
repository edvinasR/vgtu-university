<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Settings;
use Config;
use Storage;
use App\Helpers\Oauth;
use Redirect;
use App\CloudService;
use Google_Client;
use Google_Service_Drive;
use App\Helpers\DisksConfigurationUtility;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Folder;
use App\File;
use App\Jobs\CreateFolderStructureOnNewStorageService;
use Illuminate\Support\Facades\Input;


class OAuthController extends Controller
{
    public function googleRediriect(Request $request ){
        $code =  $request->query('code');
        $client = (new Oauth($request->query('state')))-> getClient();
        $serviceData = CloudService::findOrFail($request->query('state'));
        try
        {
          if(!isset($code))
          {
                $auth_url = $client->createAuthUrl();
                return Redirect::to($auth_url);
          }
          else
          {
            // Iš gauto rakto gaunamas priegos raktas ir rakto anaujinimo kodas
            $client->authenticate($code);
            $token = $client->getAccessToken();
  
            if(isset($token['refresh_token']))
            {
                $serviceData  -> refresh_token =  $token['refresh_token'];
                $serviceData  -> access_token = $token['access_token'];
                $serviceData  -> save();
                if($serviceData  -> root_folder_id == null ){
                    $this-> createRootFolderAndSync($serviceData ,[
                        "id" => $serviceData -> id,
                        "type" => "google",
                        'refresh_token' => isset($token['refresh_token']) ? $token['refresh_token'] : null,
                        'access_token' => $token['access_token'],
                    ]);
                } 
            }
            else
            {
                  if($serviceData  -> refresh_token == null && $serviceData -> activated == 0)
                  {
                      $serviceData ->delete();
                      return Redirect::to('/nustatymai')->with('disk_error', 'Tokia Google Drive saugykla jau egzistuoja');
                  }
                  else
                  {
                      $serviceData-> update(["access_token" => $token['access_token']] );
                      return Redirect::to('/rinkmenos/root')->with('disk_success', 'Google Drive saugyklos prieigos raktas atnuajintas sėkmngai!');
                  }
            }
            return Redirect::to('/nustatymai')->with('disk_success', 'Google Drive saugykla sukurta sėkmngai!');
          }
        }catch(\Exception $ex){
            Log::error($ex);
            return Redirect::to('/nustatymai')->with('disk_error', 'Serverio kalidos bandant pridėti saugyklą... Prašome pabandyti vėliau');
        }
    }
    public function oneDriveRedirect(Request $request ){
        $code =  $request->query('code');
        $state = $request->query('state');
        //
        try{
            $serviceData = CloudService::findOrFail($state);
            // Užkalusą į autorizacijos serverį su gautu raktu norint gauti priegos prie išteklių raktą pagal Oauth 2.0 
            $client = new Client(); //GuzzleHttp\Client
            $result = $client->post('https://login.live.com/oauth20_token.srf', [
                'headers' => [
                    "Content-Type" => "application/x-www-form-urlencoded"
                ],
                'form_params' => [
                    'client_id' =>  env('ONEDRIVE_CLIENT'),
                    'redirect_uri' => env('APP_URL').'/oauth/onedrive',
                    'code' => $code ,
                    'client_secret' =>  env('ONEDRIVE_SECRET'),
                    'grant_type' => 'authorization_code',
                ]
            ]);
            $data =  json_decode($result->getBody()->getContents(), true);
            $dropBoxService = CloudService::where('type','onedrive')->where('identity',$data['user_id'])->first();
            if($dropBoxService != null){
                $dropBoxService -> update([
                    "access_token" => $data['access_token'],
                    "refresh_token" =>  $data['refresh_token'],
                    "activated" => 1,
                    "identity" => $data['user_id'],
                ]);
                $serviceData -> delete();
                return Redirect::to('/nustatymai')->with('disk_success', 'OneDrive saugykla atnaujinta sėkmngai!');
            }
            else{
                $serviceData -> update([
                    "access_token" => $data['access_token'],
                    "refresh_token" =>  $data['refresh_token'],
                    "activated" => 1, 
                    "identity" => $data['user_id'],
                ]);
                if($serviceData  -> root_folder_id == null ){
                    $this-> createRootFolderAndSync($serviceData ,[
                        "id" => $serviceData -> id,
                        "type" => "onedrive",
                        "access_token" =>$data['access_token'],
                    ]);
                 }
                return Redirect::to('/nustatymai')->with('disk_success', 'OneDrive saugykla sukurta sėkmngai!');
            }
        }catch(\Exception $ex){
            Log::error($ex);
            return Redirect::to('/nustatymai')->with('disk_error', 'Serverio kalidos bandant pridėti saugyklą... Prašome pabandyti vėliau');
        }
    }

    public function dropBoxRedriect(Request $request ){

        $code =  $request->query('code');
        $state = $request->query('state');
        try{
            $serviceData = CloudService::findOrFail($state);
            // Užkalusą į autorizacijos serverį su gautu raktu norint gauti priegos prie išteklių raktą pagal Oauth 2.0 
            $client = new Client(); //GuzzleHttp\Client
            $result = $client->post('https://api.dropboxapi.com/oauth2/token', [
                'form_params' => [
                    'code' => $code,
                    'grant_type' => 'authorization_code',
                    'client_id' => env('DROPBOX_CLIENT'),
                    'client_secret' =>  env('DROPBOX_SECRET'),
                    'redirect_uri' => env('APP_URL')."/oauth/dropbox",
                ]
            ]);
            $data =  json_decode($result->getBody()->getContents(), true);
            $dropBoxService = CloudService::where('type','dropbox')->where('identity',$data['account_id'])->first();
            if($dropBoxService != null){
                $dropBoxService -> update([
                    "access_token" => $data['access_token'],
                    "activated" => 1,
                    "identity" => $data['account_id'],
                ]);
                $serviceData -> delete();
                return Redirect::to('/nustatymai')->with('disk_success', 'DropBox saugykla atnaujinta sėkmngai!');
            }else{
                $serviceData -> update([
                    "access_token" => $data['access_token'],
                    "activated" => 1, 
                    "identity" => $data['account_id'],
                ]);
                if($serviceData  -> root_folder_id == null ){
                    $this-> createRootFolderAndSync($serviceData ,[
                        "id" => $serviceData -> id,
                        "type" => "dropbox",
                        "access_token" =>$data['access_token'],
                    ]);
                 }
                return Redirect::to('/nustatymai')->with('disk_success', 'DropBox saugykla sukurta sėkmngai!');
            }
        }catch(\Exception $ex){
            Log::error($ex);
            return Redirect::to('/nustatymai')->with('disk_error', 'Serverio kalidos bandant pridėti saugyklą... Prašome pabandyti vėliau');
        } 
    } 

    private function createRootFolderAndSync($serviceData,$config){
        $diskUtility = new DisksConfigurationUtility();
        $diskUtility -> registerDisk($config);
        $diskUtility -> getDisk($serviceData -> id) ->makeDirectory('root');
        $directories = $diskUtility->getDisk($serviceData -> id)->directories();
        $rootId =  end($directories);
        $serviceData -> update([
            "root_folder_id" => $rootId,
            "activated" => 1,
        ]);
        $rootFolderId =   (new File()) -> root()->id;
        //Inserting new entry to Folders table
         $mapping = Folder::create([
                 'file_id' => $rootFolderId,
                 'cloud_service' => $serviceData->id,
                 'id_on_cloud'=>  $serviceData  -> root_folder_id,
         ]);
         //TODO Atkomentuoti kuoment įfyvendinsiu Failų sistemos dropbox interfeisą 
         $job = (new CreateFolderStructureOnNewStorageService($serviceData->id));
         $this->dispatch($job);
    }
}
