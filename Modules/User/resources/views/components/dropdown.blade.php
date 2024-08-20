@foreach ($location_names as $location_name)
    <li>
        <form method="get" action="{{route('movies.location')}}">
            <button type="submit" value="{{$location_name}}" name="location"
                class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-full text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">{{$location_name}}</button>
        </form>
    </li>
@endforeach
