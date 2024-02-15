<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zinute extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'zinutes';

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
    protected $fillable = ['tema', 'siuntejas','perskaitytas', 'zinute', 'instruktorius', 'mokinys'];

    
}
