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
use App\Zinute;
class ZinutesController extends Controller
{
	
public function index(Request $request)
    {
	
    	$instruktorius = Instruktorius::where('naudotojas', Auth::user()->id)->first()->id;
    	$tipas = $request->get('tipas');
    	$perPage = 25;
    	
    	
    	if(isset($tipas) && $tipas != -1){
    		$zinute = Zinute::where('instruktorius',$instruktorius)->where('siuntejas',$tipas)->orderBy('created_at','DESC')->paginate($perPage);
    	}
		else {
            $zinute = Zinute::where('instruktorius',$instruktorius)->where('siuntejas',"M")->orderBy('created_at','DESC')->paginate($perPage);
        }

        $mokiniai = Helpers::getInstruktoriausMokianiaiNamesArray($instruktorius);
        return view('instruktorius.zinute.index', compact('zinute','mokiniai'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
    	$instruktorius = Instruktorius::where('naudotojas', Auth::user()->id)->first()->id;
    	$mokiniai = Helpers::getInstruktoriausMokianiaiNamesArray($instruktorius);
        return view('instruktorius.zinute.create', compact('mokiniai'));
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
        $this->validate($request, [
			'zinute' => 'required|max:255',
			'tema' => 'required|max:255',
			'mokinys' => 'required'
		]);
        $requestData = $request->all();
        $instruktorius = Instruktorius::where('naudotojas', Auth::user()->id)->first()->id;        
        $requestData["instruktorius"] = $instruktorius;
        $requestData["perskaitytas"] = 0;
        $requestData["siuntejas"] = "I";
        Zinute::create($requestData);

        return redirect('instruktorius/zinute')->with('flash_message', 'Žinutė sukurta sėkmingai!');
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
        $zinute = Zinute::findOrFail($id);
		if($zinute -> siuntejas == "M"){
			
			$zinute->update([
					"perskaitytas" => 1
			]);
		}
     	$instruktorius = Instruktorius::where('naudotojas', Auth::user()->id)->first()->id;
    	$mokiniai = Helpers::getInstruktoriausMokianiaiNamesArray($instruktorius);
        return view('instruktorius.zinute.show', compact('zinute', 'mokiniai'));
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
        Zinute::destroy($id);

        return redirect('instruktorius/zinute')->with('flash_message', 'Žinutė ištrinta sėkmingai!');
    }

}
