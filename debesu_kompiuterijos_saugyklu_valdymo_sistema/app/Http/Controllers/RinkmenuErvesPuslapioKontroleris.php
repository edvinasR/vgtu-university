<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\File;
use Log;
use Illuminate\Http\Request;
use Auth;
use App\Services\CombinedCloudService;
use App\CloudService;
use Redirect;
use App\Settings;


class RinkmenuErvesPuslapioKontroleris extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function get(Request $request, $parent, $storageService = null){

        $clombinedCloudService = new CombinedCloudService(Auth::user()->id);
        $navigationTree = [];
        $headerText = "Bendra rinkmenų erdvė";
        $urlAppend = "";
        $viewFormat = "icon";

        $userSettings = Settings::where('user_id',Auth::user()->id)->first();
        if( $userSettings !== null ){
            $viewFormat =   $userSettings -> view_format;
        }
        $userCloudServices = collect(CloudService::select('id','name','type')->where('user_id',Auth::user()->id)->where('deleted',0)->where('activated',1)->get());
        $userCloudServices = $userCloudServices -> mapWithKeys(function ($item) {
            return [$item['id'] => ["type" => $item['type'], "name" => $item['name']]];
        });
        //Jeigu naudotojas neturi susikures bent 1 altyvios saugyklos, veiksmai neatliekami
        if(count($userCloudServices ) == 0){
            return Redirect::to('/nustatymai')->with('disk_error','Negalite naudotis rinkmenų valdymo erdve, kol neturite susikurę bent vienos saugyklos');
        }
        // Gauname iš kokio lygio yra atsiųsta užklausa, T.y. užsklausos kontekstą
        $parentDirectory = File::where('id',$parent)->get();
        $parentDirectory = count($parentDirectory) > 0 ? $parentDirectory[0]-> parent_id : null;
        // Pagrindinė užkalusa
        if($parent == 'root'){
            $files = File::where(function($query){
                $query-> whereNull('parent_id');
            });
        }   
        else{
            $files = File::where(function($query) use ($parent){
                $query-> where('parent_id', $parent);
            });
        }
        // Gaunami nuorodos parametrai
        $search = '';
        $filter = '';
        $queryArrays  = explode ("&", parse_url($request -> fullUrl(), PHP_URL_QUERY));
        // Pereinama per visus nuorodos parametrus ir įvykdomi atitinkami veiksmai
        foreach($queryArrays as $singleQuery){
            if($singleQuery == ''){
                $files = $files -> orderBy("extension");
            }
            $parts  = explode ("=", $singleQuery);
            if(isset($parts[0]) && isset($parts[1])){
                if($parts[0] =="search"){
                    $search = $parts[1];
                    $files = $files-> where(function($query) use ($search){
                        $query-> where('name', 'LIKE', "%$search%")
                        ->orWhere('extension', 'LIKE', "%$search%")
                        ->orWhere('created_at', 'LIKE', "%$search%")
                        ->orWhere('updated_at', 'LIKE', "%$search%")
                        ->orWhere('dowload_link', 'LIKE', "%$search%");
                    });
                }else{
                    $filter = $singleQuery;
                    $files = $files->orderBy( $parts[0], $parts[1]);
                }
            }
        }
      //Jeigu yra nustatyta saugykla filtruoojame tik tai tos saugyklos rinkmenas
        if($storageService != null){
            
            $cloudService = CloudService::findOrFail($storageService);
            $headerText = $cloudService->name;
            $urlAppend= "/".$storageService;
            $files=  $files-> where(function($query) use ($storageService){
                $query->where('storage_service',$storageService)
                ->orWhere('storage_service',null);
               
            });

     
        }
          // Sukonstravus galutinę užklausą į failus nusiunčiamas kreipinys į DB
        $files = $files->where('user_id',Auth::user()->id)->get();
        // Konstruojame navigacijos juostą
        try{
          // Konstruojame navigacijos juostos duomenis
          $nav = $clombinedCloudService-> getFileHierarchy($parent);
          $navigationTree  = File::select('id','name')->whereIn('id',  $nav)->orderBy('id')->get();
        }catch(\Exception $ex){
            $navigationTree = [];
        }
  
        session(['view_mode' => $viewFormat]);
        // Atgal į View (rodinį) nusiunčiame atfiltruotą failų sąrąša, taip pat filturs ir paiešks raktinius žodžius
       return view('files',["files"=> $files , "filter" =>  $filter == '' ? "extension=ASC" : $filter, "search" => $search, "parentDirectory" => $parentDirectory == null ? 'root' : $parentDirectory,"directory" =>$parent, "navigation" => $navigationTree, "headerText" => $headerText, "rootUrl" => $urlAppend, "services" =>  $userCloudServices ]);
    }

    public function getSingleFile($id){
        $file =File::where('id',$id)->first();
        $size = $file -> size;
        if($file -> extension == "a_folder"){
            $size = File::where('parent_id',$file->id)->sum('size');  
        }
        return response()->json(["file_info" =>$file, "size" => $this -> formatBytes($size)]);
    }

    public function getFolderHierarchy($file){
        $root = File::whereNull("parent_id")->first();
        $parentOfFile = File::where('id', $file)->first()->parent_id;
        $folders = File::select('id', 'name', 'parent_id')->where('extension','a_folder')->whereNotNull("parent_id")->get();
        $folders = $folders -> map(function($item) use($parentOfFile, $root ){
            $data = [
                "id" => $item -> id,
                "parent" => $item -> parent_id,
                "text" => $item -> name,
            ];          
            if($item -> id == $parentOfFile){
                $data["state"] = [ "opened" => true, "selected" => true];
            }
            return $data ;
        }); 
        $folders = $folders -> push([ "id" => $root -> id, "name" => 'Pagrindinis',"parent" =>'#']);
        return response()->json(["tree" => $folders]);
    }

    public function changeViewFormat(){
            $userSettings = Settings::where('user_id',Auth::user()->id)->first();
            if( $userSettings !== null){

                $viewMode =  $userSettings -> view_format;

                if($viewMode == 'icon'){
                    Settings::where('user_id',Auth::user()->id)->update([
                        "view_format" => 'list'
                    ]);
                    return Redirect::back()->with('view_mode',"list");
                }else{
                    Settings::where('user_id',Auth::user()->id)->update([
                        "view_format" => 'icon'
                    ]);
                    return Redirect::back()->with('view_mode',"icon");
                }
            }

            return Redirect::back()->with('view_mode',"icon");
            
    }
}
