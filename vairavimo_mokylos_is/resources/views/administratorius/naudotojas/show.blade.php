@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Naudotojas {{ $naudotojas->id }}</div>
                    <div class="panel-body">

                        <a href="{{ url('/administratrius/naudotojas') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Atgal</button></a>
                        <a href="{{ url('/administratrius/naudotojas/' . $naudotojas->id . '/edit') }}" title="Redaguoti naudotoją"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Redaguoti</button></a>

                        <form method="POST" action="{{ url('administratrius/naudotojas' . '/' . $naudotojas->id) }}" accept-charset="UTF-8" style="display:inline">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-xs" title="Ištrinti naudotoją" onclick="return confirm(&quot;Ar tikrai norite ištrinti?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Naikinti</button>
                        </form>
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th>ID</th><td>{{ $naudotojas->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Vardas ir pavardė</th><td>{{ $naudotojas->name.' '.$naudotojas->surename }}</td>
                                    </tr>
                                    <tr>
                                        <th>El. paštas</th><td>{{ $naudotojas->email}}</td>
                                    </tr>
                                    <tr><th> Užšifruotas slaptažodis </th><td> {{ $naudotojas->password }} </td></tr><tr><th> Teisės</th><td> {{$teises[ $naudotojas->teises_FK] }} </td></tr><tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
