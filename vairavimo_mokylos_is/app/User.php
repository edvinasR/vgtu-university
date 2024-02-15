<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'surename', 'email', 'password', 'teises_FK',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    
    public function userRoleName(){
    	$pav = null;
    	$data =Teises::where('id', $this->teises_FK)->first();
    	if(count($data)){
    		$pav =  $data -> pavadinimas;
    	}
    	return $pav;
    }
    
    public function isKETinstruktorius(){
		$data =Teises::where('id', $this->teises_FK)->first();
    	if(count($data)){
    		if($data -> pavadinimas == 'KET dėstytojas'){
    			return true;
    		}
    		return false;
    	}
    	return false;
    	
    }
    
    public function isMokinys(){
    	$data =Teises::where('id', $this->teises_FK)->first();
    	if(count($data)){
    		if($data -> pavadinimas == 'Mokinys'){
    			return true;
    		}
    		return false;
    	}
    	return false;
    	 
    }

    public function isPraktinisInstruktorius(){
    	$data =Teises::where('id', $this->teises_FK)->first();
    	if(count($data)){
    		if($data -> pavadinimas == 'Praktinio vairavimo instruktorius'){
    			return true;
    		}
    		return false;
    	}
    	return false;
  	  	
    	
    }
    
    public function getRoleEntityId(){
    	$data =Teises::where('id', $this->teises_FK)->first();
    	
    	if($data->pavadinimas == "KET dėstytojas" || $data->pavadinimas == "Praktinio vairavimo instruktorius"){
    		return Instruktorius::where('naudotojas',$this->id)->first()->id;
    	}else if($data->pavadinimas == "Mokinys" ){
    		return Mokiny::where('naudotojas',$this->id)->first()->id;
    	}
    	else return null;
    	
    }
    public function isAdmin()
    {
    	$data =Teises::where('id', $this->teises_FK)->first();
    	if(count($data)){
    		if($data -> pavadinimas == 'Administratorius'){
    			return true;
    		}
    		return false;
    	}
    	return false;
    }
}
