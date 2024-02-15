<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Folder extends Model
{
   
    protected $table = 'folders';
    public $timestamps = true;
    protected $fillable = [
        'file_id', 'cloud_service', 'id_on_cloud',
    ];



}
