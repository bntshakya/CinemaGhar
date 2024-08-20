<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Screening</title>
    @vite('resources/css/app.css')
</head>
<style>
    .hide{
        display: none;
    }
</style>
<body class="bg-neutral-800">
        <br>
        <p class="self-center block mb-4 text-lg font-medium text-gray-900 dark:text-dark">Add Screening Times</p>
        <form class="max-w-sm mx-auto" method="post" action="{{route('movies.insertnewscreening')}}">
            @csrf
            <div class="mb-5">
                <label for="select_movie" class="block mb-2 text-sm font-medium text-gray-900 dark:text-dark">Select
                    Movie</label>
                <select id="select_movie" name="movie_name"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="name@flowbite.com" required />
                @foreach ($movie as $mv)
                    <option value="{{$mv->movie_name}}">{{$mv->movie_name}}</option>
                @endforeach
                </select>
            </div>
            <div class="mb-5">
                <label for="movie_location" class="block mb-2 text-sm font-medium text-gray-900 dark:text-dark">Movie
                    Location</label>
                @foreach ($locations as $location)
                    <div class="flex items-center mb-4">
                        <input id="{{$location}}" type="checkbox" value="{{$location}}" name="location[]" class="location-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" data-target="div-{{$location}}">
                        <label for="{{$location}}"
                            class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{$location}}</label>
                    </div>

                    <div class="hide" id="div-{{$location}}">
                        <p class="self-center block mb-4 text-lg font-medium text-gray-900 dark:text-dark">Select Halls:</p>
                            @foreach (App\Models\Location::where('location', $location)->pluck('hall_name') as $hallname)
                                <div class="flex items-center mb-4">
                                    <input id='{{$hallname . $location}}' type="checkbox" value="{{$hallname}}" name="{{$location}}[]" class="location-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for='{{$hallname . $location}}' class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{$hallname}}</label>
                                </div> 
                            @endforeach              
                            <label for="time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-dark">Select time:</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <input type="time" id="time" name="time[]" class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"/>
                            </div>
                            <label for="time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-dark">Price:</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <input type="number" name="{{$location}}-pricerate[]" class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"/>
                            </div>    
                    </div>
                @endforeach
            </div>
            <br>
            <button type="submit"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Add
                Date</button>
        </form>
</body>
<script>
document.querySelectorAll('.location-checkbox').forEach(function(checkbox) {
    checkbox.addEventListener('click', function() {
        var targetId = checkbox.getAttribute('data-target');
        var targetDiv = document.getElementById(targetId);
        if (targetDiv) {
            targetDiv.classList.toggle('hide');
        }
    });
});
</script>
</html>