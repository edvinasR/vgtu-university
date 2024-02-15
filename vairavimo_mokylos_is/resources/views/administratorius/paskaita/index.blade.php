@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Paskaitos</div>
                    <div class="panel-body">
                        <a href="{{ url('/administratrius/paskaita/create') }}" class="btn btn-success btn-sm" title="Add New Paskaitum">
                            <i class="fa fa-plus" aria-hidden="true"></i>Sukurti naują
                        </a>

                        <form method="GET" action="{{ url('/administratrius/paskaita') }}" accept-charset="UTF-8" class="navbar-form navbar-right" role="search">
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
                                        <th>#</th><th>Pavadinimas</th><th>Pradzia</th><th>Pabaiga</th><th>Vieta</th><th>Praktinė Paskaita</th><th>Veiskmai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($paskaita as $item)
                                    <tr>
                                        <td>{{ $loop->iteration or $item->id }}</td>
                                        <td>{{ $item->pavadinimas }}</td> 
                                        <td>{{  isset($item) ? date('Y-m-d\ H:i', strtotime($item->pradzia)) : ''}}</td>
                                      	<td>{{ isset($item) ? date('Y-m-d\ H:i', strtotime($item->pabaiga)) : '' }}</td> 
                                        <td>{{ $item->vieta }}</td><td>{{ $item->praktine_paskaita == 1? "Taip" : "Ne" }}</td>                                  
                                        
                                        <td>
                                            <a href="{{ url('/administratrius/paskaita/' . $item->id) }}" title="Peržiūrėti paskaitą"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> </button></a>
                                            <a href="{{ url('/administratrius/paskaita/' . $item->id . '/edit') }}" title="Redaguoti paskaitą"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> </button></a>

                                            <form method="POST" action="{{ url('/administratrius/paskaita' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-danger btn-xs" title="Ištrinti paskaitą" onclick="return confirm(&quot;Ar tikrai norite ištrinti šitą paskaitą?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $paskaita->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
