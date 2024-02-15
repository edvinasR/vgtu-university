<?php 
namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Teises extends Eloquent {
	
	protected $table = 'teises';
    public function users() {
    	return $this->hasMany('App\User','teises_FK');
    }

}
