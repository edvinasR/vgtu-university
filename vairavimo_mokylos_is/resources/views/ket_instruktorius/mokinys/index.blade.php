@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row wide">
            @include('ket_instruktorius.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Mokiniai</div>
                    <div class="panel-body">

						    <form method="GET" action="{{ url('/ket_instruktorius/mokinys') }}" accept-charset="UTF-8" class="navbar-form navbar-left" role="search">
                            <div class="input-group">
                                <select  class="form-control" name="grupe" id="grupiu_filtras"  >
                                		<option value=-1 selected="selected">Visos grupės</option>
                                	@foreach($grupe as $key => $grup)
                                		<option value={{$key}} {{request('grupe') == $key ? 'selected="selected"': ''}}>{{$grup}}</option>
                                	@endforeach
                                
                                </select>
                                <span class="input-group-btn" style="z-index: -20;">
                                    <button class="btn btn-default" type="submit" id="grupiu_filtras_submit">
                                        <i class="fa fa-arrow-right"></i>
                                    </button>
                                </span>
                            </div>
                        </form>
                        <form method="GET" action="{{ url('/ket_instruktorius/mokinys') }}" accept-charset="UTF-8" class="navbar-form navbar-right" role="search">
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
                        <div class="table-responsive" style="overflow-x: visible;">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>#</th><th>Vardas ir pavardė</th><th>Kategorija</th><th>Grupė</th><th>Veiksmai</th>
                                    </tr>
                                </thead>
                                <tbody>

                                @foreach($mokinys as $item)
                                    <tr>
                                        <td>{{ $loop->iteration or $item->id }}</td>                     
                                        <td>{{ $users[$item->naudotojas]}}</td>                 
                                        <td>{{ $item->kategorija }}</td><td>{{ $grupe[$item->grupe] }}</td>
                                        <td>
                                            <a href="{{ url('/ket_instruktorius/mokinys/' . $item->id) }}" title="Rodyti mokinio inforamcią…"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> </button></a>
                                        	<a href="{{ url('/ket_instruktorius/mokinys/' . $item->id . '/edit') }}" title="Keisti mokinio būseną"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> </button></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $mokinys->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    $(document).ready(function() {
    	  $('#grupiu_filtras').on('change', function() {
    	    	$('#grupiu_filtras_submit').click();
    	  });
    	});
    </script>
@endsection
