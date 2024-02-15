<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileChunk extends Model
{
       
    protected $table = 'file_chunks';
    public $timestamps = true;
    protected $fillable = [
        'file_id', 'cloud_service', 'id_on_cloud', 'name', 'order',
    ];

}
