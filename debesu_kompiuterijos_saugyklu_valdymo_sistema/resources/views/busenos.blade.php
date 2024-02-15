@extends('layouts.main')
@section('body_header')
<div style="margin-bottom:40px">
    <span >Saugyklų naudojimo statistika</span>
        <span class="statistikos_tipas">
            Tipas:
            <select id="statistics_type" style="height: 30px; width: 180px;">
                <option value="double_chart">Paskirstymas pagal rinkmenų tipus</option>
                <option value="free_storage">Paskyrstymas pagal lasivos atminties kiekį</option>
                <option value="used_storage">Paskyrstymas pagal užimtos atminties kiekį</option>
                <option value="general_statistics">Bendra saugyklos informacija</option>
            </select>
        </span>
        <span class="statistikos_tipas">
            Saugykla: 
                <select id="service" style="height: 30px;">
                    <option value="">Sujungta saugykla</option>
                    @foreach( $services as $service)
                     <option value="{{ $service->id}}">{{$service->name}}</option>
                    @endforeach  
                </select>
        </span>
 </div>
@endsection
@section('body_content')
<div class="container double_chart">
        <div class="row">
          <div class="col-xs-6">
              <p class="text-center chart_header">Paskirstymas pagal tipą<p>
            <canvas id="myChart" class="chart" width="200" height="200"></canvas>
      
          </div>
          <div class="col-xs-6">
                <p class="text-center chart_header">Paskirstymas pagal dydį<p>
            <canvas id="myChart2"  class="chart" width="200" height="200"></canvas>
          </div>
        </div>
</div>
<div class="container free_storage">
        <p class="text-center chart_header">Paskirstymas pagal laisvos atminties dydį megabaitais<p>
        <div class="row">
            <div class="col-xs-3"></div>
            <div class="col-xs-6">
                <canvas id="free_storage" class="chart" width="200" height="200"></canvas>   
            </div>
            <div class="col-xs-3"></div>
        </div>
</div>
<div class="container used_storage">
        <p class="text-center chart_header">Paskirstymas pagal užimtos atminties dydį megabaitais<p>
        <div class="row">
            <div class="col-xs-3"></div>
            <div class="col-xs-6">
                <canvas id="used_storage" class="chart" width="200" height="200"></canvas>
            </div>
            <div class="col-xs-3"></div>
         </div>
</div>
<div class="container general_statistics">
        <div class="row">
          <div class="col-xs-12">
             <p class="text-center chart_header">Bednra informacija</p>
             <p class="general_storage_p"></p>
             <progress id="myProgress" role="progressbar" min="0" max="100"></progress>
             <div id="general_text"></div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="{{ asset('js/chart.js') }}"></script>
<script src="{{ asset('js/statistika.js') }}"></script>
@endsection