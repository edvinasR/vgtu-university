<?php

namespace App\Http\Controllers\Mokinys;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Mokiny;
use Illuminate\Http\Request;
use Log;
use App\User;
use App\Instruktorius;
use App\KET_grupe;
use App\Helpers\Helpers;
use Illuminate\Support\Facades\Auth;
use App\Paskaita;
use App\GrupiuPaskaitos;
use Illuminate\Validation\Validator;

class PaskaitosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
	
	public function gautiMokinioPaskaitas($strat, $end){
		
		$rez = [];
		$user= Auth::user();
		$qStart=gmdate("Y-m-d H:i:s", $strat);
		$qEnd=gmdate("Y-m-d H:i:s", $end);
		$mokinys = Mokiny::where('naudotojas',$user->id)->first();
		
		
		if(count($mokinys)){
			$duomenys = collect(Paskaita::where(function($query) use($mokinys)
		    {
		        $query->whereNull('mokinys')->orWhere('mokinys',$mokinys -> id);
		    })->whereBetween('pradzia',[$qStart,$qEnd])->get());
			

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
			}	
		}
		return response()-> json($rez);
	}
    public function index(Request $request)
    {
    	$grupes = Helpers::getGrupesNames(null);
        return view('mokinys.paskaitos.index', compact('grupes'));
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
    		'pavadinimas' => 'required|max:30',
    			'aprasymas' => 'required|max:255',
    			'vieta' => 'required',
            	'pradzia' => 'required|date_format:H:i',
        		'pabaiga' => 'required|date_format:H:i|after:pradzia',
    			'data' => 'required',
        		'grupiu_pasirinkmimas' => 'required',
    	]);

	    if ($validator->fails()) {
	    	return response()->json(["success"=>false, "errors" => $validator->errors()]);
	    
	    }
	    
	    $requestData = $request->all();
		$requestData['pradzia'] =  $requestData['data'].' '.$requestData['pradzia'];
    	$requestData['pabaiga'] =  $requestData['data'].' '.$requestData['pabaiga'];
    	$requestData['praktine_paskaita'] = 0;
    	$requestData['instruktorius'] = Instruktorius::where('id',Auth::user()->id)->first()->id;
    	unset( $requestData['data']);
    	$grupes =  $requestData['grupiu_pasirinkmimas'];
    	unset( $requestData['grupiu_pasirinkmimas']);
    	
    	$error_data = [];
    	// before save code
    	$error_data = Paskaita::where('pradzia','<',$requestData['pabaiga'])->where('pabaiga','>',$requestData['pradzia'])->where('instruktorius',$requestData['instruktorius'])->get();
    	// if there is records it means that there are question in this period so this function returns existing question info
    	if (count($error_data) != 0) {		
    		return response()->json(["success"=>false, "errors" => [
    				"pradzia" => ["Negalima rinktis šito pradžios laiko, kadangi šiame intervale jau turite paskaitą!"],
    				"pabaiga" => ["Negalima rinktis šito pabaiagos laiko, kadangi šiame intervale jau turite paskaitą!"]		
    			]
    		]);
    	}
    	 
    	
    	$paskaita = Paskaita::create($requestData);
    	foreach($grupes as $grupe){
    		GrupiuPaskaitos::create([
    				"grupe" => $grupe,
    				"paskaita" =>  $paskaita -> id,
    		]);
    		
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
    		'pavadinimas' => 'required|max:30',
    			'aprasymas' => 'required|max:255',
    			'vieta' => 'required',
    			'pradzia' => 'required',
    			'pabaiga' => 'required',
    			'data' => 'required',
    			'grupiu_pasirinkmimas' => 'required',
    	]);

	    if ($validator->fails()) {
	    	return response()->json(["success"=>false, "errors" => $validator->errors()]);
	    
	    }
    	$requestData = $request->all();
    	$requestData['pradzia'] =  $requestData['data'].' '.$requestData['pradzia'];
    	$requestData['pabaiga'] =  $requestData['data'].' '.$requestData['pabaiga'];
    	$requestData['praktine_paskaita'] = 0;
    	$requestData['instruktorius'] = Instruktorius::where('id',Auth::user()->id)->first()->id;
    	unset( $requestData['data']);
    	$grupes =  $requestData['grupiu_pasirinkmimas'];
    	unset( $requestData['grupiu_pasirinkmimas']);
    	
    	$error_data = [];
    	// before save code
    	$error_data = Paskaita::where('pradzia','<',$requestData['pabaiga'])->where('pabaiga','>',$requestData['pradzia'])->where('id','!=',$id)->where('instruktorius',$requestData['instruktorius'])->get();
    	// if there is records it means that there are question in this period so this function returns existing question info
    	if (count($error_data) != 0) {
    		return response()->json(["success"=>false, "errors" => [
    				"pradzia" => ["Negalima rinktis šito pradžios laiko, kadangi šiame intervale jau turite kitą paskaitą!"],
    				"pabaiga" => ["Negalima rinktis šito pabaiagos laiko, kadangi šiame intervale jau turite kitą paskaitą!"]
    		]
    		]);
    	}
    	
    	$paskaita = Paskaita::findOrFail($id);
    	$paskaita->update($requestData);
    	GrupiuPaskaitos::where('paskaita',$id)->delete();
    	foreach($grupes as $grupe){
    		GrupiuPaskaitos::create([
    				"grupe" => $grupe,
    				"paskaita" =>  $paskaita -> id,
    		]);	
    	}
    	
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
    	GrupiuPaskaitos::where('paskaita',$id)->delete();
       Paskaita::where('id')->delete();
       return response()->json(["success"=>true]);	
    }
}
