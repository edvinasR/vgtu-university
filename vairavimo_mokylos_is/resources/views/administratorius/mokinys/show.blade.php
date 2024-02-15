@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Mokinys {{ $mokiny->id }}</div>
                    <div class="panel-body">

                        <a href="{{ url('/administratrius/mokinys') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Atgal</button></a>
                        <a href="{{ url('/administratrius/mokinys/' . $mokiny->id . '/edit') }}" title="Edit Mokiny"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Redaguoti</button></a>

                        <form method="POST" action="{{ url('administratrius/mokinys' . '/' . $mokiny->id) }}" accept-charset="UTF-8" style="display:inline">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-xs" title="Delete Mokiny" onclick="return confirm(&quot;Ar tikrai norite ištrinti?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Naikinti</button>
                        </form>
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
                                    <tr><th> Kategorija </th><td> {{ $mokiny->kategorija }} </td></tr><tr><th> Grupė </th><td> {{$grupe[ $mokiny->grupe] }} </td></tr><tr><th> Vairavimo Instruktorius </th><td> {{ $inst[$mokiny->vairavimo_instruktorius] }} </td></tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
