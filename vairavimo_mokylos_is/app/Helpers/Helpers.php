<?php
namespace App\Helpers;

use App\User;
use App\Teises;
use App\Instruktorius;
use App\KET_grupe;
use App\Mokiny;
use App\Paskaita;

class Helpers{
	
	public static function getNaudotojaiNamesArray($role){
		$data = [];
		if(is_array($role)){
			$data = $role;
		}else{
			$data = [$role];	
		}
		
		$roles = collect(Teises::whereIn('pavadinimas',$data)->get())->pluck('id');
		return collect(User::whereIn('teises_FK',$roles)->get()) -> mapWithKeys(function($item){
		
			return [$item['id'] => ($item['name'].' '.$item['surename'])];
		});
	}
	
	public static function getInstruktoriausMokianiaiNamesArray($id){

		return collect(Mokiny::where('vairavimo_instruktorius',$id)->get()) -> mapWithKeys(function($item){
		
			return [$item['id'] => ($item->naudotojas()->first()->name.' '.$item->naudotojas()->first()->surename)];
		});
		
	}
	public static function getMokianioInstruktorius($id){
	
		return collect(Mokiny::where('id',$id)->get()) -> mapWithKeys(function($item){
	
			$instruktoriausData = Instruktorius::where('id',$item -> vairavimo_instruktorius)-> first(); 
	
			return [$instruktoriausData -> id => ($instruktoriausData->naudotojas()->first()->name.' '.$instruktoriausData->naudotojas()->first()->surename)];
		});
	
	}
	
	
	
	
	public static function getMokiniaiNamesArray(){
	

	return collect(Mokiny::get()) -> mapWithKeys(function($item){
				
			return [$item['id'] => ($item->naudotojas()->first()->name.' '.$item->naudotojas()->first()->surename)];
		});
	}
	
	

	public static function getPaskaitosInfo(){
	
	
		return collect(Paskaita::get()) -> mapWithKeys(function($item){
	 
			return [$item['id'] => ($item->pavadinimas.' ('.substr($item->pradzia,0,16).' iki '.substr($item->pabaiga,0,16).')
					')];
		});
	}
	
	public static function getTeisesArray(){

		return collect(Teises::get()) -> mapWithKeys(function($item){
			return [$item['id'] => $item->pavadinimas];
		});
		
		
	}
	public static function getNaudotojaiWithoutRoles($role){
	
		$ids = [];
		if($role == 'Mokinys')
			$ids = collect(Mokiny::get())->pluck('naudotojas')->unique();
		if($role == 'KET dėstytojas')
			$ids = collect(Instruktorius::get())->pluck('naudotojas')->unique();
		if($role == 'Praktinio vairavimo instruktorius')
			$ids = collect(Instruktorius::get())->pluck('naudotojas')->unique();
		if($role == 'Administratorius')
			$ids = [];
		
		$roleId = Teises::where('pavadinimas',$role)->first()->id;
		return collect(User::where('teises_FK',$roleId)->whereNotIn('id',$ids)->get()) -> mapWithKeys(function($item){
		
			return [$item['id'] => ($item['name'].' '.$item['surename'])];
		});
	}
	
	public static function getInstruktoriaiNamesArray(){
		return collect(Instruktorius::get()) -> mapWithKeys(function($item){
				
			return [$item['id'] => ($item->naudotojas()->first()->name.' '.$item->naudotojas()->first()->surename)];
		});
	
	
	}
	
	public static function getGrupesNames($kategorija){

		$gr = new KET_grupe();
		if($kategorija!=null){
			$gr = $gr -> where('kategorija',$kategorija);
		}

		return collect($gr->get()) -> mapWithKeys(function($item){
		
			return [$item['id'] => ($item['pavadinimas'])];
		});
	
	
	}
	
}