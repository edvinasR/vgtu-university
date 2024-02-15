@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row wide">
            @include('ket_instruktorius.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Grupės #{{ $ket_grupe->id }} atnaujinimas</div>
                    <div class="panel-body">
                        <a href="{{ url('/ket_instruktorius/ket_grupe') }}" title="Atgal"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Atgal</button></a>
                        <br />
                        <br />

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <form method="POST" action="{{ url('/ket_instruktorius/ket_grupe/' . $ket_grupe->id) }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            {{ csrf_field() }}

                            @include ('ket_instruktorius.k-e-t_grupe.form', ['submitButtonText' => 'Atnaujinti', 'atnaujinamas' =>true])

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
