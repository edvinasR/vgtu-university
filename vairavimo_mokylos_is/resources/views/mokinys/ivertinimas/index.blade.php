@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('mokinys.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Įvertinimai</div>
                    <div class="panel-body">
                        <a href="{{ url('/mokinys/egzaminai') }}" class="btn btn-success btn-sm" title="Mano egzaminai">
                            <i class="" aria-hidden="true"></i> Egzaminai
                        </a>

                        <form method="GET" action="{{ url('/mokinys/ivertinimas') }}" accept-charset="UTF-8" class="navbar-form navbar-right" role="search">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Ieškoti..." value="{{ request('search') }}">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </form>

                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>#</th><th>Įvertinimas</th><th>Paskaita</th><th>Data</th><th>Tipas</th><th>Veiksmai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($ivertinimas as $item)
                                    <tr>
                                        <td>{{ $loop->iteration or $item->id }}</td>
                                        <td>{{ $item['ivertinimas'] }}</td><td>{{ $item['paskaita'] }}</td><td>{{ substr($item['data'],0,16) }}</td><td>{{  $item['tipas']}}</td>
                                        <td>
                                            <a href="{{ url('/mokinys/ivertinimas/' . $item['id']) }}" title="Peržiūrėti įvertinimo informaciją"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> </button></a>                              
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                           
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
