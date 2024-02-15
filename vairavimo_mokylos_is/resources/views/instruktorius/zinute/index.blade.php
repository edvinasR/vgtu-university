@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row wide">
            @include('instruktorius.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Žinutės</div>
                    <div class="panel-body">
                        <a href="{{ url('/instruktorius/zinute/create') }}" class="btn btn-success btn-sm" title="Sukurti naują žinutę">
                            <i class="fa fa-plus" aria-hidden="true"></i> Kurti naują žinutę
                        </a>

						<form method="GET" action="{{ url('/instruktorius/zinute') }}" accept-charset="UTF-8" class="navbar-form navbar-right" >
                            <div class="input-group">
                                <select  class="form-control" name="tipas" id="tipas" >
                                		<option value="I" {{ request('tipas') == 'I' ? 'selected="selected"': '' }}>Išsiųstos žinutės</option>
                                		<option value="M" {{ request('tipas') == 'M' ? 'selected="selected"': '' }}>Gautos žinutės</option>      
                                </select>
                                <span class="input-group-btn" style="z-index: -20;">
                                    <button class="btn btn-default" type="submit" id="tipas_submit">
                                        <i class="fa fa-arrow-right"></i>
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
                                        <th>#</th><th>Tema</th><th>Ar perskaityta</th><th>Mokinys</th><th>Data</th><th>Veiksmai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($zinute as $item)
                                    <tr class="{{$item->perskaitytas ==1 ? '': 'bold'}}">
                                        <td>{{ $loop->iteration or $item->id }}</td>
                                        <td>{{ $item->tema }}</td><td>{{ $item->perskaitytas ==1 ? 'Taip' : 'Ne' }}</td><td>{{ $mokiniai[$item->mokinys] }}</td><td>{{ substr($item -> created_at,0,16) }}</td>
                                        <td>
                                            <a href="{{ url('/instruktorius/zinute/' . $item->id) }}" title="Peržiūrėti žinutę"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> </button></a>                     
                                            <form method="POST" action="{{ url('/instruktorius/zinute' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-danger btn-xs" title="Ištrinti žinutę" onclick="return confirm(&quot;Ar tikrai norite ištrinti šią?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
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
        <script>
    $(document).ready(function() {
    	  $('#tipas').on('change', function() {
    	    	$('#tipas_submit').click();
    	  });
    	});
    </script>
@endsection
