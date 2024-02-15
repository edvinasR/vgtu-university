@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row wide">
            @include('instruktorius.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Žinutė #{{ $zinute->id }}</div>
                    <div class="panel-body">

                        <a href="{{ url('/mokinys/zinute') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Atgal</button></a>

                        <form method="POST" action="{{ url('mokinys/zinute' . '/' . $zinute->id) }}" accept-charset="UTF-8" style="display:inline">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-xs" title="Delete Zinute" onclick="return confirm(&quot;Ar tikrai norite ištrinti?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Ištrinti</button>
                        </form>
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th>ID</th><td>{{ $zinute->id }}</td>
                                    </tr>
                                    <tr><th> Tema </th><td> {{ $zinute->tema }} </td></tr>
                                     <tr><th>Turinys </th><td> {{ $zinute->zinute }} </td></tr>             
                                    <tr><th> Adresatas </th><td>Instruktorius: {{ $mokiniai[$zinute->instruktorius] }} </td></tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
