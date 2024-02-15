@extends('layouts.main')
@section('body_header')
<div id="files_wrapper">
 <span>
        @if($parentDirectory != 'root')
        <a href="/rinkmenos/{{$parentDirectory}}{{$rootUrl}}" class="btn btn-success header_button" >
            <i class="fa fa-arrow-left " aria-hidden="true"></i> 
            <span></span>
        </a>
        @endif 
        {{$headerText}}
</span>
 <span class="rusiavimas">Paieška
        <input type="text"  style="width: 178px;" id="paieska" value="{{$search}}"/>
        <button type="button" class="btn btn-success header_button" id="vykdyti_paieska">
             <i class="fa fa-search" aria-hidden="true"></i> 
        </button>
        <button id="change_view" class="btn btn-success header_button">
                <i class="fa fa-eye"></i>
        </button>
        <a  href="/view/change" class="btn btn-success header_button">
            <i class="fa fa-list-alt"></i>
        </a>
      
</span>
 <span class="rusiavimas">Rušiuoti pagal: 
     <select id="rusiavimas" style="height: 30px;">
        <option value="updated_at=ASC">Datą</option>
        <option value="size=ASC">Dydį</option>
        <option value="name=ASC">Pavadinimą</option>
        <option value="extension=ASC">Tipą</option>
        <option value="updated_at=DESC">Datą mažėjančiai</option>
        <option value="size=DESC">Dydį mažėjančiai</option>
        <option value="name=DESC">Pavadinimą mažėjančiai</option>
        <option value="extension=DESC">Tipą mažėjančiai</option>
    </select>   
</span>
</div>
<div class="folder-navigation">
    <a></a>
        @foreach($navigation as $navLink)
            <a href="/rinkmenos/{{$navLink->id}}{{$rootUrl}}" id="{{$navLink->id}}" ondrop="drop(event)" ondragover="allowDrop(event)" >{{$navLink->name}}</a> >
        @endforeach
</div>
<div class="alert alert-success alert-dismissable toast_msg" >
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <span id="msg"></span>
    
</div>
@endsection
@section('body_content')

<div class="loading">
    <img  class="loader" src="/img/loading.gif">
    <span class="loading_info">Vyksta sinchronizacija, prašome palaukti...</span>
</div>

@if (session('view_mode'))
    @if(session('view_mode') == 'icon')
    <span id="format_id" class="icon"></span>
        @include('partials.icon_view')
    @elseif(session('view_mode') == 'list')
        <span id="format_id" class="list"></span>
        @include('partials.list_view')
    @endif 
 @else
    <span id="format_id" class="icon"></span>
    @include('partials.icon_view')
 @endif
 <div class="info"><div class="soninio_lango_antraste"><span  id="side_window_header">INFORMACIJA</span><span id='close'>x</span></div><br><div id="informacijos_laukas"></div></div>
 @include('partials.dropzone')
 @endsection
@section('scripts')
<script>
    // jeigu yra uzdetas filtras jis bus automatiskai pasirenkamas uzsikrovus puslapiui
    @if($filter !='')
    document.addEventListener("DOMContentLoaded", function(event) { 
        document.getElementById('rusiavimas').value='{{$filter}}';
        });
    @endif
    var rootUrl ="{{$rootUrl}}";
</script>
<script src="{{ asset('js/dropzone.min.js') }}"></script>
<script src="{{ asset('js/rinkmenos.js') }}">

</script>
@endsection