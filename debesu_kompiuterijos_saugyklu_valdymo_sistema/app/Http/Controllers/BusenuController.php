<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Statistics;
use App\Helpers\HttpUtility;
use Auth;
use App\CloudService;
class BusenuController extends Controller
{
    public function index(){
        $storageServices = CloudService::where('user_id', Auth::user()->id)->where('deleted',0)->where('activated',1)->get();
        return view('busenos',["services"=>$storageServices]);
    }

    public function getUserStorageServiceExtensionStatistics($service = null){
        $user =Auth::user();
        $statisticsDydis = Statistics::select('key','value')->where('key', 'LIKE', '%tipo%')->where('key', 'LIKE', '%dydis%')->where('disk_id',$service)->where('user_id', $user -> id)->get();
        $statisticsKiekis= Statistics::select('key','value')->where('key', 'LIKE', '%tipo%')->where('key', 'LIKE', '%kiekis%')->where('disk_id',$service)->where('user_id', $user -> id)->get();
        
        return HttpUtility::buildSuccessfullResponse('Success',[
            "kiekis" => $this -> formatStatistics($statisticsKiekis),
            "dydis" =>  $this -> formatStatistics($statisticsDydis)
            ]);
    }
  
    public function getFreeStorageData(){
        $user =Auth::user();
        $laisvosAminites = Statistics::select('key','value','disk_id')->where('key', 'LIKE', '%aisvos%')->where('user_id', $user -> id)->where('disk_id','!=',null)->get();
        return HttpUtility::buildSuccessfullResponse('Success',$this -> formatStorageStatistics( $laisvosAminites));
    }

    public function getUsedStorageData(){
        $user =Auth::user();
        $užtimtaAminites = Statistics::select('key','value','disk_id')->where('key', 'LIKE', '%žimta%')->where('user_id', $user -> id)->where('disk_id','!=',null)->get();
        return HttpUtility::buildSuccessfullResponse('Success',$this -> formatStorageStatistics( $užtimtaAminites ));
    }

    public function getUserStorageServiceGeneralStatistics($service = null){
        $user =Auth::user();
        $statistics = Statistics::select('key','value')->where('key', 'NOT LIKE', '%tipo%')->where('disk_id',$service)->where('user_id', $user -> id)->get();
      
        return HttpUtility::buildSuccessfullResponse('Success', $statistics );
    }

    private function formatStatistics($data){
        return $data->map(function ($item) {
            return [
                "label" => $item -> key,
                "value" =>  $item  -> value,
                "color" => $this -> random_color(),
        
            ];
        });
    }

    private function formatStorageStatistics($data){
        return $data->map(function ($item) {
            $service = CloudService::select('name')->where('id',$item->disk_id)->first();
            return [
                "label" => $service  -> name,
                "value" =>  $item  -> value,
                "color" => $this -> random_color(),
        
            ];
        });
    }
     private function random_color() {
        $str = '#';
        for($i = 0 ; $i < 3 ; $i++) {
            $str.= dechex(rand(36,255));
        }
        return $str;
    }
}
