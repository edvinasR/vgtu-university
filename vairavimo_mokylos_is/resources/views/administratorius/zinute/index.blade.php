@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Žinutės</div>
                    <div class="panel-body">
                        <a href="{{ url('/administratrius/zinute/create') }}" class="btn btn-success btn-sm" title="Sukurti naują žinutę">
                            <i class="fa fa-plus" aria-hidden="true"></i> Sukurti naują žinutę
                        </a>

                        <form method="GET" action="{{ url('/administratrius/zinute') }}" accept-charset="UTF-8" class="navbar-form navbar-right" role="search">
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
                                        <th>#</th><th>Tema</th><th>Ar perskaityta</th><th>Instruktorius</th><th>Mokinys</th><th>Veiksmai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($zinute as $item)
                                    <tr>
                                        <td>{{ $loop->iteration or $item->id }}</td>
                                        <td>{{ $item->tema }}</td><td>{{ $item->perskaitytas ==1 ? 'Taip' : 'Ne' }}</td><td>{{ $instruktoriai[$item->instruktorius] }}</td><td>{{ $mokiniai[$item->mokinys] }}</td>
                                        <td>
                                            <a href="{{ url('/administratrius/zinute/' . $item->id) }}" title="Peržiūrėti žinutę"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> </button></a>
                                            <a href="{{ url('/administratrius/zinute/' . $item->id . '/edit') }}" title="Atnaujniti žinutę"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> </button></a>

                                            <form method="POST" action="{{ url('/administratrius/zinute' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-danger btn-xs" title="Ištrinti žinutę" onclick="return confirm(&quot;Ar tikrai norite ištrinti šią žinutę?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $zinute->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
