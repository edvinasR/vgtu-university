<div class="grid-container context-menu-main selectable" data-directory="{{$directory}}">
    @foreach($files as $file)
        <div class="grid-item" data-id="{{$file->id}}">
            @if($file -> extension == 'a_folder')    
               <a href= "/rinkmenos/{{$file->id}}{{$rootUrl}}" class="folder_link"> <div class="folder context-menu" data-name="{{preg_replace('/\\.[^.\\s]{3,4}$/', '', $file->name)}}" id="{{$file->id}}" ondragstart="dragStart(event)" ondrag="dragging(event)" draggable="true" ondrop="drop(event)" ondragover="allowDrop(event)"></div>{{$file-> name}}</a>
            @else
                <a href="/file/{{$file->id}}/content">
                    <img class="file_item context-menu" onerror="if (this.src != '/img/png/file.png') this.src = '/img/png/file.png';" ondragstart="dragStart(event)"  data-name="{{preg_replace('/\\.[^.\\s]{3,4}$/', '', $file->name)}}" id="{{$file->id}}" ondrag="dragging(event)" draggable="true" src="/img/png/{{strtolower($file-> extension)}}.png" ></img>
                    <br>
                    {{$file-> name}} 
                    <br>
                    <span class="rinkmenos_dydis">{{round($file->size*0.000001,2).'MB'}}</span>
                </a>
            @endif
        </div> 
    @endforeach
</div>