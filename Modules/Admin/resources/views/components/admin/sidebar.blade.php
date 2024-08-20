@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Dashboard</h1>
@stop

@section('content')
<x-admin::admin.panel :moviesales="$moviesales" :dates="$dates" :b="$b" :movienames="$movienames" :tickets="$tickets" :locations="$locations"></x-admin::admin.panel>
@stop

@section('css')
{{-- Add here extra stylesheets --}}
{{--
<link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

