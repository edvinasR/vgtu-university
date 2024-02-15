@extends('layouts.main')
@section('body_header')
 <span>Paruošti atsisiuntimai</span>
 <div style="color: chocolate;">Visi  nauji naudotojo atsisiuntimai automatiškai yra ištrinami po valandos laiko</div>
@endsection
@section('body_content')
@if(count($atsisiuntimai))
<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div style="min-height: 0px;">
               
                <table class="table table-striped table-users">
                    <thead>
                    <tr>
                        <th>Pavadinimas</th>
                        <th>Elementų skaičius</th>
                        <th>Tipas</th>
                        <th>Data</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($atsisiuntimai as $atsisiuntimas)
                            <tr data-id="{{$atsisiuntimas->id}}">
                                <td>
                                  {{$atsisiuntimas -> name}}
                                </td>
                                <td>
                                    {{$atsisiuntimas -> file_count}}
                                </td>
                                <td >
                                    {{$atsisiuntimas -> mimetype}}  
                                </td>
                                <td >
                                    {{$atsisiuntimas -> updated_at}}
                                </td>
        
                                <td>
                                    <a  rel="nofollow" href="/atsisiuntimai/{{$atsisiuntimas->id}}"  download="{{$atsisiuntimas -> name}}"  class="fa fa-download" ></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
