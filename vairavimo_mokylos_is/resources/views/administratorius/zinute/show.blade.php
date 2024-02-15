@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Žinutė {{ $zinute->id }}</div>
                    <div class="panel-body">

                        <a href="{{ url('/administratrius/zinute') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="{{ url('/administratrius/zinute/' . $zinute->id . '/edit') }}" title="Edit Zinute"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>

                        <form method="POST" action="{{ url('administratrius/zinute' . '/' . $zinute->id) }}" accept-charset="UTF-8" style="display:inline">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-xs" title="Delete Zinute" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                        </form>
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th>ID</th><td>{{ $zinute->id }}</td>
                                    </tr>
                                    <tr><th> Tema </th><td> {{ $zinute->tema }} </td></tr><tr><th> Ar perskaityta </th><td> {{ $zinute->perskaitytas == 1 ? 'Taip' : 'Ne' }} </td></tr><tr><th> Mokinys </th><td> {{ $mokiniai[$zinute->mokinys] }} </td></tr><tr><th> Instruktorius </th><td> {{ $instruktoriai[$zinute->instruktorius] }} </td></tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
