<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use App\CloudService;
use Auth;
use App\Helpers\Formatters;
use App\Helpers\DisksConfigurationUtility;

class CloudServicesLandingPageController extends Controller
{
    public function get(){
        (new DisksConfigurationUtility()) -> registerDisksInConfig(Auth::user()->id);
        $services = CloudService::where('user_id',Auth::user()->id)->where('deleted',0)->where('activated',1)->get();
        $services = $services -> map(function($item){
            $item -> free_storage = Formatters::formatBytes($item -> free_storage);
            return $item;
        });
        if(count($services) == 0){
            return Redirect::to('/nustatymai')->with('disk_error','Negalite naudotis rinkmenų valdymo erdve, kol neturite susikurę bent vienos saugyklos');
        }
        return view('landing_page',["saugyklos"=> $services]);
    }
        
}
