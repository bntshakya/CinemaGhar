<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Date</title>
    @vite('resources/css/app.css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.4.1/flowbite.min.css" rel="stylesheet" />

</head>
<body class="bg-teal-600">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.4.1/flowbite.min.js"></script>
    <br>
    <p class="self-center block mb-4 text-lg font-medium text-gray-900 dark:text-white">Add Date</p>
    <form class="max-w-sm mx-auto" method="post" action="{{route('movies.insertnewdate')}}">
        @csrf
        <div class="mb-5">
            <label for="select_movie" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select Movie</label>
            <select id="select_movie" name="movie_name"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="name@flowbite.com" required />
                    @foreach ($movie as $mv)
                        <option value="{{$mv->movie_name}}">{{$mv->movie_name}}</option>
                    @endforeach
            </select>
        </div>
        <div class="mb-5">
            <label for="movie_location" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Movie Location</label>
            @foreach ($locations as $location)
                    <div class="flex items-center mb-4">
                        <input id="{{$location}}" type="checkbox" value="{{$location}}" name="location[]"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="{{$location}}" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{$location}}</label>
                    </div>
            @endforeach
        </div>
        <div class="relative max-w-sm">
        <label class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Start Date</label>
        <input id="default-datepicker" type="date" name="s_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date">
        </div>
        <div class="relative max-w-sm">
            <label class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">End Date</label>
            <input id="" type="date" name="e_date"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Select date">
        </div>
        <br>
        <button type="submit" 
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Add Date</button>
    </form>
</body>
</html>