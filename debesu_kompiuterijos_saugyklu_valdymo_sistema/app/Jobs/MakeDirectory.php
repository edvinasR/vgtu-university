<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\CombinedCloudService;

class MakeDirectory implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $userId;
    private $name;
    private $parentId;
    private $file;

    public function __construct($userId, $file, $parentId, $name)
    {
        $this -> userId = $userId;
        $this -> name = $name;
        $this -> parentId = $parentId;
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
        $cloudService -> createDirectory( $this -> file, $this -> parentId, $this -> name);


    }
}
