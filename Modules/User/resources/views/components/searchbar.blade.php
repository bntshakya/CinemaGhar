<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<form class="max-w-lg mx-auto" method="get" action="{{route('movies.search')}}" id="searchbar">
    <div class="flex">
        <button id="dropdown-button" data-dropdown-toggle="dropdown"
            class="flex-shrink-0 z-10 inline-flex items-center py-2.5 px-4 text-sm font-medium text-center text-gray-900 bg-gray-100 border border-gray-300 rounded-s-lg hover:bg-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700 dark:text-white dark:border-gray-600"
            type="button">All categories <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 4 4 4-4" />
            </svg></button>
        <form>
            <div id="dropdown"
                class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdown-button">
                    <li>
                        <input id="action-checkbox" type="checkbox" value="action" name="genre[]"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                            checked>
                        <label for="action"
                            class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Action</label>
                    </li>
                    <li>
                        <input id="comedy-checkbox" type="checkbox" value="comedy" name="genre[]"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                            checked>
                        <label for="comedy"
                            class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Comedy</label>
                    </li>
                    <li>
                        <input id="romance-checkbox" type="checkbox" value="romance" name="genre[]"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                            checked>
                        <label for="romance"
                            class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Romance</label>
                    </li>
                    <li>
                        <input id="drama-checkbox" type="checkbox" value="drama" name="genre[]"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                            checked>
                        <label for="drama"
                            class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Drama</label>
                    </li>
                </ul>
            </div>
        </form>
        <div class="relative w-full">
            <input type="search" id="search-dropdown" name="searchbox"
                class="block p-2.5 w-full z-20 text-sm text-gray-900 bg-gray-50 rounded-e-lg border-s-gray-50 border-s-2 border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-s-gray-700  dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-blue-500"
                placeholder="Search Movies..." />
            <button type="submit" id='searchbutton'
                class="absolute top-0 end-0 p-2.5 text-sm font-medium h-full text-white bg-blue-700 rounded-e-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                </svg>
                <span class="sr-only">Search</span>
            </button>
        </div>
    </div>
</form>
<script>
    let el = document.querySelector('#searchbar');
    el.addEventListener('input', (event) => {
        let genredata = [];
        $('input[name="genre[]"]:checked').each(function () {
            genredata.push($(this).val());
        });

        let formdata = {
            'genre': genredata,
            'searchbox': $('#search-dropdown').val()
        };

        $.get({
            url: "{{ route('movies.search') }}",
            data: formdata,
            success: function (response) {

                if (response.movies_now.length > 0 || response.movies_future.length > 0 || response.movies_past.length > 0) {
                    
                    $('.showing-now .flex').empty();
                    $('.showing-future .flex').empty();
                    $('.showing-past .flex').empty();
                    $('.no-movies').empty();

                    // Populate "Showing Now" section
                    response.movies_now.forEach(movie => {
                        $('.showing-now .flex').append(`
                    <form method="get" action="{{route('tickets.show')}}" class='form'>
                    <h6 class="text-3xl font-extrabold dark:text-gray">${movie.movie_name}</h6>
                    <button type="submit" class="hover:bg-gray-50 dark:hover:bg-gray-600 movie-img-button"><img
                            class="h-auto max-w-full hover:bg-gray-50 dark:hover:bg-gray-600"
                            src="storage/${movie.moviepath}" alt="image description" width="300"
                            height="500"></button>
                    <input type="hidden" value="${movie.movie_name}" name="movie_name">
                    <input type="hidden" value="${movie.timing}" name="timing">
                    <input type="hidden" value="${movie.id}" name="movie_id">
                    <input type="hidden" value="true" name="not-available">
                </form>
                `);
                    });

                    // Populate "Next Change" section
                    response.movies_future.forEach(movie => {
                        $('.showing-future .flex').append(`
                    <form method="get" action="{{route('tickets.show')}}" class='form'>
                        <h6 class="text-3xl font-extrabold dark:text-gray">${movie.movie_name}</h6>
                        <button type="submit" class="hover:bg-gray-50 dark:hover:bg-gray-600 movie-img-button">
                            <img class="h-auto max-w-full hover:bg-gray-50 dark:hover:bg-gray-600" src="storage/${movie.moviepath}" alt="image description" width="300" height="500">
                        </button>
                        <input type="hidden" value="${movie.movie_name}" name="movie_name">
                        <input type="hidden" value="${movie.timing}" name="timing">
                        <input type="hidden" value="${movie.id}" name="movie_id">
                        <input type="hidden" value="true" name="not-available">
                    </form>
                `);
                    });

                    // Populate "Past Shows" section
                    response.movies_past.forEach(movie => {
                        $('.showing-past .flex').append(`
                    <form method="get" action="{{route('tickets.show')}}" class='form'>
                    <h6 class="text-3xl font-extrabold dark:text-gray">${movie.movie_name}</h6>
                    <button type="submit" class="hover:bg-gray-50 dark:hover:bg-gray-600 movie-img-button"><img
                            class="h-auto max-w-full hover:bg-gray-50 dark:hover:bg-gray-600"
                            src="storage/${movie.moviepath}" alt="image description" width="300"
                            height="500"></button>
                    <input type="hidden" value="${movie.movie_name}" name="movie_name">
                    <input type="hidden" value="${movie.timing}" name="timing">
                    <input type="hidden" value="${movie.id}" name="movie_id">
                    <input type="hidden" value="true" name="not-available">
                </form>
                `);
                    });
                } else {
                    $('.showing-now .flex').empty();
                    $('.showing-future .flex').empty();
                    $('.showing-past .flex').empty();
                    $('.no-movies').empty();
                    $('.no-movies').append(`
                        <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
                            <div class="mx-auto max-w-screen-sm text-center">
                                <p class="mb-4 text-3xl font-light text-amber-200 dark:text-white-400 font-bold">Sorry, we can't find that movie. You'll find lots to explore on the home page. </p>
                            </div>   
                        </div>
                    `)
                }

            },
            error: function (error) {
                // Handle any errors
                alert('An error occurred.');
            }
        });
    });
</script>