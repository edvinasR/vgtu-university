@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Ivertinimas {{ $ivertinimas->id }}</div>
                    <div class="panel-body">

                        <a href="{{ url('/administratrius/ivertinimas') }}" title="Atgal"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Atgal</button></a>
                        <a href="{{ url('/administratrius/ivertinimas/' . $ivertinimas->id . '/edit') }}" title="Edit Ivertinima"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Atnaujinti</button></a>

                        <form method="POST" action="{{ url('administratrius/ivertinimas' . '/' . $ivertinimas->id) }}" accept-charset="UTF-8" style="display:inline">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-xs" title="Delete Ivertinima" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Ištrinti</button>
                        </form>
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th>ID</th><td>{{ $ivertinimas->id }}</td>
                                    </tr>
                                    <tr><th> Ivertinimas </th><td> {{ $ivertinimas->ivertinimas }} </td></tr><tr><th> Paskaita </th><td> {{ $paskaitos[$ivertinimas->paskaita] }} </td></tr><tr><th> Mokinys </th><td> {{ $mokiniai[$ivertinimas->mokinys] }} </td></tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
