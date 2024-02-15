@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Naudotojai</div>
                    <div class="panel-body">
                        <a href="{{ url('/administratrius/naudotojas/create') }}" class="btn btn-success btn-sm" title="Add New Mokiny">
                            <i class="fa fa-plus" aria-hidden="true"></i>Sukurti naują naudotoją
                        </a>

                        <form method="GET" action="{{ url('/administratrius/naudotojas') }}" accept-charset="UTF-8" class="navbar-form navbar-right" role="search">
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
                                        <th>#</th><th>Vardas</th><th>Pavardė</th><th>El. paštas</th><th>Teisės</th><th>Veiksmai</th>
                                    </tr>
                                </thead>
                                <tbody>

                                @foreach($naudotojas as $item)
                                    <tr>
                                        <td>{{ $loop->iteration or $item->id }}</td>                     
                                        <td>{{ $item->name}}</td>                 
                                        <td>{{ $item->surename}}</td>
                                        <td>{{ $item->email}}</td>
                                        <td>{{ $teises[$item->teises_FK] }}</td>
                                        <td>
                                            <a href="{{ url('/administratrius/naudotojas/' . $item->id) }}" title="Rodyti naudotojo informaciją"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> </button></a>
                                            <a href="{{ url('/administratrius/naudotojas/' . $item->id . '/edit') }}" title="Atnaujinti naudotojo informaciją"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> </button></a>

                                            <form method="POST" action="{{ url('/administratrius/naudotojas' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-danger btn-xs" title="Naikiniti naudotoją" onclick="return confirm(&quot;Ar tikrai norite ištrinti?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $naudotojas->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
