@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Add Movie</h1>
@stop

@section('content')
        <body class="bg-gray-100 dark:bg-gray-900">
                <form method="get" action="{{route('movies.adddate')}}">
                    <button type="submit"
                        class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium
                                    rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Add Movie
                        Date</button>
                </form>
                <form method="get" action="{{route('movies.addscreening')}}">
                    <button type="submit"
                        class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium
                                                    rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Add
                        Screening Times</button>
                </form>

    <div class="flex justify-center items-center min-h-screen">
        

        <form class="max-w-md w-full mx-auto bg-dark dark:bg-gray-800 p-8 rounded-lg shadow-md" enctype="multipart/form-data" method="post" action="{{route('movies.upload')}}">
            @csrf
            <h2 class="text-2xl font-bold mb-8 text-gray-800 dark:text-white">Add a New Movie</h2>
            <div class="mb-5">
                <label for="movie_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Movie Name</label>
                <input type="text" id="movie_name" name="movie_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Enter movie name" required />
            </div>
            <div class="mb-5">
                <label for="movie_poster" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Movie Poster</label>
                <input type="file" id="movie_poster" name="movie_poster"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    />
            </div>
            <label for="movie_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Genre</label>
            <div class="mb-5 genre-container">
                <input type="checkbox" name="genre[]" value="romance"><label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Romance</label>
                <input type="checkbox" name="genre[]" value="comedy"><label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Comedy</label>
                <input type="checkbox" name="genre[]" value="action"><label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Action</label>
                <input type="checkbox" name="genre[]" value="horror"><label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Horror</label>
            </div>
            <div class="mb-5">
                <label for="movie_details" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Movie Details</label>
                <textarea id="movie_details" name="movie_details"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Enter movie details" required /></textarea>
            </div>
            <div class="mb-5">
                <label for="movie_cast" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Movie cast</label>
                <input type="text" id="movie_cast" name="movie_cast"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Enter movie cast" required />
            </div>
            <div class="mb-5">
                <label for="movie_rating" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Movie Rating</label>
                <input type="text" id="movie_rating" name="movie_rating"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Enter movie rating" required />
            </div>
            <div class="mb-5">
                <label for="movie_runtime" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Movie Runtime</label>
                <input type="text" id="movie_runtime" name="movie_runtime"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Enter movie runtime" required />
            </div>
            <div class="mb-5">
                <label for="movieScreeningCost" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Movie Screening Cost</label>
                <input type="number" id="movieScreeningCost" name="movieScreeningCost"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Enter movie screening cost" required />
            </div>
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
        </form>
    </div>
</body>
@endsection

@section('meta_tags')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Movie</title>
    @vite('resources/css/app.css')
@endsection

@section('css')
<style>
    .genre-container {
        display: flex;
        flex-direction: row;
        justify-content: space-evenly;
    }
</style>
@endsection



