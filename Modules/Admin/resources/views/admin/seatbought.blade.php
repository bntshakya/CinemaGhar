@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>QR scans</h1>
@stop
@section('meta_tags')
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
@endsection

@section('content')
    <table>
        <div class="relative overflow-x-auto">
            <table id='ticketsTable' class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Movie
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Time
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Location
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Hall
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tickets as $ticket)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-dark">
                                {{$ticket->movie}}
                            </th>
                            <td class="px-6 py-4">
                                {{$ticket->movietime}}
                            </td>
                            <td class="px-6 py-4">
                                {{$ticket->location}}
                            </td>
                            <td class="px-6 py-4">
                                {{$ticket->hall}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </table>

@endsection