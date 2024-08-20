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
    <title>Booking list</title>
    @vite('resources/css/app.css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet">

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
                        Buy
                    </th>
                    <th>
                        Cancel
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tickets as $ticket)
                                                                                <tr
                                                                                    class="bg-dark border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-sky-700 dark:hover:bg-gray-700">
                                                                                    <th scope="row" class="px-6 py-4 font-medium text-white-900 whitespace-nowrap dark:text-white">
                                                                                        {{$ticket['movie_name']}}
                                                                                    </th>
                                                                                    <td class="px-6 py-4">
                                                                                        {{(App\Models\Location::find($ticket->location_id))->location . (App\Models\Location::find($ticket->location_id))->hall_name}}
                                                                                    </td>
                                                                                    <td class="px-6 py-4">
                                                                                        {{(App\Models\Movietime::find($ticket->movie_time_id))->movie_time}}
                                                                                    </td>
                                                                                    <td class="px-6 py-4">
                                                                                        @php
    $tkts = json_decode($ticket->selected_seats);
    foreach ($tkts as $tkt) {
        echo $tkt . ' ';
    }
                                                                                        @endphp
                                                                                    </td>

                                                                                    <td class="px-6 py-4">
                                                                                        {!! $ticket->paymentDone ?
        '<span class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">Confirmed</span>' :
        '<span class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Not Confirmed</span>' !!}
                                                                                    </td>
                                                                                    <td class="px-6 py-4">
                                                                                        <!-- Modal toggle -->
                                                                                        @if ($ticket->paymentDone)
                                                                                            <button
                                                                                                class="block text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                                                                                type="button" disabled>
                                                                                                Bought
                                                                                            </button>
                                                                                        @else
                                                                                            <button data-modal-target="modal-{{ $ticket->id }}" data-modal-toggle="modal-{{ $ticket->id }}"
                                                                                                class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                                                                                type="button">
                                                                                                Buy
                                                                                            </button>
                                                                                        @endif

                                                                                        <!-- Main modal -->
                                                                                        <div id="modal-{{ $ticket->id }}" tabindex="-1" aria-hidden="true"
                                                                                            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                                                                            <div class="relative p-4 w-full max-w-2xl max-h-full">
                                                                                                <!-- Modal content -->
                                                                                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                                                                                    <!-- Modal header -->
                                                                                                    <div
                                                                                                        class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                                                                                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                                                                                            Terms of Service
                                                                                                        </h3>
                                                                                                        <button type="button"
                                                                                                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                                                                                            data-modal-hide="modal-{{ $ticket->id }}">
                                                                                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                                                                                fill="none" viewBox="0 0 14 14">
                                                                                                                <path stroke="currentColor" stroke-linecap="round"
                                                                                                                    stroke-linejoin="round" stroke-width="2"
                                                                                                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                                                                            </svg>
                                                                                                            <span class="sr-only">Close modal</span>
                                                                                                        </button>
                                                                                                    </div>
                                                                                                    <!-- Modal body -->
                                                                                                    <div class="p-4 md:p-5 space-y-4">
                                                                                                        <form method="post" action="{{route('User.PaymentModal.Pay')}}">
                                                                                                            @csrf
                                                                                                            @foreach ($paymentMethods as $paymentMethod)
                                                                                                                @if ($paymentMethod->id === $defaultCardId)
                                                                                                                    <div>
                                                                                                                        <input type="radio" id="{{ $paymentMethod->id }}" name="paymentMethod"
                                                                                                                            value="{{ $paymentMethod->id }}" checked>
                                                                                                                        <label for="{{ $paymentMethod->id }}">
                                                                                                                            <div class="card mb-3 p-3 shadow-sm"
                                                                                                                                style="border-radius: 10px; background-color: #f8f9fa;">
                                                                                                                                <div class="card-body">
                                                                                                                                    <h5 class="card-title">{{ $paymentMethod->card->brand }}
                                                                                                                                    </h5>
                                                                                                                                    <p class="card-text">
                                                                                                                                        <strong>Country:</strong>
                                                                                                                                        {{ $paymentMethod->card->country }}<br>
                                                                                                                                        <strong>Expiry:</strong>
                                                                                                                                        {{ $paymentMethod->card->exp_month }}/{{ $paymentMethod->card->exp_year }}<br>
                                                                                                                                        <strong>Last 4 Digits:</strong> **** **** ****
                                                                                                                                        {{ $paymentMethod->card->last4 }}
                                                                                                                                    </p>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </label>
                                                                                                                    </div>
                                                                                                                @else
                                                                                                                    <div>
                                                                                                                        <input type="radio" id="{{ $paymentMethod->id }}" name="paymentMethod"
                                                                                                                            value="{{ $paymentMethod->id }}">
                                                                                                                        <label for="{{ $paymentMethod->id }}">
                                                                                                                            <div class="card mb-3 p-3 shadow-sm"
                                                                                                                                style="border-radius: 10px; background-color: #f8f9fa;">
                                                                                                                                <div class="card-body">
                                                                                                                                    <h5 class="card-title">{{ $paymentMethod->card->brand }}
                                                                                                                                    </h5>
                                                                                                                                    <p class="card-text">
                                                                                                                                        <strong>Country:</strong>
                                                                                                                                        {{ $paymentMethod->card->country }}<br>
                                                                                                                                        <strong>Expiry:</strong>
                                                                                                                                        {{ $paymentMethod->card->exp_month }}/{{ $paymentMethod->card->exp_year }}<br>
                                                                                                                                        <strong>Last 4 Digits:</strong> **** **** ****
                                                                                                                                        {{ $paymentMethod->card->last4 }}
                                                                                                                                    </p>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </label>
                                                                                                                    </div>
                                                                                                                @endif
                                                                                                            @endforeach
                                                                                                    </div>
                                                                                                    <!-- Modal footer -->
                                                                                                    <div
                                                                                                        class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                                                                                                        <input type="hidden" name="movie_time_id" value="{{$ticket->movie_time_id}}">
                                                                                                        <input type="hidden" name="movie_name" value="{{$ticket->movie_name}}">
                                                                                                        <input type="hidden" name="location_id" value="{{$ticket->location_id}}">
                                                                                                        <input type="hidden" name="selected_seats" value="{{$ticket->selected_seats}}">
                                                                                                        <input type="hidden" name="timing" value="00:00:00">
                                                                                                        <input type="hidden" name="movie_time_new" value="00:00:00">
                                                                                                        <input type="hidden" name="id" value="{{$ticket->id}}">
                                                                                                        <button data-modal-hide="modal-{{ $ticket->id }}" type="submit"
                                                                                                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                                                                            I accept
                                                                                                        </button>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                    <div>
                                                                                                        <button data-modal-hide="modal-{{ $ticket->id }}" type="button"
                                                                                                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Decline
                                                                                                        </button>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <form method="post" action="{{route('User.cancelBooking')}}">
                                                                                            @csrf
                                                                                            <input type="hidden" name="CancelId" value="{{$ticket->id}}">
                                                                                            @if ($ticket->paymentDone)
                                                                                                <button
                                                                                                    class="cancel-button block text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                                                                                    type="submit" disabled>
                                                                                                    Cancel
                                                                                                </button>
                                                                                            @else
                                                                                                <button
                                                                                                    class="cancel-button block text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                                                                                    type="submit">
                                                                                                    Cancel
                                                                                                </button>
                                                                                            @endif
                                                                                        </form>
                                                                                    </td>
                                                                                </tr>
                @endforeach
            </tbody>
            
    
        </table>
        {{$tickets->links()}}
    </div>

</body>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Use event delegation to attach the event listener to the table
        document.querySelector('tbody').addEventListener('click', function (event) {
            // Check if the clicked element is a Cancel button
            if (event.target.matches('.cancel-button')) {
                const flag = confirm('Are you sure ?');
                if (!flag) {
                    event.preventDefault(); // Prevent the form submission if user cancels
                }
            }
        });
    });
</script>


</html>