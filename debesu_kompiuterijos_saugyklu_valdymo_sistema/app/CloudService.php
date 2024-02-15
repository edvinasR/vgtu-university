<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Services\GoogleDriveFileService;
use App\Services\DropBoxFileService;
use App\Services\OneDriveFileService;

class CloudService extends Model
{
    protected $table = 'cloud_services';
    public $timestamps = true;
    protected $fillable = [
        'root_folder_id', 'logo', 'type', 'access_token', 'activated', 'refresh_token', 'name', 'user_id', 'free_storage', 'identity', 'owner' , 'deleted',
    ];


    public function type(){
        switch($this->type){
            case 'google':
                return  'GoogleDrive';
                break;
            case 'onedrive':
                 return  'OneDrive';
                break;
            case 'dropbox':
                  return  'DropBox';
                break;
            default:
                return null;

        }
    }
    public function getServiceInstance(){ 

        //Jeigu saugykla nėra registruota per Oauth mechanizmo protokolą
        if($this->activated == 0) 
            return null;
            
        switch($this->type){
            case 'google':
                return  new GoogleDriveFileService($this -> id);
                break;
            case 'onedrive':
                return new OneDriveFileService($this -> id);
                break;
            case 'dropbox':
                return new DropBoxFileService($this -> id);
                break;
            default:
                return null;

        }
    }
}
