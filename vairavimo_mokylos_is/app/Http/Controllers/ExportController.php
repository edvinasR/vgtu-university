<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\KET_grupe;
use App\Mokiny;
use App\User;
use Log;
use PDF;
use App\Instruktorius;
use DateTime;
use App\Paskaita;
use App\Helpers\Helpers;
use PhpParser\Builder\Use_;
use App\GrupiuPaskaitos;
class ExportController extends Controller
{
    public function exportGroups(){
    
    	$data = [];
    	$grupes = KET_grupe::get();
    	

    	foreach($grupes as $grupe){
    		$item = [];
    		$item['grupe'] = $grupe;
    		
    		$mokiniai = Mokiny::where('grupe', $grupe->id)->get();	
    		$mokiniai = $mokiniai -> map(function($item){
    		$naudojas = User::where('id',$item->naudotojas)->first();
    		$inst = null;
    		$instruktorius = Instruktorius::where('id',$item->vairavimo_instruktorius)->first();
    		if(count($instruktorius)){
    			
    			$temp = User::where('id',$instruktorius->naudotojas)->first();
    			$inst = $temp-> name.' '.$temp-> surename;
    		}
    				return [
    						"id" => $item -> id,
    						"vardas" => $naudojas->name,
    						"pavarde" =>  $naudojas->surename,
    						"el_pastas" =>  $naudojas->email,
    						"kategorija" => $item -> kategorija,
    						"vairavimo_instruktorius" =>   $inst
    				];		
    		});
    			$item['mokiniai'] = $mokiniai;
    			array_push($data, $item);
    	}
		
    	$pdf = PDF::loadView('exports.grupes',compact('data')) -> setPaper('A4', 'landscape');
            return $pdf->download('geupes.pdf');

    }
    
    
    public function exportGroup($id){
    	 
    	$data = [];
    	$grupes = KET_grupe::where('id',$id)->get();
    	 
    
    	foreach($grupes as $grupe){
    		$item = [];
    		$item['grupe'] = $grupe;
    
    		$mokiniai = Mokiny::where('grupe', $grupe->id)->get();
    		$mokiniai = $mokiniai -> map(function($item){
    			$naudojas = User::where('id',$item->naudotojas)->first();
    			$inst = null;
    			$instruktorius = Instruktorius::where('id',$item->vairavimo_instruktorius)->first();
    			if(count($instruktorius)){
    				 
    				$temp = User::where('id',$instruktorius->naudotojas)->first();
    				$inst = $temp-> name.' '.$temp-> surename;
    			}
    			return [
    					"id" => $item -> id,
    					"vardas" => $naudojas->name,
    					"pavarde" =>  $naudojas->surename,
    					"el_pastas" =>  $naudojas->email,
    					"kategorija" => $item -> kategorija,
    					"vairavimo_instruktorius" =>   $inst
    			];
    		});
    			$item['mokiniai'] = $mokiniai;
    			array_push($data, $item);
    	}
    
    	$pdf = PDF::loadView('exports.grupes',compact('data')) -> setPaper('A4', 'landscape');
    	return $pdf->download('geupes.pdf');
    
    }
    
    
    public function exportuotiTvarkarasti($instruktoriausId,$start, $end){
    	$data = [];
    	$startas = $start;
    	$pabaiga = $end;
    
    	while (strtotime($startas) <= strtotime($pabaiga)) {
    		$item = [];
 			$dabar = $startas;
    		$startas = date ("Y-m-d", strtotime("+1 day", strtotime($startas)));
    		$item['data'] = $dabar;
    		$paskaitos = Paskaita::where('pradzia','>',$dabar)->where('pabaiga','<',$startas)->where('instruktorius',$instruktoriausId)->get();
    		if(count($paskaitos)>0){
    			$item['paskaitos'] = $paskaitos;
    			array_push($data, $item);
    		}
    	}

    	$pdf = PDF::loadView('exports.tvarkarastis',compact('data')) -> setPaper('A4', 'landscape');
    	return $pdf->download('tvarkarastis.pdf');	
    }
    
    public function exportuotiMokinioTvarkarasti($mokinioId,$start, $end){
    	$data = [];
    	$startas = $start;
    	$pabaiga = $end;
    	$mokinys = Mokiny::where('id',$mokinioId)->first();
    	
    	while (strtotime($startas) <= strtotime($pabaiga)) {
    		$item = [];
    		$dabar = $startas;
    		$startas = date ("Y-m-d", strtotime("+1 day", strtotime($startas)));
    		$item['data'] = $dabar;

    		if(count($mokinys)){
    			$duomenys = collect(Paskaita::where(function($query) use($mokinys)
    			{
    				$query->whereNull('mokinys')->orWhere('mokinys',$mokinys -> id);
    			})->where('pradzia','>',$dabar)->where('pabaiga','<',$startas)->get());
    				
    		
    			$teorinesPaskaitosIds = collect(GrupiuPaskaitos::whereIn('paskaita',$duomenys -> pluck('id')->unique()->toArray())->where('grupe',$mokinys->grupe)->get())->pluck('paskaita')->unique()->toArray();
    				
    			if(count($duomenys)){
    				$rez =$duomenys ->map(function($item) use($mokinys, $teorinesPaskaitosIds) {
    						
    					if($item -> mokinys  == $mokinys ->id || (in_array($item->id, $teorinesPaskaitosIds))){
    						$instruktorius = Instruktorius::where('id',$item->instruktorius)->first();
    						if(count($instruktorius)){
    		
    							$vardas = User::where('id',$instruktorius->naudotojas)->first();
    		
    							return [
    									"id" => 	$item -> id,
    									"pradzia" => $item -> pradzia,
    									"pabaiga" => $item -> pabaiga,
    									"pavadinimas" => $item -> pavadinimas,
    									"aprasymas" => $item -> aprasymas,
    									"praktine" => $item -> praktine_paskaita,
    									"vieta"  => $item -> vieta,
    									"instruktoriaus_vardas" =>  $vardas-> name.' '.$vardas->surename,
    									"numeriai" => $instruktorius ->  transporto_priemones_numeris,
    		
    							];
    						}
    					}
    				});
    					if(count($rez)>0){
    						$item['paskaitos'] = $rez;
    						array_push($data, $item);
    					}
    				
    			}
    		}
    		//

    	}

    
    	$pdf = PDF::loadView('exports.mokinio_tvarkarastis',compact('data')) -> setPaper('A4', 'landscape');
    	return $pdf->download('tvarkarastis.pdf');
    }
    
    
    public function exportuotipraktiniuPaskaituTvarkarasti($instruktoriausId,$start, $end){
    	$data = [];
    	$startas = $start;
    	$pabaiga = $end;
    
    	$mokiniai = Helpers::getMokiniaiNamesArray();
    	while (strtotime($startas) <= strtotime($pabaiga)) {
    		$item = [];
    		$dabar = $startas;
    		$startas = date ("Y-m-d", strtotime("+1 day", strtotime($startas)));
    		$item['data'] = $dabar;
    		$paskaitos = Paskaita::where('pradzia','>',$dabar)->where('pabaiga','<',$startas)->where('instruktorius',$instruktoriausId)->get();
    		if(count($paskaitos)>0){
    			$item['paskaitos'] = $paskaitos;
    			array_push($data, $item);
    		}
    	}
    
    	$pdf = PDF::loadView('exports.tvarkarastis_prkatnis',compact('data','mokiniai')) -> setPaper('A4', 'landscape');
    	return $pdf->download('tvarkarastis.pdf');
    }
}