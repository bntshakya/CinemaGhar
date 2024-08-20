@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Notification</h1>
@stop

@section('content')
    <div>
        <form method="post" action="{{route('admin.salessearch')}}">
            @csrf
            <input type="number" name="minsales">Min Sales
            <input type="number" name="maxsales">Max sales
            <input type="submit" value="search">
        </form>
    </div>
@endsection

@section('js')
@endsection

@section('meta_tags')

@endsection