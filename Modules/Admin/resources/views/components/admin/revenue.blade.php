@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Revenue</h1>
@stop

@section('content')
    @include('datatables.revenue')
@endsection

@section('meta_tags')
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.css" rel="stylesheet" />
@endsection