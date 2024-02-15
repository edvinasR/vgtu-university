<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Download;
use File;

class DeleteUserDownloads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear_downloads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ištrina naudotojo atsisiuntimus iš servverio siekiant atlaisvinti vietą serveryje';

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
        $date = date('Y-m-d H:i:s');
        $newdate = strtotime ( '-60 minute' , strtotime ( $date ) ) ;
        $newdate = date ( 'Y-m-d H:i:s' , $newdate );
        $downloads = Download::select('id','updated_at','path')->where('updated_at','<',$newdate)->get();
        foreach( $downloads as $download){
            $dirToDelete = str_replace("/final/".$download -> name,"",$download -> path);
            File::deleteDirectory(storage_path(). $dirToDelete );
            $download -> delete();
        }
    }
}
