@extends('layouts.main')
@section('body_header')
 <span>Pagrindinis langas</span>
@endsection
@section('body_content')
<ul>
        
    @for ($i = 0; $i < 100; $i++)
        <p>Testuoju {{$i}}</p>
    @endfor
</ul>
@endsection
<script>

</script>