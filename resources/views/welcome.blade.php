<head>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>

<body background="{{url('storage/popcorn_background.jpg')}}" style="background-size:cover" class="movies-container">
    <x-navbar></x-navbar>
   <div class="container">
    <div class="no-movies">
    </div>
    <div class="showing-now">
        <h1
            class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-6xl dark:text-black ">
            Showing Now</h1>
    
        <div class="flex justify-around flex-wrap">
            @foreach ($movies_now as $movie)
                <form method="get" action="{{route('tickets.show')}}" class='form'>
                    <h6 class="text-3xl font-extrabold dark:text-gray">{{$movie->movie_name}}</h6>
                    <button type="submit" class="hover:bg-gray-50 dark:hover:bg-gray-600 movie-img-button"><img
                            class="h-auto max-w-full hover:bg-gray-50 dark:hover:bg-gray-600"
                            src="{{url('storage/' . $movie->moviepath)}}" alt="image description" width="300"
                            height="500"></button>
                    <input type="hidden" value="{{$movie->movie_name}}" name="movie_name">
                    <input type="hidden" value="{{$movie->timing}}" name="timing">
                    <input type="hidden" value="{{$movie->id}}" name="movie_id">
                </form>
            @endforeach
        </div>
    </div>
    <div class="showing-future">
        <h1
            class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-6xl dark:text-black ">
            Next Change</h1>
    
        <div class="flex justify-around flex-wrap">
            @foreach ($moviesfuture as $movie)
                <form method="get" action="{{route('tickets.show')}}" class='form'>
                    <h6 class="text-3xl font-extrabold dark:text-gray">{{$movie->movie_name}}</h6>
                    <button type="submit" class="hover:bg-gray-50 dark:hover:bg-gray-600 movie-img-button"><img
                            class="h-auto max-w-full hover:bg-gray-50 dark:hover:bg-gray-600"
                            src="{{url('storage/' . $movie->moviepath)}}" alt="image description" width="300"
                            height="500"></button>
                    <input type="hidden" value="{{$movie->movie_name}}" name="movie_name">
                    <input type="hidden" value="{{$movie->timing}}" name="timing">
                    <input type="hidden" value="{{$movie->id}}" name="movie_id">
                    <input type="hidden" value="true" name="not-available">
                </form>
            @endforeach
        </div>
    </div>
    <div class="showing-past">
        <h1
            class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-6xl dark:text-black ">
            Past Shows</h1>
    
        <div class="flex justify-around flex-wrap">
            @foreach ($movies_past as $movie)
                <form method="get" action="{{route('tickets.show')}}" class='form'>
                    <h6 class="text-3xl font-extrabold dark:text-gray">{{$movie->movie_name}}</h6>
                    <button type="submit" class="hover:bg-gray-50 dark:hover:bg-gray-600 movie-img-button"><img
                            class="h-auto max-w-full hover:bg-gray-50 dark:hover:bg-gray-600"
                            src="{{url('storage/' . $movie->moviepath)}}" alt="image description" width="300"
                            height="500"></button>
                    <input type="hidden" value="{{$movie->movie_name}}" name="movie_name">
                    <input type="hidden" value="{{$movie->timing}}" name="timing">
                    <input type="hidden" value="{{$movie->id}}" name="movie_id">
                    <input type="hidden" value="true" name="not-available">
                </form>
            @endforeach
        </div>
    </div>
   
   </div>
    <style>
        .movie-img-button img {
            width: 300px;
            /* Set the width as desired */
            height: 500px;
            /* Set the height as desired */
            object-fit: cover;
            /* This ensures the image covers the area without distorting aspect ratio */
        }

        .form {
            flex: 0 0 33%;
        }
    </style>
</body>