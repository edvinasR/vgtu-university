<div class=" context-menu-main selectable" data-directory="{{$directory}}">

        <table class="list_icons_table" >
            @if(count($files))
            <tr>
                <th></th>
                <th>Pavadinimas</th>
                <th>Tipas</th>
                <th>Dydis</th>
                <th>Išdalintas</th>
                <th>Saugykla</th>
                <th>Saug. tipas</th>
                <th>Sukurtas</th>
            </tr>
            @endif
            @foreach($files as $file)
            <tr>
                <td class="table_icon">
                    <div class="list_view_item"  data-id="{{$file->id}}">
                            @if($file -> extension == 'a_folder')    
                                <a href= "/rinkmenos/{{$file->id}}{{$rootUrl}}" class="folder_link"> 
                                    <img class="list_img context-menu" data-name="{{preg_replace('/\\.[^.\\s]{3,4}$/', '', $file->name)}}" id="{{$file->id}}" ondragstart="dragStart(event)" ondrag="dragging(event)" draggable="true" ondrop="drop(event)" ondragover="allowDrop(event)" src="/img/png/a_folder.png"></img>
                                </a>
                            @else
                                <a href="/file/{{$file->id}}/content">
                                    <img class="list_img context-menu" onerror="if (this.src != '/img/png/file.png') this.src = '/img/png/file.png';" ondragstart="dragStart(event)"  data-name="{{preg_replace('/\\.[^.\\s]{3,4}$/', '', $file->name)}}" id="{{$file->id}}" ondrag="dragging(event)" draggable="true" src="/img/png/{{strtolower($file-> extension)}}.png" ></img>
                                </a>
                            @endif
                    </div> 
                </td>
                <td>{{strlen($file->name)> 35 ? substr($file->name,0,35)."..." : $file->name}}</td>
                <td>{{strtoupper($file->extension == 'a_folder'? 'aplankas' : $file->extension)}}</td>
                <td>{{round($file->size*0.000001,2).'MB'}}</td>
                <td>{{$file->chunked ? "T":"N"}}</td>
                @if($file->storage_service != null)
                    <td>{{$services[$file->storage_service]["name"] }}</td>
                    <td>{{$services[$file->storage_service]["type"] }}</td>            
                @else
                    <td>Kiekviena</td>
                    <td></td>
                @endif
                <td>{{$file->updated_at}}</td>
            </tr>         
            @endforeach
        </table>

</div>