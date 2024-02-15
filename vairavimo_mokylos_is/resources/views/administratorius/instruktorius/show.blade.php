@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Instruktorius {{ $instruktorius->id }}</div>
                    <div class="panel-body">

                        <a href="{{ url('/administratrius/instruktorius') }}" title="Atgal"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Atgal</button></a>
                        <a href="{{ url('/administratrius/instruktorius/' . $instruktorius->id . '/edit') }}" title="Keisti instruktoriaus informaciją"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Atnaujinti</button></a>

                        <form method="POST" action="{{ url('administratrius/instruktorius' . '/' . $instruktorius->id) }}" accept-charset="UTF-8" style="display:inline">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-xs" title="Delete Instruktorius" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Naikinti</button>
                        </form>
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th>ID</th><td>{{ $instruktorius->id }}</td>
                                    </tr>
                                    <tr><th> Transporto priemonės numeris </th><td> {{ $instruktorius->transporto_priemones_numeris }} </td></tr><tr><th> Telefonas </th><td> {{ $instruktorius->telefonas }} </td></tr><tr><th> Naudotojas </th><td>  {{ $instruktoriai[$instruktorius->naudotojas]}} </td></tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
