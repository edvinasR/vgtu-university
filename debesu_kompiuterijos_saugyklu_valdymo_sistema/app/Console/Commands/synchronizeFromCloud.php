<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\CloudService;
use App\User;
use App\Helpers\DisksConfigurationUtility;
use Log;
use App\Folder;
use App\File;
class synchronizeFromCloud extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'synchronize_from_cloud';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Komanda naudojama norint sinchronizuoti visus failus naujai sukurtus atitinkamoje naudotojo saugykloje';

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
        // Registruojame kiekvieno naudotojo saugyklą naudojant Laravelio Failų sistemos adapterį atitinkamam saugyklos tipui
        $users = User::select('id')->get();
        foreach( $users as $user)
        {
            (new DisksConfigurationUtility())-> registerDisksInConfig($user -> id);
            $cloudServices = CloudService::where('user_id',$user -> id)->where('deleted',0)->where('activated',1)->get();
            foreach($cloudServices as $service)
            {
                $this -> handleCloudFileServiceContents( $service, $user -> id);
            }
        }
    }

    private function handleCloudFileServiceContents($service, $userId){
        $root =  $service -> root_folder_id;
        $instace =  $service -> getServiceInstance();
        $contents = $instace -> getContentsOfDirectory($root, true); 
        //Atskiriame aplankalaus nuo paprastų failų kadangi šios rinkmenos yra saugomos skirtingai
        $folders = $contents -> filter(function($item){
             return $item['type'] == 'dir';
        })->sortBy('dirname');
        $files = $contents -> filter(function($item){
            return $item['type'] == 'file';
        })->sortBy('dirname');
        // Iš pradžių tikriname ar neatirado kokių nors naujų aplanakalų kadanginaudojome funkcija sortBy aplankalai bus surušiuoti nuo root aplankalo
        foreach($folders as $folder){
            // Kadangi einame nuo root galime tikrinti ar yra toks tėvinis katalogas nes bet kokiu atvėju jis buvo sukurtas prieš tai buvusiuose iteracijose
            $isFolderExists =  Folder::where('id_on_cloud', $folder['basename'])->where('cloud_service',$service -> id)->count();
            if($isFolderExists){
                continue;
            }else{
                Log::info("Sukuriamas aplankalas iš duomenų");
                Log::info($folder);
               $this -> createFolder($folder,$service, $userId);
            }
        }
        // kuomet visi aplankalai jau sukurti galima pradėti kurti ir trūkstamas rinkmenas
        foreach($files as $file){
            // Kadangi einame nuo root galime tikrinti ar yra toks tėvinis katalogas nes bet kokiu atvėju jis buvo sukurtas prieš tai buvusiuose iteracijose
            $isFolderExists =  File::where('storage_service_id', $file['basename'])->count();
            if($isFolderExists || $file['extension'] == "part" ||  $file['extension'] == ""){
                continue;
            }else{
                Log::info("Sukuriama rinkmena iš duomenų");
                Log::info($file);
                $parent =   strrpos($file['dirname'], '/') === false ? $file['dirname'] : substr($file['dirname'], strrpos($file['dirname'], '/') + 1);
                $parentId = Folder::select('file_id')->where('id_on_cloud', $parent)->first();
                File::create([
                    "parent_id" => $parentId -> file_id,
                    "extension" =>$file['extension'],
                    "name" => $file['filename'].".".$file['extension'],
                    "user_id" => $userId,
                    "storage_service_id" => $file['basename'],
                    "storage_service" => $service -> id,
                    "size" => $file['size'],
                ]);

            }
        }
    }

    private function createFolder($folder,$service,$userId){
        $root =  $service -> root_folder_id;
        // Kadangi mes einame gilyn nuo root tai tėvas visada egzistuos, nes net jei jo nebuvo prieš sinchronizaciją jis buvo sukurtas prieš tai buvusioje iteracijoje
        $folderParent = strrpos($folder['dirname'], '/') === false ? $folder['dirname'] : substr($folder['dirname'], strrpos($folder['dirname'], '/') + 1);
        $FolderInfo = Folder::select('file_id')->where('id_on_cloud', $folderParent)->where('cloud_service',$service -> id)->first();
        //Ištraukiame visas rinkmenas, kurios yra šios rinkmenos vaikai jei tokiu yra
        $newFile = File::where('parent_id',$FolderInfo -> file_id)->where('user_id',$userId)->where("extension","a_folder")->where("name",$folder['filename'])->first();
        // jeigu tokio aplankalo nera jissukuriamas
        if( $newFile  === null){
            //Sukuriame aplankalą Files lentelėje
            $newFile = File::create([
                "parent_id" => $FolderInfo -> file_id,
                "extension" => "a_folder",
                "name" =>  $folder['filename'],
                "user_id" => $userId,
            ]);
        }

        // Aplankalas sukuriamas visose saugyklose išskyrus toje kur buvo surastas noritn užtikrinti sklandžią sinchronizaciją
        $saugyklos = CloudService::where('user_id',$userId)->where('deleted',0)->where('activated',1)->get();
        foreach($saugyklos as  $saugykla){

            $isFolderExists = Folder::where("file_id",$newFile -> id)->where('cloud_service',$saugykla-> id)->count();
            if($isFolderExists > 0) {
                continue;
            }

            if(  $saugykla ->id == $service -> id ){
                Folder::create([
                    "file_id" => $newFile -> id,
                    "cloud_service" => $service-> id,
                    "id_on_cloud" => $folder['basename'],
                ]);
            }else{
                // Gaunamas atitinkamas failų servisas prikalusomai nuos saugyklos tipo
                $fileService =  $saugykla -> getServiceInstance();
                if($fileService != null){
                    $savePathOnCloud = $this -> getDirectory($saugykla->id,  $FolderInfo -> file_id);  
                    $fileService -> makeDirectory($savePathOnCloud, $folder['filename'], $newFile -> id);
                }
            }

        }   
    }

    private function getDirectory($serviceId,$fileId){
        $result = $this -> getDirectoryOfFile($serviceId,$fileId);
        $exp = explode('/',$result);
         return implode('/',array_reverse($exp));
    }

    private function getDirectoryOfFile($serviceId,$fileId){
        $item = File::select('name','id','parent_id')->where('id',$fileId)->first();
        $itemOnCloud =  Folder::where('file_id', $item ->id)->where('cloud_service',$serviceId)->first();
        if( $item -> parent_id != null)
            return  $itemOnCloud -> id_on_cloud.'/'.$this -> getDirectoryOfFile($serviceId, $item->parent_id);
        return $itemOnCloud -> id_on_cloud;
    }
}
