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
						
						return [
								"id" => 	$item -> id,
								"pradzia" => $item -> pradzia,
								"pabaiga" => $item -> pabaiga,
								"pavadinimas" => $item -> pavadinimas,
								"aprasymas" => $item -> aprasymas,
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
    public function index(Request $request)
    {
    	$u = Auth::user();
    	$instruktorius = Instruktorius::where('naudotojas',$u->id)->first();
    	$mokiniai = Helpers::getInstruktoriausMokianiaiNamesArray($instruktorius->id);
        return view('instruktorius.paskaitos.index', compact('mokiniai'));
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
        		'mokinys' => 'required',
    	]);

	    if ($validator->fails()) {
	    	return response()->json(["success"=>false, "errors" => $validator->errors()]);
	    
	    }
	    
	    $requestData = $request->all();
		$requestData['pradzia'] =  $requestData['data'].' '.$requestData['pradzia'];
    	$requestData['pabaiga'] =  $requestData['data'].' '.$requestData['pabaiga'];
    	$requestData['praktine_paskaita'] = 1;
    	$requestData['instruktorius'] = Instruktorius::where('id',Auth::user()->id)->first()->id;
    	unset( $requestData['data']);

    	
    	$error_data = [];
    	// before save code
    	$error_data = Paskaita::where('pradzia','<',$requestData['pabaiga'])->where('pabaiga','>',$requestData['pradzia'])->where('instruktorius',$requestData['instruktorius'])->get();
    	// if there is records it means that there are question in this period so this function returns existing question info
    	if (count($error_data) != 0) {		
    		return response()->json(["success"=>false, "errors" => [
    				"pradzia" => ["Negalima rinktis šio laiko, kadangi nurodytame intervale jau turite paskaitą!"],
    				"pabaiga" => ["Negalima rinktis šio laiko, kadangi nurodytame intervale jau turite paskaitą!"]		
    			]
    		]);
    	}
    	Paskaita::create($requestData);
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
    			'mokinys' => 'required',
    	]);

	    if ($validator->fails()) {
	    	return response()->json(["success"=>false, "errors" => $validator->errors()]);
	    
	    }
    	$requestData = $request->all();
    	$requestData['pradzia'] =  $requestData['data'].' '.$requestData['pradzia'];
    	$requestData['pabaiga'] =  $requestData['data'].' '.$requestData['pabaiga'];
    	$requestData['praktine_paskaita'] = 1;
    	$requestData['instruktorius'] = Instruktorius::where('id',Auth::user()->id)->first()->id;
    	unset( $requestData['data']);
    	
    	
    	$error_data = [];
    	// before save code
    	$error_data = Paskaita::where('pradzia','<',$requestData['pabaiga'])->where('pabaiga','>',$requestData['pradzia'])->where('id','!=',$id)->where('instruktorius',$requestData['instruktorius'])->get();
    	// if there is records it means that there are question in this period so this function returns existing question info
    	if (count($error_data) != 0) {
    		return response()->json(["success"=>false, "errors" => [
					"pradzia" => ["Negalima rinktis šio laiko, kadangi nurodytame intervale jau turite paskaitą!"],
    				"pabaiga" => ["Negalima rinktis šio laiko, kadangi nurodytame intervale jau turite paskaitą!"]		
    		]
    		]);
    	}
    	
    	$paskaita = Paskaita::findOrFail($id);
    	$paskaita->update($requestData);
    	
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
    	
       Paskaita::where('id',$id)->delete();
       return response()->json(["success"=>true]);	
    }
}
