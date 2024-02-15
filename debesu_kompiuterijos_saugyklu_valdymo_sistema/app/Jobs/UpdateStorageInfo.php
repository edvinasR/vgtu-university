<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Helpers\DisksConfigurationUtility;
use App\CloudService;
use Artisan;

class UpdateStorageInfo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $userId;
    public function __construct($userId)
    {
        $this -> userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $config = new DisksConfigurationUtility();
        $config-> registerDisksInConfig($this -> userId );
        $storageServices = CloudService::where('user_id',$this -> userId )->where('deleted',0)->where('activated',1)->get();
        foreach($storageServices as $singleFileService){
            $storageServiceInstance = $singleFileService -> getServiceInstance();
            if($storageServiceInstance != null){
                $storageServiceInstance  -> freeSpace();
            }
        }

        Artisan::call('calculate_statistics');
    }
}
