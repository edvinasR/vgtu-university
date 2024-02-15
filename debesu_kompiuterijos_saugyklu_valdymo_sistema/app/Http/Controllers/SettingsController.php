<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CloudService;
use App\File;
use App\Folder;
use Config;
use Redirect;
use Storage;
use App\Helpers\Oauth;
use App\Helpers\HttpUtility;
use Illuminate\Validation\Rule;
use Log;
use Auth;
use App\Interfaces\FileService;
use App\Jobs\CreateFolderStructureOnNewStorageService;
use App\Helpers\DisksConfigurationUtility;
use App\Jobs\UpdateStorageInfo;

class SettingsController extends Controller
{

    public function index(){
       (new DisksConfigurationUtility()) -> registerDisksInConfig(Auth::user()->id);
        $storageServices = CloudService::where('user_id', Auth::user()->id)->where('deleted',0)->get();
        return view('settings',['storageServices'=> $storageServices ]);
    }


    public function updateStorageInfo(){
        $job = (new UpdateStorageInfo(Auth::user()->id));
        $this->dispatch($job);
        return HttpUtility::buildSuccessfullResponse('Success');
    }
    
    public function test($id){
        $job = (new CreateFolderStructureOnNewStorageService($id));
        $this->dispatch($job);

    }
    public function getAbout($id){
        $itemForReturn =  [           
            "freeSpace" => '0 GB',
            "usedSpace" => '0 GB',
            "belongsTo" => 'Nežinoma',
        ];
        $service = CloudService::findOrFail($id);
        $serviceInstance =  $service -> getServiceInstance();
        if($serviceInstance != null){
        
            $itemForReturn = [
                "freeSpace" => $serviceInstance -> freeSpace(),
                "usedSpace" => $serviceInstance -> usedSpace(),
                "belongsTo" => $serviceInstance -> getBelongsTo(),
            ];
           
        }
        return HttpUtility::buildSuccessfullResponse('Success',$itemForReturn);
    }
    public function deleteStorageService($id){

        $serviceToDelete = CloudService::findOrFail($id);
        if(  $serviceToDelete -> activated &&   $serviceToDelete -> type == 'google'){
            $oauthHelper = new Oauth($id);
            $oauthHelper -> getAutorizedHTTPInstance();
            $client = $oauthHelper -> getClient() ->revokeToken();
        }
        $serviceToDelete->delete();
        return HttpUtility::buildSuccessfullResponse('Success');
    }

    public function renameStorageService(Request $request, $id){
        CloudService::findOrFail($id)->update(["name" => $request->get('name')]);
        return HttpUtility::buildSuccessfullResponse('Success');
    }

    public function createStorageService(Request $request){
        $validatedData = $request->validate([
            'type'=> [
                'required',
                 Rule::in(['google', 'dropbox','onedrive']),
            ],
            'pavadinimas' => 'required'
        ]);
        try{
  
            $cloudService = new  CloudService();
            $cloudService ->  type =  $request -> get('type');
            $cloudService ->  name =  $request -> get('pavadinimas');
            $cloudService -> logo = $this ->  getLogo($request -> get('type'));
            $cloudService -> activated = false;
            $cloudService ->  root_folder_id = null;
            $cloudService ->  user_id = Auth::user()->id;
            $cloudService -> save();
            $url =  (new Oauth($cloudService -> id)) -> createOauthUrl();
            return HttpUtility::buildSuccessfullResponse('Success',['oauth' => $url]);
        }catch(\Exception $ex){
            Log::error($ex);
            return HttpUtility::buildErroreusResponse('Errors',['err' => 'Neišėjo sukurti tėvinio katalago, patikrinkite konfiguraciją.']);
        }
    }

    public function activateStorageService($id){
        $url =  (new Oauth($id)) -> createOauthUrl();
        return Redirect::to($url);
    }
   //---------------------------------------------------------------------------------
    private function getLogo($type){
        switch($type){
                case 'google':
                 return 'img/google_drive.png';
                case 'onedrive':
                 return 'img/onedrive.png';
                case 'dropbox':
                 return 'img/dropbox.png';
        }
    }
}
