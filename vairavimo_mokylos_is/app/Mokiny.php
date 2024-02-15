<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mokiny extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mokiniai';

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
    protected $fillable = ['kategorija', 'grupe', 'vairavimo_instruktorius', 'naudotojas'];
    
	public function naudotojas() {
		return $this->belongsTo ( 'App\User', "naudotojas" );
	}
	

    
}
