<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function log($message){
    	Log::info($message);
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$userRoleName = Auth::user()->userRoleName();
    	if($userRoleName=='Administratorius'){
    		
    		return redirect('/administratrius/mokinys');
    	}
    	if($userRoleName=='KET dėstytojas'){
    	
    		return redirect('/ket_instruktorius/paskaitos');
    	}
    	if($userRoleName=='Praktinio vairavimo instruktorius'){
    		 
    		return redirect('/instruktorius/paskaitos');
    	}
    	if($userRoleName=='Mokinys'){
    		 
    		return redirect('/mokinys/paskaitos'); 
    		//return "Mokinio rolės naudotojo sąsaja ko kas nesukurta, pabandykite vėliau...";
    		//return redirect('/instruktorius/paskaitos');
    	}
    	
    	
    	return "Jūsų rolės naudotojo sąsaja ko kas nesukurta, pabandykite vėliau...";
        //return redirect('/administratrius/mokinys');
    }
    
}
