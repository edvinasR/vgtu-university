@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Instruktoriai</div>
                    <div class="panel-body">
                        <a href="{{ url('/administratrius/instruktorius/create') }}" class="btn btn-success btn-sm" title="Add New Instruktorius">
                            <i class="fa fa-plus" aria-hidden="true"></i> Sukurti naują
                        </a>

                        <form method="GET" action="{{ url('/administratrius/instruktorius') }}" accept-charset="UTF-8" class="navbar-form navbar-right" role="search">
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
                                        <th>#</th><th>Transporto priemonės numeris</th><th>Telefonas</th><th>Naudotojas</th><th>Veiksmai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($instruktorius as $item)
                                    <tr>
                                        <td>{{ $loop->iteration or $item->id }}</td>
                                        <td>{{ $item->transporto_priemones_numeris }}</td><td>{{ $item->telefonas }}</td><td>{{ $instruktoriai[$item->naudotojas] }}</td>
                                        <td>
                                            <a href="{{ url('/administratrius/instruktorius/' . $item->id) }}" title="Peržiūrėti instruktorius"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> </button></a>
                                            <a href="{{ url('/administratrius/instruktorius/' . $item->id . '/edit') }}" title="Atnaujniti instruktorių"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> </button></a>

                                            <form method="POST" action="{{ url('/administratrius/instruktorius' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-danger btn-xs" title="Ištrinti instruktorių" onclick="return confirm(&quot;Ar tikrai norite ištrinti?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $instruktorius->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
