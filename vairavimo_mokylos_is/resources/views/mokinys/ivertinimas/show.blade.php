@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('mokinys.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Ivertinimas {{ $ivertinimai[0]['id'] }}</div>
                    <div class="panel-body">

                        <a href="{{ url('/mokinys/ivertinimas') }}" title="Atgal"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Atgal</button></a>
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table table-borderless">
                                @foreach($ivertinimai as $ivertinimas)
                                <tbody>
                                    <tr><th>ID</th><td>{{ $ivertinimas['id'] }}</td></tr>
                                    <tr><th> Ivertinimas </th><td> {{ $ivertinimas['ivertinimas'] }} </td></tr>
                                 	<tr><th> Įvertinimo aprašymas</th><td> {{ $ivertinimas['aprasymas'] }} </td></tr>
                                    <tr><th> Paskaitos pavadinimas</th><td> {{ $ivertinimas['paskaita'] }} </td></tr>
                                    <tr><th> Paskaitos tipas </th><td> {{ $ivertinimas['tipas'] }} </td></tr>
                                    <tr><th> Paskaitos aprašymas </th><td> {{ $ivertinimas['paskaitosAprasas'] }} </td></tr>
                                    <tr><th> Paskaitos pradžia </th><td> {{ substr($ivertinimas['pradzia'],0,16) }} </td></tr>
                                    <tr><th> Paskaitos pabaiga </th><td> {{ substr($ivertinimas['pabaiga'],0,16) }} </td></tr>
                                    <tr><th> Instruktorius </th><td> {{ $ivertinimas['instruktorius'] }} </td></tr>
                                </tbody>
                                @endforeach
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
