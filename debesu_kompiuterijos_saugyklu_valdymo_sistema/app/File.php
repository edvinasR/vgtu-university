<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Folder;

class File extends Model
{
   
    protected $table = 'files';
    public $timestamps = true;
    protected $fillable = [
        'storage_service_id', 'storage_service', 'size', 'name', 'extension', 'depth', 'dowload_link', 'parent_id', 'user_id', 'chunked',
    ];


    public function root($user = null){
        $userId = null;
        if(Auth::user() != null){
            $userId= Auth::user()->id;
        }else if($user != null){
            $userId  = $user;
        }

        if($userId != null){
            return $this->whereNull('parent_id')->where('name','root')->where('user_id',$userId )->first();
        }else return null;
        
    }
    public function folder($storageService = null){
        return Folder::where('cloud_service',$storageService == null? $this -> storage_service : $storageService)->where('file_id',$this -> id)->first();
    }
}
