@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row wide">
            @include('ket_instruktorius.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Mokinys {{ $mokiny->id }}</div>
                    <div class="panel-body">

                        <a href="{{ url('/ket_instruktorius/mokinys') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Atgal</button></a>
                    
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th>ID</th><td>{{ $mokiny->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Vardas ir pavardė</th><td>{{ $users[$mokiny->naudotojas] }}</td>
                                    </tr>
                                    <tr><th> Kategorija </th><td> {{ $mokiny->kategorija }} </td></tr><tr><th> Grupė </th><td> {{$grupe[ $mokiny->grupe] }} </td></tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
