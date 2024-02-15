<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\CombinedCloudService;

class RenameDirectory implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

      /**
     * Create a new job instance.
     *
     * @return void
     */
    private $userId;
    private $file;
    private $name;

    public function __construct($userId, $file, $name)
    {
        $this -> userId = $userId;
        $this -> file = $file;
        $this -> name = $name;
        
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
        $cloudService -> renameDirectoriesInAllServices($this -> file -> id, $this -> name);
      
    }
}
