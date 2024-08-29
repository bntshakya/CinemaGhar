<!DOCTYPE html>
<html>

<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    @vite('resources/css/app.css')
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body background="{{url('storage/popcorn_background.jpg')}}" style="background-size:cover">

    <x-navbar></x-navbar>
    <title>Ticket Seats</title>
    <style>
        .ticket-seats-container {
            background-color: #313035;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .seat-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
            flex-direction: column;
        }

        .seat {
            background-image: url('/storage/seat_free.png');
            display: inline-block;
            width: 50px;
            height: 50px;
            margin: 10px;
            cursor: pointer;
            background-size: cover;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .selected {
            background-image: url('/storage/seat_select.png');
            background-size: cover;
        }

        .disabled {
            background-image: url('/storage/seat_booked.png');
            cursor: not-allowed;
            background-size: cover;
        }

        .seatingicons {
            display: inline-flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            gap: 50px;
            background-color: #313035;
            border-radius: 8px;
        }

        .container {
            padding-left: 38%;
        }

        .icon {
            text-align: center;
        }

        .icon img {
            display: block;
            margin: 0 auto;
        }

        .center-container {
            display: flex;
            justify-content: center;
        }

        .spinner {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1050;
            /* Higher than modal */
            align-items: center;
            justify-content: center;
        }

        .loader {
            border: 16px solid #f3f3f3;
            /* Light grey */
            border-top: 16px solid #3498db;
            /* Blue */
            border-radius: 50%;
            width: 120px;
            height: 120px;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        
       
    </style>

    <body>
      
        <h1
            class="mb-4 text-3xl font-extrabold leading-none tracking-tight text-gray-900 md:text-2xl lg:text-5xl dark:text-black ">
            {{ $movie->movie_name }}
        </h1>
        <h1
            class="mb-4 text-3xl font-extrabold leading-none tracking-tight text-gray-900 md:text-2xl lg:text-3xl dark:text-black ">
            Showtime: {{ $movietime_->movie_time }}<br />
            Hall: {{ App\Models\Location::find($movietime_->location_id)->hall_name }}<br />
            Location: {{ App\Models\Location::find($movietime_->location_id)->location }}<br />
            Ticketrate: <span id="ticketrate">{{ $ticketrate }}</span>
        </h1>
        <form id="ticketForm" method="post" action="{{ route('User.PaymentModal.Pay') }}">
            @csrf
            <div class="seat-container">
                <input type="hidden" name="movie_name" value="{{ $movie->movie_name }}">
                <input type="hidden" name="timing" value="{{ $movie->timing }}">
                <input type="hidden" name="location_id" value="{{ $hall->id }}">
                <input type="hidden" name="movie_time_new" value="{{ $movietime }}">
                <table class="ticket-seats-container">
                    @php
$seatNumber = 1;
$seats = $hall->seats;
$row = sqrt($seats);
                    @endphp
                    @for ($i = 0; $i < $row; $i++)
                                    <tr>
                                        @for ($j = 0; $j < $row; $j++)
                                                            <td>
                                                                <div class="seat {{ in_array($seatNumber, $tickets) ? 'disabled' : '' }}"
                                                                    onclick="{{ in_array($seatNumber, $tickets) ? '' : 'changecolor(this)' }}"
                                                                    data-seat-number="{{ $seatNumber }}">
                                                                </div>
                                                            </td>
                                                            @php
        $seatNumber++;
                                                            @endphp
                                        @endfor
                                    </tr>
                    @endfor
                </table>
            </div>
            <br />
            <div class="maincontainer">
                <div class="container">
                    <div class="seatingicons bg-teal-900">
                        <div class="icon">
                            <img alt="Free Seat" class="freeseat" src="{{ asset('storage/seat_free.png') }}">
                            <p class="self-center text-1xl font-semibold whitespace-nowrap dark:text-white">Free Seat
                            </p>
                        </div>
                        <div class="icon">
                            <img alt="Purchased Seat" class="purchasedseat" src="{{ url('storage/seat_booked.png') }}">
                            <p class="self-center text-1xl font-semibold whitespace-nowrap dark:text-white">Purchased
                                Seat</p>
                        </div>
                        <div class="icon">
                            <img alt="Selected Seat" class="selectedseat" src="{{ url('storage/seat_select.png') }}">
                            <p class="self-center text-1xl font-semibold whitespace-nowrap dark:text-white">Selected
                                Seat</p>
                        </div>
                    </div>
                </div>
                <br />
                <div class="center-container">
                    <!-- <button data-modal-target="default-modal" data-modal-toggle="default-modal" id="buyButton" -->
                    <button id="buyButton"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        type="button">
                        Buy Now
                    </button>
                    <input type="hidden" name="movie_time_id" value="{{ $movie_time_id }}">
                    <button id="bookButton" type='button'
                        class="text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                        {{$bookableFlag ? '' : 'disabled'}}>
                        Book Now
                    </button>
                </div>
            </div>
            <div id="book-modal" tabindex="-1" aria-hidden="true"
                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div role="status" id="spinner-book" class="spinner">
                    <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                        viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                            fill="currentColor" />
                        <path
                            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                            fill="currentFill" />
                    </svg>
                    <span class="sr-only">Loading...</span>
                </div>
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
                                class="cancel-booking text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-4 md:p-5 space-y-4">
                            <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                Movie: {{$movie->movie_name}}<br />
                                Showtime: {{ $movietime_->movie_time }}<br />
                                Hall: {{ App\Models\Location::find($movietime_->location_id)->hall_name }}<br />
                                Location: {{ App\Models\Location::find($movietime_->location_id)->location }}<br />
                                Selected Seats: <span class="selectedSeats"></span><br />
                                Cost: <span class="totalCost"></span>
                            </p>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                            <button data-modal-hide="book-modal" type="button" onclick="bookTickets(this)"
                                class=" text-white bg-blue-700 hover:bg-blue-800
                                focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5
                                text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Book</button>
                            <button type="button" class="cancel-booking py-2.5 px-5 ms-3 text-sm font-medium
                                text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100
                                hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700
                                dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white
                                dark:hover:bg-gray-700">Decline</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="default-modal" tabindex="-1" aria-hidden="true"
                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div role="status" id="spinner" class="spinner">
                    <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                        viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                            fill="currentColor" />
                        <path
                            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                            fill="currentFill" />
                    </svg>
                    <span class="sr-only">Loading...</span>
                </div>
                <div class="relative p-4 w-full max-w-2xl max-h-full">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                Payment Details
                            </h3>
                            <button type="button"
                                class="cancel text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>

                        <div class="p-4 md:p-5 space-y-4">
                            <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                Movie: {{$movie->movie_name}}<br />
                                Showtime: {{ $movietime_->movie_time }}<br />
                                Hall: {{ App\Models\Location::find($movietime_->location_id)->hall_name }}<br />
                                Location: {{ App\Models\Location::find($movietime_->location_id)->location }}<br />
                                Selected Seats: <span class="selectedSeats"></span><br />
                                Cost: <span class="totalCost"></span>
                            </p>
                            @foreach ($paymentMethods as $paymentMethod)
                                @if ($paymentMethod->id === $defaultCardId)
                                    <div>
                                        <input type="radio" id="{{ $paymentMethod->id }}" name="paymentMethod"
                                            value="{{ $paymentMethod->id }}" checked>
                                        <label for="{{ $paymentMethod->id }}">
                                            <div class="card mb-3 p-3 shadow-sm"
                                                style="border-radius: 10px; background-color: #f8f9fa;">
                                                <div class="card-body">
                                                    <h5 class="card-title">{{ $paymentMethod->card->brand }}</h5>
                                                    <p class="card-text">
                                                        <strong>Country:</strong> {{ $paymentMethod->card->country }}<br>
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
                                                    <h5 class="card-title">{{ $paymentMethod->card->brand }}</h5>
                                                    <p class="card-text">
                                                        <strong>Country:</strong> {{ $paymentMethod->card->country }}<br>
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
                        <div
                            class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                            <button type="submit" id="payButton"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Pay</button>

                            <button type="button"
                                class="cancel py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Cancel</button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
        @if (session('bookStatus'))
            <div id="toast-top-right"
            class="fixed flex items-center w-full max-w-xs p-4 space-x-4  divide-x rtl:divide-x-reverse divide-gray-200 rounded-lg shadow top-5 right-5 "
            role="alert">
                <div id="toast-default"
                    class="flex items-center w-full max-w-xs p-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800"
                    role="alert">
                    <div
                        class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-blue-500 bg-blue-100 rounded-lg dark:bg-blue-800 dark:text-blue-200">
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 18 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.147 15.085a7.159 7.159 0 0 1-6.189 3.307A6.713 6.713 0 0 1 3.1 15.444c-2.679-4.513.287-8.737.888-9.548A4.373 4.373 0 0 0 5 1.608c1.287.953 6.445 3.218 5.537 10.5 1.5-1.122 2.706-3.01 2.853-6.14 1.433 1.049 3.993 5.395 1.757 9.117Z" />
                        </svg>
                        <span class="sr-only">Fire icon</span>
                    </div>
                    <div class="ms-3 text-sm font-normal">Booking Successful</div>
                    <button type="button"
                        class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                        data-dismiss-target="#toast-default" aria-label="Close">
                        <span class="sr-only">Close</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif
        @if (session('paymentStatus'))
            <div id="toast-top-right-payment"
                class="fixed flex items-center w-full max-w-xs p-4 space-x-4  divide-x rtl:divide-x-reverse divide-gray-200 rounded-lg shadow top-5 right-5 "
                role="alert">
                <div id="toast-default-payment"
                    class="flex items-center w-full max-w-xs p-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800"
                    role="alert">
                    <div
                        class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-blue-500 bg-blue-100 rounded-lg dark:bg-blue-800 dark:text-blue-200">
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.147 15.085a7.159 7.159 0 0 1-6.189 3.307A6.713 6.713 0 0 1 3.1 15.444c-2.679-4.513.287-8.737.888-9.548A4.373 4.373 0 0 0 5 1.608c1.287.953 6.445 3.218 5.537 10.5 1.5-1.122 2.706-3.01 2.853-6.14 1.433 1.049 3.993 5.395 1.757 9.117Z" />
                        </svg>
                        <span class="sr-only">Fire icon</span>
                    </div>
                    <div class="ms-3 text-sm font-normal">Payment Successful</div>
                    <button type="button"
                        class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                        data-dismiss-target="#toast-default-payment" aria-label="Close">
                        <span class="sr-only">Close</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif
    </body>
    <script>
        function bookTickets() {
            const form = document.getElementById('ticketForm');
            const formData = new FormData(form);
            formData.append('_token', '{{ csrf_token() }}'); // Append CSRF token to FormData

            document.getElementById('spinner-book').style.display = 'block';

            // Disable all buttons
            var buttons = document.querySelectorAll('button');
            buttons.forEach(function (button) {
                button.disabled = true;
            });
            $.ajax({
                url: "{{ route('User.bookTickets') }}",
                type: 'POST',
                data: formData,
                processData: false, // Prevent jQuery from automatically transforming the data into a query string
                contentType: false, // Prevent jQuery from setting the Content-Type header
                success: function (data) {
                    location.reload();
                },
                error: function (xhr, status, error) {
                    // Handle any errors
                    console.error('Error:', error);
                }
            });
        }

        function changecolor(el) {
            const form = document.getElementById('ticketForm');
            el.classList.toggle('selected');
            let seatNumber = el.getAttribute('data-seat-number');
            let existingInput = form.querySelector('input[type="hidden"][name="selected_seats[]"][value="' + seatNumber + '"]');
            if (el.classList.contains('selected')) {
                if (!existingInput) {
                    let input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'selected_seats[]';
                    input.value = seatNumber;
                    form.appendChild(input);
                }
            } else {
                if (existingInput && existingInput.parentNode === form) {
                    form.removeChild(existingInput);
                }
            }
            updateTotalCostAndSeats();
        }

        function updateTotalCostAndSeats() {
            const ticketrate = parseFloat(document.getElementById('ticketrate').textContent);
            const selectedSeats = document.querySelectorAll('.seat.selected');
            const totalCost = ticketrate * selectedSeats.length;
            document.querySelectorAll('.totalCost').forEach(element => {
                element.textContent = totalCost.toFixed(2);
            })
            const seatNumbers = Array.from(selectedSeats).map(seat => seat.getAttribute('data-seat-number'));
            const selectedSeatsElements = document.querySelectorAll('.selectedSeats');
            selectedSeatsElements.forEach(element => {
                element.textContent = seatNumbers.join(', ');
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            @if ($bookableFlag === false)
                alert('Tickets cannot be booked now');
            @endif
            const buyButton = document.querySelector('#buyButton');
            const form = document.getElementById('ticketForm');
            const id = document.getElementById('default-modal');
            const options = {
                placement: 'bottom-right',
                backdrop: 'dynamic',
                backdropClasses:
                    'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40',
                closable: true,
            };
            const instanceOptions = {
                id: 'default-modal',
                override: true
            };
            const modal = new Modal(id, options, instanceOptions);
            buyButton.addEventListener('click', function (event) {
                const selectedSeats = form.querySelectorAll('input[type="hidden"][name="selected_seats[]"]');
                if (selectedSeats.length === 0) {
                    event.preventDefault();
                    alert('Please select at least one seat.');
                }
                else {
                    modal.show();
                }
            });

            const cancelButtons = document.querySelectorAll('.cancel');
            cancelButtons.forEach(button => {
                button.addEventListener('click', function (event) {
                    modal.hide();
                });
            });

            const BookBtn = document.getElementById('bookButton');
            const bookModalId = document.getElementById('book-modal');
            const modalBooking = new Modal(bookModalId, options, instanceOptions);
            BookBtn.addEventListener('click', function (event) {
                const selectedSeats = form.querySelectorAll('input[type="hidden"][name="selected_seats[]"]');
                if (selectedSeats.length === 0) {
                    event.preventDefault();
                    alert('Please select at least one seat.');
                }
                else {
                    modalBooking.show();
                }
            })
            const cancelBtnsBooking = document.querySelectorAll('.cancel-booking');
            cancelBtnsBooking.forEach(button => {
                button.addEventListener('click', function (event) {
                    modalBooking.hide();
                });
            });

            $('#payButton').click(function (event) {
                $('#spinner').css("display", "block");
            })
            @if (session('bookStatus'))
                const $targetEl = document.getElementById('toast-top-right');
                const optionsToast = {
                    transition: 'transition-opacity',
                    duration: 3000,
                    timing: 'ease-out',

                    // callback functions
                    onHide: (context, targetEl) => {
                        console.log('element has been dismissed')
                        console.log(targetEl)
                    }
                };
                const instanceOptionsToast = {
                    id: 'toast-top-right',
                    override: true
                };
                const dismiss = new Dismiss($targetEl);
                setTimeout(() => {
                    dismiss.hide();
                }, 2000);
            @endif
            
            @if (session('paymentStatus'))
                const targetElPayment =  document.getElementById('toast-top-right-payment');
                const dismissElPayment = new Dismiss(targetElPayment);
                setTimeout(() => {
                    dismissElPayment.hide();
                }, 2000);
            @endif

            @if (is_null($defaultCardId))
                alert("Add a payment method");
            @endif
        });
    
    </script>
</html>