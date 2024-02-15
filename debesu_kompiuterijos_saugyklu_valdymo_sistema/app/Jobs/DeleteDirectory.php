<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\CombinedCloudService;
use App\File;

class DeleteDirectory implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $userId;
    private $file;

    public function __construct($userId, $file)
    {
        $this -> userId = $userId;
        $this -> file = $file;
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $cloudService = new CombinedCloudService( $this -> userId);
        $cloudService -> registerDisks();
        $cloudService -> deleteDirectory($this -> file);
        $this -> deleteFile($this -> file);
    }

    private function deleteFile($fileId){
        $file = File::where('id',$fileId)->first();
        $isDirectory = $file -> extension == "a_folder"  ? true : false;
        if($isDirectory){
            $childFiles = File::where('parent_id',$fileId ) -> get();
            foreach($childFiles as $singleChild){
                    $this -> deleteFile($singleChild -> id);
            }
        }
        $file -> delete();
        return true;
    }
}
