@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Grupių paskaitos</div>
                    <div class="panel-body">
                        <a href="{{ url('/administratrius/grupiu-paskaitos/create') }}" class="btn btn-success btn-sm" title="Priskirti paskaitą prie grupės ">
                            <i class="fa fa-plus" aria-hidden="true"></i> Priskirti paskaitą prie grupės
                        </a>

                        <form method="GET" action="{{ url('/administratrius/grupiu-paskaitos') }}" accept-charset="UTF-8" class="navbar-form navbar-right" role="search">
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
                                        <th>#</th><th>Paskaita</th><th>Grupė</th><th>Veiksmai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($grupiupaskaito as $item)
                                    <tr>
                                        <td>{{ $loop->iteration or $item->id }}</td>
                                        <td>{{ $paskaitos[$item->paskaita] }}</td><td>{{ $grupes[$item->grupe] }}</td>
                                        <td>
                                            <form method="POST" action="{{ url('/administratrius/grupiu-paskaitos' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-danger btn-xs" title="Ištriniti grupės priskyrimą" onclick="return confirm(&quot;Ar tikrai norite ištrinti?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i>Ištrinti</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $grupiupaskaito->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
