<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Settings extends Model
{
   
    protected $table = 'settings';
    public $timestamps = true;
    protected $fillable = [
        'id', 'user_id', 'view_format',
    ];



}
