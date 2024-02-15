@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Paskaita {{ $paskaita->id }}</div>
                    <div class="panel-body">

                        <a href="{{ url('/administratrius/paskaita') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Atgal</button></a>
                        <a href="{{ url('/administratrius/paskaita/' . $paskaita->id . '/edit') }}" title="Edit paskaita"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Atnaujinti</button></a>

                        <form method="POST" action="{{ url('administratrius/paskaita' . '/' . $paskaita->id) }}" accept-charset="UTF-8" style="display:inline">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-xs" title="Delete paskaita" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Ištrinti</button>
                        </form>
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th>ID</th><td>{{ $paskaita->id }}</td>
                                    </tr>
                                    <tr><th> Pavadinimas </th><td> {{ $paskaita->pavadinimas }} </td>
                                    <tr>
                                        <th>Pradzia</th><td>{{  isset($paskaita) ? date('Y-m-d\TH:i:s', strtotime($paskaita->pradzia)) : ''}}</td>
                                    </tr>
                                     <tr>
                                        <th>Pabaiga</th><td>{{ isset($paskaita) ? date('Y-m-d\TH:i:s', strtotime($paskaita->pabaiga)) : '' }}</td>
                                    </tr>
                                    
                                    </tr><tr><th> Vieta </th><td> {{ $paskaita->vieta }} </td></tr><tr><th> Praktine Paskaita </th><td> {{ $paskaita->praktine_paskaita == 1? "Taip" : "Ne" }} </td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
