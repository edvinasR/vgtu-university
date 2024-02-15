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
use App\MokinioBusena;
class MokiniaiController extends Controller
{

	public function index(Request $request)
	{
		$grupe = $request->get('grupe');


		$keyword = $request->get('search');
		$perPage = 25;
	
		if(isset($grupe) && $grupe != -1){
			$mokinys = Mokiny::where('grupe',$grupe)->paginate($perPage);
		}
		else if (!empty($keyword)) {
			$mokinys = Mokiny::where('kategorija', 'LIKE', "%$keyword%")
			->orWhere('grupe', 'LIKE', "%$keyword%")
			->orWhere('vairavimo_instruktorius', 'LIKE', "%$keyword%")
			->orWhere('naudotojas', 'LIKE', "%$keyword%")
			->paginate($perPage);
		} else {
			$mokinys = Mokiny::paginate($perPage);
		}
	
		$users =  Helpers::getNaudotojaiNamesArray('Mokinys');
		$grupe = Helpers::getGrupesNames(null);
		return view('ket_instruktorius.mokinys.index', compact('mokinys','users','grupe'));
	}
	
	public function show($id)
	{
		$mokiny = Mokiny::findOrFail($id);
		$users =  Helpers::getNaudotojaiNamesArray('Mokinys');
		$grupe = Helpers::getGrupesNames(null);
		 
	
		return view('ket_instruktorius.mokinys.show', compact('mokiny','users','grupe'));
	}
	
	public function edit($id)
	{
		 
		$mokiny = Mokiny::findOrFail($id);
	
		$users =  Helpers::getNaudotojaiNamesArray('Mokinys');
		$busena = MokinioBusena::where('mokinys',$id)->first();
		$grupe = Helpers::getGrupesNames(null);
		$data = MokinioBusena::where('mokinys',$id)->first();
		if(count($data)){
				
			$busena = MokinioBusena::where('mokinys',$id)->first()->teorinio_egzamino_ivertinimas;
		}
		else{
			MokinioBusena::create(["mokinys" => $id]);
			$busena = null;
		}
		return view('ket_instruktorius.mokinys.edit', compact('mokiny','users','busena','grupe'));
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
		$this->validate($request, [
				'grupe' => 'required',
				'teorinio_egzamino_ivertinimas' => 'required|integer|between:0,100'
		]);
		$requestData = $request->all();
		$mokiny = Mokiny::findOrFail($id);
		$mokiny->update(["grupe" => $requestData['grupe']]);		
		$busena = MokinioBusena::where('mokinys',$id) ->first();
		$busena->update(["teorinio_egzamino_ivertinimas" => $requestData['teorinio_egzamino_ivertinimas']]);
	
		return redirect('ket_instruktorius/mokinys')->with('flash_message', 'Mokinys atnaujintas sėkmingai!');
	}
	
	
}
