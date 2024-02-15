@extends('layouts.main')
@section('body_header')
 <span>Nustatymai</span>
@endsection
@section('body_content')
@if (session('disk_error'))
<div class="alert alert-danger alert-dismissable">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    {{ session('disk_error') }}
</div>
@endif
@if (session('disk_success'))
<div class="alert alert-success alert-dismissable">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    {{ session('disk_success') }}
</div>
@endif
<div class="row vert_auto_scrolbar">
    <div class="col-xs-12">
        <div class="card">
            <div style="min-height: 0px;">
                @if(count($storageServices))
                <table class="table table-striped table-users">
                    <thead>
                    <tr>
                        <th>Pavadinimas</th>
                        <th>Priklauso</th>
                        <th>Panaudota</th>
                        <th>Laisvos Talpos</th>
                        <th>Tipas</th>
                        <th>Aktyvi</th>
                        <th>Logotipas</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($storageServices as $storageService)
                            <tr data-id="{{$storageService->id}}" @if(!$storageService -> activated)  class="not_activated" @endif>
                                <td>
                                   <input class="saugyklos_pav"  data-target="/saugykla/{{$storageService->id}}/rename"  type="text" value="{{$storageService->name}}"/> 
                                </td>
                                <td class="belongsTo">
                                     {{$storageService->owner}}
                                </td>
                                <td class="usedSpace">
                                        kraunama ...
                                </td>
                                <td class="freeSpace">
                                        kraunama ...
                                </td>
        
                                <td>
                                    {{isset($storageService->type) ? $storageService->type : 'Google' }}
                                </td>
                                <td>
                                    @if($storageService->activated)
                                        Taip
                                    @else
                                         <a href="/saugykla/{{$storageService->id}}/aktyvuoti">Aktyvuoti</a>
                                    @endif
                                </td>
                                <td>
                                    <img style="width: 50px;" src="{{ $storageService->logo}}">
                                </td>
                                <td>
                                    <a data-confirm="Ar tikrai norite ištrinti saugyklą?" data-method="DELETE" rel="nofollow" data-target="/saugykla/{{$storageService->id}}" class="close delete"  aria-label="close">&times;</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
                <p>
                    <button id="set_btn" class="btn btn-lg btn-primary pull-right" data-toggle="modal" data-target="#settings_modal"><i aria-hidden="true"  class="fa fa-plus-circle"></i> Pridėti naują saugyklą</button>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="settings_modal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Pridėti naują saugyklą</h4>
        </div>
        <div class="modal-body" >
            <div id="settings_error" class="alert alert-danger fade in">
                <span id="error_text"></span>
            </div>
            <label for="type">Tipas</label>
            <select class="settings_input" id="type" name="type">
              <option value="empty" disabled selected value> Pasirikite tipą</option>
              <option value="google">GoogleDrive</option>
              <option value="dropbox">DropBox</option>
              <option value="onedrive">OneDrive</option>
            </select>
            <div id="settings_body">
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Uždaryti</button>
        </div>
      </div>
      
    </div>
  </div>
@endsection
@section('scripts')
<script src="{{ asset('js/settings.js') }}"></script>
@endsection