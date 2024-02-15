<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use App\File;

class MockFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:mock_files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        for($i=0 ; $i<100; $i++){
            $data = [];
            if( $i%5 == 0){
                $data = [
                    "extension" =>  'a_folder',
                    "name" => "temp".$i."_folder",
                    "storage_service" => 0,
                    "storage_service_id" => 0,
                    "depth" => 0,
                    "size" =>  0,
                    "dowload_link" =>  "/downloadlink",
                    "parent_id" => null,
    
                ];
            }else{
                $ext = ["ppt", "doc", "txt", "jpg"][mt_rand(0, count(["ppt", "doc", "txt", "jpg"]) - 1)];
                   $data=
                   [ "extension" =>  $ext,
                    "name" => "temp".$i.".". $ext,
                    "storage_service" => 0,
                    "storage_service_id" => 0,
                    "depth" => 0,
                    "size" =>  rand(0,800000),
                    "dowload_link" =>  "/downloadlink",
                    "parent_id" => null,
    
                ];
            }


            $file = $this -> saveFile($data );
            for($z = 1 ; $z<=40 ; $z++ ){
                if( $file -> extension == 'a_folder'){
                    $ext = ["ppt", "doc", "txt", "jpg"][mt_rand(0, count(["ppt", "doc", "txt", "jpg"]) - 1)];
                    $data = [
                        "extension" =>  $ext,
                        "name" => "temp".$i.".". $ext,
                        "storage_service" => 0,
                        "storage_service_id" => 0,
                        "depth" => 1,
                        "size" =>  rand(0,800000),
                        "dowload_link" =>  "/downloadlink",
                        "parent_id" => $file -> id,
        
                    ];
                    $newFile = $this -> saveFile($data  );
                }else continue;

            }
        }
           return true; 
    }

    private function saveFile($data){
        
                try{
                    $file = new File($data);
                    $file -> save();
                }catch(\Exception $ex){
                        Log::error($ex);
                        throw $ex;
                }
          
                return $file;
            }
}
