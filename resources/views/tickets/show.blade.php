<!DOCTYPE html>
<html>
<head>
@vite('resources/css/app.css')
    <body background="{{url('storage/popcorn_background.jpg')}}" style="background-size:cover">
<x-navbar></x-navbar>
<title>Ticket Seats</title>
<style>
    /* Centering the ticket seat section */
     .ticket-seats-container {
        background-color: #313035; /* Light grey background */
        padding: 20px; /* Add some padding around the seats for better appearance */
        border-radius: 8px; /* Optional: Adds rounded corners for a softer look */
        box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* Optional: Adds a subtle shadow for depth */
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
        background-image: url('storage/seat_free.png');
        display: inline-block;
        width: 50px;
        /* Increase the size */
        height: 50px;
        /* Increase the size */
        margin: 10px;
        /* Adjust spacing */
        cursor: pointer;
        background-size: cover;
        /* Ensure the image covers the seat area */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        /* Add some depth */
    }

    .selected {
        background-image: url('storage/seat_select.png');
        background-size: cover;
    }

    .disabled {
        background-image: url('storage/seat_booked.png');
        cursor: not-allowed;
        background-size: cover;
    }

    /* .seatingicons{
        display: flex;
        flex-direction: row;
        justify-content: center;
        gap:50px;
    } */

</style>
<style>
    /* Other styles */

    .seatingicons {
        display: inline-flex;
        /* Change to inline-flex to only take up necessary space */
        flex-direction: row;
        justify-content: center;
        align-items: center;
        /* Optional: Center align the items vertically */
        gap: 50px;
        background-color: #313035;
        border-radius: 8px;
        /* Add rounded corners */
    }

    .container{
        padding-left: 38%;
    }

    .icon {
        text-align: center;
        /* Center the text below each icon */
    }

    .icon img {
        display: block;
        /* Ensure images are block level to center them properly */
        margin: 0 auto;
        /* Center the images horizontally */
    }

    .center-container {
    display: flex;
    justify-content: center;
   
}
</style>
</head>
<body>
    <h1 class="mb-4 text-3xl font-extrabold leading-none tracking-tight text-gray-900 md:text-2xl lg:text-5xl dark:text-black ">{{ $movie_name }} </h1>
        <p class="mb-1 text-3xl font-extrabold leading-none tracking-tight text-gray-900 md:text-1xl lg:text-1xl dark:text-black">Available times : 
        @if ($timing)
            @foreach ($movie_ti as $location_id => $movie_time_id)
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-teal-800 border border-gray-200 divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ App\Models\Location::find($location_id)->hall_name }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Movie Times
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($movie_time_id as $mid)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <form method="get" action="{{ route('movies.time') }}">
                                            <button type="submit" name="time" value="{{ $location_id }}"
                                                class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                                {{ App\Models\Movietime::find($mid)->movie_time }}
                                            </button>
                                            <input type="hidden" name="movie_id" value="{{ $movie_id }}" />
                                            <input type="hidden" name="movie_time_id" value="{{ $mid }}" />
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        @endif
        </p>
        <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Details</title>
    @vite('resources/css/app.css')
</head>
<body>
<div class="relative overflow-x-auto">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <tbody>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <center><img src="{{url('storage/' . $movie->moviepath)}}"></center>
            </tr>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Details
                </th>
                <td class="px-6 py-4">
                    {{$movie->details}}
                </td>
            </tr>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Cast
                </th>
                <td class="px-6 py-4">
                    {{$movie->cast}}
                </td>
            </tr>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Rating
                </th>
                <td class="px-6 py-4">
                    {{$movie->rating}}
                </td>
            </tr>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Genre
                </th>
                <td class="px-6 py-4">
                    
                    @foreach ($movie->genre as $genre)
                        {{$genre}}
                    @endforeach
                </td>
            </tr>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Runtime
                </th>
                <td class="px-6 py-4">
                    {{$movie->runtime}}
                </td>
            </tr>
        </tbody>
    </table>
</div>
</table>
</body>
</html>

    <x-comment :comments=$comments>
        {{$movie_name}}
    </x-comment>

    <script>
        function changecolor(el) {
            const form = document.getElementById('ticketForm');
            el.classList.toggle('selected');
            let seatNumber = el.getAttribute('data-seat-number');
            let existingInput = form.querySelector('input[type="hidden"][value="' + seatNumber + '"]');

            if (el.classList.contains('selected')) {
                if (!existingInput) {
                    let input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'selected_seats[]';
                    input.value = seatNumber;
                    form.appendChild(input);
                }
            } else {
                if (existingInput) {
                    form.removeChild(existingInput);
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const buyButton = document.querySelector('#buyButton'); 
            const form = document.getElementById('ticketForm'); 
            const errorMessageContainer = document.createElement('div');
            buyButton.addEventListener('click', function (event) {
                const selectedSeats = form.querySelectorAll('input[type="hidden"][name="selected_seats[]"]');
                if (selectedSeats.length === 0) {                     
                    event.preventDefault();
                    alert('select one seat');  
                } 
            });
        });
    </script>
</body>

</html>
