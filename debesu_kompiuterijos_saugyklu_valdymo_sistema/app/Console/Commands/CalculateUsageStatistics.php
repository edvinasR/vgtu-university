<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Statistics;
use App\File;
use App\User;
use App\CloudService;
use Log;

class CalculateUsageStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate_statistics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Apskaičiuoja kiek kokių rinkmnų buvo išsaugota diske, keik atmntės ir pan.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::get();
        foreach($users as $user){
            $storageServices = CloudService::where('user_id',$user -> id)->where('deleted',0)->where('activated',1)->get();
            foreach($storageServices as $service){
                $this -> insertStatisticsRecord('Laisvos atminties',$service -> free_storage,$user -> id, $service->id);

                $filesCount = File::where('storage_service',$service -> id) -> count();
                $filesSizeSum = File::where('storage_service',$service -> id) -> sum('size');
              
                $distinctExtensions = collect(File::select('extension')->where('storage_service',$service -> id)->distinct()->get())->pluck('extension');
                $this -> insertStatisticsRecord('Išsaugota rinkmenų',$filesCount,$user -> id, $service->id);
                $this -> insertStatisticsRecord('Užimta atminties',$filesSizeSum,$user -> id, $service->id);
                foreach($distinctExtensions as $ext){            
                    $this -> insertExtensionInfo( $ext, $service,$user);                  
                }
            }
            $aplankalai = File::where('extension','a_folder')->where('user_id',$user -> id) -> count();
            $rinkmenos = File::where('user_id',$user -> id) -> count();
            $bendraiUzimtaAtminties = File::where('user_id',$user -> id) -> sum('size');
            $likoAtminties = CloudService::where('user_id',$user -> id)->where('deleted',0)->where('activated',1)->sum('free_storage');
            $distinctExtensions = collect(File::select('extension')->where('user_id',$user -> id)->distinct()->get())->pluck('extension');
            // Bendra visos saugyklos statistika
            $this -> insertStatisticsRecord('Aplankalų kiekis',$aplankalai,$user -> id,null);
            $this -> insertStatisticsRecord('Bendrai rinkmenų', $rinkmenos,$user -> id, null);
            $this -> insertStatisticsRecord('Bendrai užimta atminties', $bendraiUzimtaAtminties,$user -> id, null);
            $this -> insertStatisticsRecord('Liko laisvos aminties',  $likoAtminties,$user -> id, null);
            foreach($distinctExtensions as $ext){           
               $this -> insertExtensionInfo( $ext, null,$user);              
            }
        }
    }


    private function insertExtensionInfo($ext, $service, $user){
        if( $service == null){
            $dydis = File::where('user_id',$user -> id)->where('extension',$ext) -> sum('size');
            $keikis = File::where('user_id',$user -> id)->where('extension',$ext) -> count();
            if($ext == '' || $ext == 'a_folder'){
                return;
            } 
            $this -> insertStatisticsRecord('Bendrai '.$ext.' tipo rinkmenų kiekis',$keikis,$user -> id,null);
            $this -> insertStatisticsRecord('Bendrai '.$ext.' tipo rinkmenų užimamas dydis',$dydis,$user -> id, null);
     
        }else{
            $dydis = File::where('storage_service',$service -> id)->where('extension',$ext) -> sum('size');
            $keikis = File::where('storage_service',$service -> id)->where('extension',$ext) -> count();
            if($ext == '' || $ext == 'a_folder'){
                return;
            } 
            $this -> insertStatisticsRecord($ext.' tipo rinkmenų kiekis',$keikis,$user -> id, $service->id);
            $this -> insertStatisticsRecord($ext.' tipo rinkmenų užimamas dydis',$dydis,$user -> id, $service->id);

        }
    }

    private function insertStatisticsRecord($key, $value, $user, $service){

        try{
            Statistics::where('user_id',$user)->where('key',$key)->where('disk_id', $service)->delete();
            Statistics::create([
                'user_id'=>  $user,
                'key' => $key,
                'disk_id' =>  $service,
                'value' => $value,
            ]);

        }catch(\Exception $ex){
            Log::info($ex);
            return;
        }
    }
}
