<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Download;
use Storage;
class AtisiuntimaiController extends Controller
{
    public function index(){

        $userId = Auth::user()->id;
        $downloads = Download::where('user_id',$userId)->orderBy('updated_at','DESC')->get();
        return view('downloads',['atsisiuntimai'=> $downloads]);
    }

    public function download($id){
        $atsisuntims = Download::findOrFail($id);
        $rawDataStream =  Storage::disk('temp_dir')->getDriver()->readStream($atsisuntims->path);
        return response()->stream(function () use ($rawDataStream) {
            fpassthru($rawDataStream);
        }, 200, [
            'Content-Type' =>   $atsisuntims -> mimetype ,
            'Content-Disposition' => 'attachment; filename="'. $atsisuntims -> name.'"',
            'Content-Transfer-Encoding' => 'Binary',
        ]);
    }   
}
