<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paskaita extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'paskaitos';

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
    protected $fillable = ['pavadinimas', 'vieta', 'praktine_paskaita', 'pradzia', 'pabaiga', 'aprasymas', 'instruktorius', 'mokinys'];

    
}
