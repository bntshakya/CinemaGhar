@php
use Carbon\Carbon;

date_default_timezone_set('Asia/Kathmandu');
$today = Carbon::now();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket list</title>
    @vite('resources/css/app.css')
</head>
<body background="{{url('storage/popcorn_background.jpg')}}" style="background-size:cover">
    <x-navbar></x-navbar>
    <div class="mt-8 mx-auto overflow-x-auto relative shadow-md sm:rounded-lg w-4/5 max-w-4xl">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Movie
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Movie Date
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Movie Hall
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Movie Time
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Seats
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3">
                        View tickets
                    </th>
                </tr>
            </thead>
<tbody>
    @foreach ($tickets as $groupedTickets)
        @foreach ($groupedTickets as $index => $ticket)
            <tr class="bg-dark border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-sky-700 dark:hover:bg-gray-700">
                @if ($index === 0)
                                    <th scope="row" class="px-6 py-4 font-medium text-white-900 whitespace-nowrap dark:text-white">
                                        {{$ticket['movie_name']}}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{($ticket['created_at'])->toDateString()}}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{($ticket->location)->location . "--" . ($ticket->location)->hall_name}}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{($ticket->movietime)->movie_time}}
                                    </td>
                                    <td class="px-6 py-4">
                                        @foreach ($groupedTickets as $tkt)
                                            {{$tkt['ticket_seat']}}@if (!$loop->last), @endif
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                    $moviedate = Carbon::parse($ticket['created_at']->toDateString() . ' ' . ($ticket->movietime)->movie_time);
                                        @endphp
                                        @if ($today > $moviedate)
                                            <!-- Expired status HTML -->
                                            <dl class="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                                                <dt class="text-base font-medium text-gray-500 dark:text-gray-400">Status:</dt>
                                                <dd
                                                    class="me-2 mt-1.5 inline-flex items-center rounded bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-300">
                                                    <svg class="me-1 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M6 18 17.94 6M18 18 6.06 6" />
                                                    </svg>
                                                    Expired
                                                </dd>
                                            </dl>
                                        @else
                                            <!-- Valid status HTML -->
                                            <dl class="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                                                <dt class="text-base font-medium text-gray-500 dark:text-gray-400">Status:</dt>
                                                <dd
                                                    class="me-2 mt-1.5 inline-flex items-center rounded bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">
                                                    <svg class="me-1 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M5 11.917 9.724 16.5 19 7.5" />
                                                    </svg>
                                                    Valid
                                                </dd>
                                            </dl>
                                        @endif
                                    </td>
                                    <td>
                                        <form method="get" action="{{route('qr.generate')}}">
                                            <input type="hidden" name="ticket_id" value="{{$ticket->id}}">
                                            <button type="submit"
                                                class="text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">QR
                                                Code</button>
                                        </form>
                                    </td>
                @endif
            </tr>
        @endforeach
    @endforeach
</tbody>
        </table>
        <div class="mt-4">
            {{ $tickets->links() }}
        </div>

    </div>    
</body>
</html>