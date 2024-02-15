<?php

namespace App\Http\Controllers\Instruktorius;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Mokiny;
use Illuminate\Http\Request;
use Log;
use App\User;
use App\Instruktorius;
use App\KET_grupe;
use App\Helpers\Helpers;
use App\Ivertinimas;
use App\GrupiuPaskaitos;
use Illuminate\Support\Facades\Auth;
use App\Paskaita;
class IvertinimaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

	
	public function gautiInstruktoriausPaskaitas($strat, $end){
	
		$rez = [];
		$user= Auth::user();
		$qStart=gmdate("Y-m-d H:i:s", $strat);
		$qEnd=gmdate("Y-m-d H:i:s", $end);
		$instruktroius = Instruktorius::where('naudotojas',$user->id)->first();
		if(count($instruktroius)){
			$duomenys = collect(Paskaita::where('instruktorius',$instruktroius -> id)->whereNotNull('mokinys')->whereBetween('pradzia',[$qStart,$qEnd])->get());
			if(count($duomenys)){
	
	
				$rez =$duomenys ->map(function($item) {
	
					$mokinys = Mokiny::where('id',$item->mokinys)->first()->naudotojas;
					$u= User::where('id',$mokinys)->first();
	
					$ivertinimas = null;
					$aprasymas = null;
					$ivertinimo_id = null;
					
					$ivert = Ivertinimas::where('mokinys', $item->mokinys)->where('paskaita',$item->id)->first();
					
					if(count($ivert)){
						$ivertinimas = $ivert -> ivertinimas;
						$aprasymas = $ivert -> aprasymas;	
						$ivertinimo_id =  $ivert -> id;	
					}
					
					return [
							"id" => 	$item -> id,
							"pradzia" => $item -> pradzia,
							"pabaiga" => $item -> pabaiga,
							"pavadinimas" => $item -> pavadinimas,
							"aprasymas" => $aprasymas,
							"ivertinimas" => $ivertinimas,
							"ivertinimo_id" => $ivertinimo_id,
							"praktine" => 1,
							"mokinio_id" => $item -> mokinys,
							"mokinys" => $u->name.' '.$u->surename,
							"vieta"  => $item -> vieta
					];					
				});
			}
		}
		return response()-> json($rez);
	}
	
	public function getMokiniai(){
	
		$id = Instruktorius::where('naudotojas', Auth::user()->id)->first()->id;
		$mokiniai = collect(Mokiny::where('vairavimo_instruktorius',$id)->get()) -> mapWithKeys(function($item){
				
			return [$item['id'] => ($item->naudotojas()->first()->name.' '.$item->naudotojas()->first()->surename)];
		});	
		return $mokiniai;
	
	}
	

	public function index(Request $request)
	{
		$mokiniai = $this -> getMokiniai();
		return view('instruktorius.ivertinimai.index', compact('mokiniai'));
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		 
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function store(Request $request)
	{
		 
		$validator = \Validator::make($request->all(), [
				'paskaita' => 'required',
				'mokinys' => 'required',
				'ivertinimas' => 'required|integer|between:0,10',
				'aprasymas' => 'required'
		]);
		
		if ($validator->fails()) {
			return response()->json(["success"=>false, "errors" => $validator->errors()]);
		}
		$requestData = $request->all();
		$iv = Ivertinimas::where('paskaita',$requestData['paskaita'])->where('mokinys',$requestData['mokinys'])->first();
		
		if(count($iv)){
			$iv->update($requestData);
		}else{
			Ivertinimas::create($requestData);
		}

		return response()->json(["success"=>true]);		 
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 *
	 * @return \Illuminate\View\View
	 */
	public function show($id)
	{
	
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 *
	 * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		 
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param  int  $id
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function update(Request $request, $id)
	{
		$validator = \Validator::make($request->all(), [
				'paskaita' => 'required',
				'mokinys' => 'required',
				'ivertinimas' => 'required|integer|between:0,10',
				'aprasymas' => 'required'
		]);
		
		if ($validator->fails()) {
			return response()->json(["success"=>false, "errors" => $validator->errors()]);
		}
		
		$data = $request->all();
		Ivertinimas::where('id',$id)->update([
				"ivertinimas" => $request->get("ivertinimas"),
				"aprasymas" =>  $request->get("aprasymas"),
		]);
		
		return response()->json(["success"=>true]);
		 
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function destroy($id)
	{
		Ivertinimas::where('id',$id)->delete();
		return response()->json(["success"=>true]);
	}
}
