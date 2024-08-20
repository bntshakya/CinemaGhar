@extends('adminlte::page')
@section('title', 'Customers')

@section('content_header')
<h1>Customers</h1>
@stop
@section('content')
    @include('datatables.registers')
@endsection
