@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('mokinys.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Egzaminai</div>
                    <div class="panel-body">

                        <a href="{{ url('/mokinys/ivertinimas') }}" title="Atgal"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Atgal</button></a>
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table table-borderless">

                                <tbody>
                                   
                                    <tr><th> Teorinio egzamino įvertinimas </th><td> {{ $praktinisEgz }} </td></tr>
                                 	<tr><th> Praktinio egzamino įvertinimas</th><td> {{ $teorinisEgz }} </td></tr>
                                    
                                </tbody>
                 
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
