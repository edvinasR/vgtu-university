<?php

namespace App\Http\Controllers\Ket_instruktorius;

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
class IvertinimaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
	
	public function getGrupes(Request $request){
		
		$grupes = $request->get('grupes');
		$KET_gruppes = collect(KET_grupe::whereIn('id',$grupes)->orderBy('pavadinimas')->get());
		$KET_gruppes = $KET_gruppes ->mapWithKeys(function($item){
		
			return [$item['id'] => $item['pavadinimas']];
		});
		
		return response()->json(["success" => true, "data" => $KET_gruppes]);
		
	}
	
	public function getMokiniai($id){
	
		$mokiniai = collect(Mokiny::where('grupe',$id)->get()) -> mapWithKeys(function($item){
				
			return [$item['id'] => ($item->naudotojas()->first()->name.' '.$item->naudotojas()->first()->surename)];
		});	
		return response()->json(["success" => true, "data" => $mokiniai]);
	
	}
	
	public function getIvertinimai($paskaita){
	
		$rezultatas = [];
		$grupes = collect(GrupiuPaskaitos::where('paskaita',$paskaita)->get())->pluck('grupe')->unique()->toArray();
		
		foreach($grupes as $grupe){
			$temp = [];
		
			$grupesPav = KET_grupe::where('id',$grupe)->first()->pavadinimas;
			$mokiniaiData = Mokiny::where('grupe',$grupe)->get();
			$mokiniai = collect($mokiniaiData) -> mapWithKeys(function($item){
			
				return [$item['id'] => ($item->naudotojas()->first()->name.' '.$item->naudotojas()->first()->surename)];
			});
			$mokiniaiIds = collect($mokiniaiData)->pluck('id')->unique()->toArray();
			$ivertiniai = collect(Ivertinimas::whereIn('mokinys',$mokiniaiIds)->where('paskaita',$paskaita)->get())->map(function($item) use ($mokiniai){
				return [
						"id" => $item -> id,
						"grupe" => $item -> grupe,
						"mokinys" =>  $mokiniai[$item -> mokinys],
						"ivertinimas" =>  $item -> ivertinimas,
						"aprasymas" => $item -> aprasymas,
				];
				
			});
				$temp['grupe'] = $grupesPav;
				$temp['mokiniai'] = $ivertiniai;
				array_push($rezultatas, $temp);
	
		}
	
			return response()->json(["success" => true, "data" => $rezultatas]);
	
	}
	public function index(Request $request)
	{
		$grupes = Helpers::getGrupesNames(null);
		return view('ket_instruktorius.ivertinimai.index', compact('grupes'));
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
		 
		Log::info( $request->all());
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
	
		
		$data = $request->all();
		Log::info($data);
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
