@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Add Halls</h1>
@stop

@section('content')
    <div class="hall-container">
        <form method="post" action="{{route('admin.saveHallData')}}">
            @csrf
            Hall Location:<input type="text" name="hallLocation"><br/>
            Hall name:<input type="text" name="hallName"><br/>
            Hall Seats:<input type="number" name="hallSeatsNumber"><br/>
            <input type="submit" value="submit">
        </form>
    </div>
@endsection

@section('js')
<script>
    
 
</script>
@endsection

@section('meta_tags')

@vite('resources/js/app.js')
@vite('resources/css/app.css')
<!-- <link rel="stylesheet" href="{{ mix('resources/css/app.css') }}"> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pqGrid/3.5.1/pqgrid.min.css" />
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/remdond/jquery-ui.css" />
@endsection

@section('css')

@endsection