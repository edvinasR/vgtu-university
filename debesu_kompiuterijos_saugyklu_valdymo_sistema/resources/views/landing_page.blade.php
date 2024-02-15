@extends('layouts.main')
@section('body_header')
<span>Pasirinkite saugyklą</span>
@endsection
@section('body_content')
<div class="landing_container vert_auto_scrolbar">
    <div class="landing">
        @foreach($saugyklos as $saugykla)
       
            <div class="kachel" data-id={{$saugykla->id}}> 
                <a href="/rinkmenos/{{Auth::user()->rootDir()}}/{{ $saugykla->id}}">
                    <span class="ion-android-checkbox-outline">
                        <div style="text-align: center;">
                            <h2 class="elispis">{{$saugykla->name}}  </h2>
                            <img src="{{$saugykla->logo}}" style="width:100px;"></img>
                            <h4  class="elispis" id="{{$saugykla->id}}"> {{$saugykla-> owner == null ? "Nežinoma"  :  $saugykla-> owner}}</h4>
                            <h3  class="elispis">{{$saugykla-> free_storage}}  </h3>   
                        </div>
                    </span>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection
@section('scripts')
@endsection