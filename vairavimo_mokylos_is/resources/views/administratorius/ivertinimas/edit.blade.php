@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Įvertinimo atnaujinimas #{{ $ivertinimas->id }}</div>
                    <div class="panel-body">
                        <a href="{{ url('/administratrius/ivertinimas') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Atgal</button></a>
                        <br />
                        <br />

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <form method="POST" action="{{ url('/administratrius/ivertinimas/' . $ivertinimas->id) }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            {{ csrf_field() }}

                            @include ('administratorius.ivertinimas.form', ['submitButtonText' => 'Atnaujinti', 'atnaujinamas' =>true])

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
