<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Instruktorius extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'instruktoriai';

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
    protected $fillable = ['transporto_priemones_numeris', 'telefonas', 'naudotojas'];

	public function naudotojas() {
		return $this->belongsTo ( 'App\User', "naudotojas" );
	}
	
}
