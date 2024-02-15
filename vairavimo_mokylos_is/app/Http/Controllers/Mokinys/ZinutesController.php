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
use App\Zinute;
class ZinutesController extends Controller
{
	
public function index(Request $request)
    {
	
    	$mokinys= Mokiny::where('naudotojas', Auth::user()->id)->first()->id;
    	$tipas = $request->get('tipas');
    	$perPage = 25;
    	
    	
    	if(isset($tipas) && $tipas != -1){
    		$zinute = Zinute::where('mokinys',$mokinys)->where('siuntejas',$tipas)->orderBy('created_at','DESC')->paginate($perPage);
    	}
		else {
            $zinute = Zinute::where('mokinys',$mokinys)->where('siuntejas',"I")->orderBy('created_at','DESC')->paginate($perPage);
        }

        $mokiniai = Helpers::getMokianioInstruktorius($mokinys);
        return view('mokinys.zinute.index', compact('zinute','mokiniai'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
    	$mokinys = Mokiny::where('naudotojas', Auth::user()->id)->first()->id;
        $mokiniai = Helpers::getMokianioInstruktorius($mokinys);
        return view('mokinys.zinute.create', compact('mokiniai'));
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
			'instruktorius' => 'required'
		]);
        $requestData = $request->all();
        $mokinys = Mokiny::where('naudotojas', Auth::user()->id)->first()->id;
        $requestData["mokinys"] = $mokinys;
        $requestData["perskaitytas"] = 0;
        $requestData["siuntejas"] = "M";
        Zinute::create($requestData);

        return redirect('mokinys/zinute')->with('flash_message', 'Žinutė sukurta sėkmingai!');
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
		if($zinute -> siuntejas == "I"){
			
			$zinute->update([
					"perskaitytas" => 1
			]);
		}
    	$mokinys = Mokiny::where('naudotojas', Auth::user()->id)->first()->id;
        $mokiniai = Helpers::getMokianioInstruktorius($mokinys);
        return view('mokinys.zinute.show', compact('zinute', 'mokiniai'));
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

        return redirect('mokinys/zinute')->with('flash_message', 'Žinutė ištrint sėkmingai!');
    }

}
