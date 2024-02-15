@extends('layouts.app')
@section('content')
<div class="body">
    @include('navigation.navigation')
    <div class="content"> 
        <div class="header">
            @yield('body_header')          
        </div>
        <div class="section_content">
            @yield('body_content')
        </div>
    </div>
</div>
@endsection
