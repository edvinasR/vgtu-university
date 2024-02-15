<?php

namespace App\Http\Controllers\Mokinys;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Ivertinimas;
use Illuminate\Http\Request;
use App\Helpers\Helpers;
use Illuminate\Support\Facades\Auth;
use App\Paskaita;
use App\Instruktorius;
use App\User;
use App\MokinioBusena;

class IvertinimasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        $mokinioId = Auth::user()->getRoleEntityId();
        
        if (!empty($keyword)) {
             $ivertinimas = Ivertinimas::where('mokinys',$mokinioId)->where('Ivertinimass', 'LIKE', "%$keyword%")
                ->orWhere('aprasymas', 'LIKE', "%$keyword%")
                ->orWhere('mokinys', 'LIKE', "%$keyword%")
                ->orWhere('paskaita', 'LIKE', "%$keyword%")
                ->paginate($perPage);
        } else {
             $ivertinimas = Ivertinimas::where('mokinys',$mokinioId)->paginate($perPage);
        }

        $ivertinimas = $ivertinimas -> map(function($item){
        	$pask = Paskaita::where('id',$item->paskaita)->first();   	
        	$inst = Instruktorius::where('id',$pask->instruktorius)->first();
        	$naud = User::where('id',$inst->naudotojas)->first();
        	return [
        			"id" => $item  -> id,
        			"paskaita" => $pask -> pavadinimas,
        			"data" =>  $pask -> pradzia,
        			"ivertinimas" => $item -> ivertinimas,
        			"tipas" => $pask -> praktine_paskaita == 1 ? "Praktinė": "Ket",
        			"aprasymas" => $item -> aprasymas,
        			"instruktorius" => $naud -> name.' '.$naud-> surename,
        			
        	];
        		
        });


        
        return view('mokinys.ivertinimas.index', compact('ivertinimas'));
    }




    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    
    
    
    public function gautiEgzaminus()
    {
    	$id = Auth::user()->getRoleEntityId();
    	$praktinisEgz = 'Nelaikytas';
    	$teorinisEgz = 'Nelaikytas';
    	$ivertinimai = MokinioBusena::where('mokinys',$id)->first();
    	 if(count($ivertinimai)){
    	 	$praktinisEgz = $ivertinimai -> praktinio_egzamino_ivertinimas != null ? $ivertinimai -> praktinio_egzamino_ivertinimas : 'Nelaikytas';
    	 	$teorinisEgz = $ivertinimai -> teorinio_egzamino_ivertinimas != null ? $ivertinimai -> teorinio_egzamino_ivertinimas : 'Nelaikytas';
    	 	
    	 }

    		return view('mokinys.ivertinimas.egzaminai', compact('praktinisEgz', 'teorinisEgz' ));
    }
    
    public function show($id)
    {

    	$ivertinimai = Ivertinimas::where('id',$id)->get();
    	
    	$ivertinimai = $ivertinimai -> map(function($item){
    		$pask = Paskaita::where('id',$item->paskaita)->first();
    		$inst = Instruktorius::where('id',$pask->instruktorius)->first();
    		$naud = User::where('id',$inst->naudotojas)->first();
    		return [
    				"id" => $item  -> id,
    				"paskaita" => $pask -> pavadinimas,
    				"paskaitosAprasas" =>  $pask -> aprasymas,
    				"pradzia" =>  $pask -> pradzia,
    				"pabaiga" =>  $pask -> pabaiga,
    				"ivertinimas" => $item -> ivertinimas,
    				"tipas" => $pask -> praktine_paskaita == 1 ? "Praktinė": "Ket",
    				"aprasymas" => $item -> aprasymas,
    				"instruktorius" => $naud -> name.' '.$naud-> surename,			 
    		];
    	
    	});
        return view('mokinys.ivertinimas.show', compact('ivertinimai'));
    }

  
}
