@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Mokinio būsena #{{ $mokiniobusena->id }}</div>
                    <div class="panel-body">

                        <a href="{{ url('/administratrius/mokinio-busena') }}" title="Atgal"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Atgal</button></a>
                        <a href="{{ url('/administratrius/mokinio-busena/' . $mokiniobusena->id . '/edit') }}" title="Edit MokinioBusena"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Atnaujinti</button></a>

                        <form method="POST" action="{{ url('administratrius/mokiniobusena' . '/' . $mokiniobusena->id) }}" accept-charset="UTF-8" style="display:inline">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-xs" title="Delete MokinioBusena" onclick="return confirm(&quot;Ar tikrai norite ištrinti?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Naikinti</button>
                        </form>
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th>ID</th><td>{{ $mokiniobusena->id }}</td>
                                    </tr>
                                    <tr><th> Teorinio egzamino įvertinimas </th><td> {{ $mokiniobusena->teorinio_egzamino_ivertinimas }} </td></tr><tr><th> Praktinio egzamino įvertinimas </th><td> {{ $mokiniobusena->praktinio_egzamino_ivertinimas }} </td></tr><tr><th> Mokinys </th><td> {{ $mokiniai[$mokiniobusena->mokinys] }} </td></tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
