<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Statistics extends Model
{
    protected $table = 'statistics';
    public $timestamps = true;
    protected $fillable = ['user_id', 'disk_id', 'key', 'value'];

}
