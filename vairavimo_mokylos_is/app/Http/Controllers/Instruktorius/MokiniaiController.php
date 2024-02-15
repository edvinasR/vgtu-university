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
use App\MokinioBusena;
use Illuminate\Support\Facades\Auth;
class MokiniaiController extends Controller
{

	public function index(Request $request)
	{
		
		$user = Auth::user();
		$inst = Instruktorius::where('naudotojas',$user->id)->first();
		$keyword = $request->get('search');
		$perPage = 25;
	
		if (!empty($keyword)) {
			$mokinys = Mokiny::where('vairavimo_instruktorius',$inst->id)->where('kategorija', 'LIKE', "%$keyword%")
			->orWhere('grupe', 'LIKE', "%$keyword%")
			->orWhere('vairavimo_instruktorius', 'LIKE', "%$keyword%")
			->orWhere('naudotojas', 'LIKE', "%$keyword%")
			->paginate($perPage);
		} else {
			$mokinys = Mokiny::where('vairavimo_instruktorius',$inst->id)->paginate($perPage);
		}
	
		$users =  Helpers::getNaudotojaiNamesArray('Mokinys');
		return view('instruktorius.mokinys.index', compact('mokinys','users'));
	}
	
	public function show($id)
	{
		$mokiny = Mokiny::findOrFail($id);
		$users =  Helpers::getNaudotojaiNamesArray('Mokinys');

		 
	
		return view('instruktorius.mokinys.show', compact('mokiny','users'));
	}
	
	public function edit($id)
	{
		 
		$mokiny = Mokiny::findOrFail($id);	
		$users =  Helpers::getNaudotojaiNamesArray('Mokinys');
		$data = MokinioBusena::where('mokinys',$id)->first();
		if(count($data)){
			
			$busena = MokinioBusena::where('mokinys',$id)->first()->praktinio_egzamino_ivertinimas;
		}
		else{
			MokinioBusena::create(["mokinys" => $id]);
			$busena = null;
		}
		
	
		return view('instruktorius.mokinys.edit', compact('mokiny','users','busena'));
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
				'praktinio_egzamino_ivertinimas' => 'required|integer|between:0,100'
		]);
		$requestData = $request->all();		
		$busena = MokinioBusena::where('mokinys',$id) ->first();
		$busena->update(["praktinio_egzamino_ivertinimas" => $requestData['praktinio_egzamino_ivertinimas']]);
	
		return redirect('instruktorius/mokinys')->with('flash_message', 'Mokinys atnaujintas sėkmingai!');
	}
	
	
}
