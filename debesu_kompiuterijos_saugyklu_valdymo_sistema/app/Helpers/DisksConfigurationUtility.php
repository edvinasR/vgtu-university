<?php

namespace App\Helpers;


use Illuminate\Support\Facades\Auth;
use Storage;
use Config;
use App\CloudService;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
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


class DisksConfigurationUtility {

   
    public function registerDisksInConfig($userId, $id = null){
            $saugyklos = CloudService::where('user_id',$userId )->where('deleted',0)->where('activated',1);
            if($id  != null){
                $saugyklos = $saugyklos->where('id',$id);
            }
            $saugyklos = $saugyklos -> get();
            foreach( $saugyklos as $saugykla){
                $this -> registerDisk([
                    'id' =>  $saugykla -> id,
                    'type' => $saugykla -> type,
                    'folderId' =>  $saugykla -> root_folder_id,
                    'access_token' =>  $saugykla -> access_token,
                    'refresh_token' =>  $saugykla -> refresh_token,
                ]);
                $this -> refreshTokenIfNeeded(  $saugykla);
            }
    }


    public function registerDisk($data){
        $disks =  Config::get('filesystems.disks');
        switch($data['type']){
            case 'google':{
                $disks[$data['id']] = [
                    'driver' => $data['type'],
                    'clientId' => env('GOOGLE_CLIENT'),
                    'clientSecret' =>  env('GOOGLE_SECRET'), 
                    'refreshToken' =>  isset($data['refresh_token'])? $data['refresh_token']: $data['access_token'] ,
                    'folderId' => isset($data['folderId']) ? $data['folderId'] : null
                 ];
                }
                 break;
            case 'dropbox':{
                $disks[$data['id']] = [
                    'driver' => $data['type'],
                    'access_token' =>   $data['access_token']
                 ];
                }
                break;
            case 'onedrive':{
                $disks[$data['id']] = [
                    'driver' => $data['type'],
                    'access_token' =>   $data['access_token'],
                    'root_folder_id' => isset($data['folderId']) ? $data['folderId'] : '',
                    ];
                }
                break;
        }   
        $settings = Config::set('filesystems.disks',$disks);

    } 

    public function updateDisk($id, $data){
        $disks =  Config::get('filesystems.disks');
        $disk = $disks[$id];
        $mergedConfig = array_merge($disk , $data);
        $disks[$id] = $mergedConfig;
        return Config::set('filesystems.disks',$disks);

    }
    public function getDisk($id){

        return  Storage::disk($id);
    }

    private function refreshTokenIfNeeded( $storageEntity){

        if($storageEntity -> type == 'onedrive') {

            try{
                $storageEntity -> getServiceInstance() -> about();
            }catch (\GuzzleHttp\Exception\ClientException $e) {
                $response = $e->getResponse();
                if ($response && $response->getStatusCode() == 401) {
                    //Gauname naują   prieigos raaktą iš refresh rakto
                    $client = new Client(); 
                    $result = $client->post('https://login.live.com/oauth20_token.srf', [
                        'headers' => [
                            "Content-Type" => "application/x-www-form-urlencoded"
                        ],
                        'form_params' => [
                            'client_id' =>  env('ONEDRIVE_CLIENT'),
                            'redirect_uri' => env('APP_URL').':'.env('APP_PORT').'/oauth/onedrive',
                            'refresh_token' =>  $storageEntity -> refresh_token ,
                            'client_secret' =>  env('ONEDRIVE_SECRET'),
                            'grant_type' => 'refresh_token',
                        ]
                    ]);
                    $data =  json_decode($result->getBody()->getContents(), true);
                    $storageEntity -> update([       
                        "access_token" => $data['access_token'],
                        "refresh_token" =>  $data['refresh_token'],
                        ]);
                    $this -> registerDisk([
                        'id' =>  $storageEntity -> id,
                        'type' => $storageEntity -> type,
                        'folderId' =>  $storageEntity -> root_folder_id,
                        'access_token' =>  $storageEntity -> access_token,
                        'refresh_token' =>  $storageEntity -> refresh_token,
                    ]);
                }
                else {
                  throw $e;
                }
            }catch(\Exception $exception){
                Log::error($exception);
            }
        }   
    }

}