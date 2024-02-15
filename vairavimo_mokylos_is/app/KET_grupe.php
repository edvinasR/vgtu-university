<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KET_grupe extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'KET_grupes';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['kategorija', 'pavadinimas'];

    
}
