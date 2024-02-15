@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">KET_grupe {{ $ket_grupe->id }}</div>
                    <div class="panel-body">

                        <a href="{{ url('/administratrius/ket_grupe') }}" title="Atgal"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Atgal</button></a>
                        <a href="{{ url('/administratrius/ket_grupe/' . $ket_grupe->id . '/edit') }}" title="Edit KET_grupe"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Redaguoti</button></a>

                        <form method="POST" action="{{ url('administratrius/ket_grupe' . '/' . $ket_grupe->id) }}" accept-charset="UTF-8" style="display:inline">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-xs" title="Naikinti KET_grupe" onclick="return confirm(&quot;Ar tikrai norite ištrinti?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Istrinti</button>
                        </form>
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th>ID</th><td>{{ $ket_grupe->id }}</td>
                                    </tr>
                                    <tr><th> Kategorija </th><td> {{ $ket_grupe->kategorija }} </td></tr><tr><th> Pavadinimas </th><td> {{ $ket_grupe->pavadinimas }} </td></tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
