<?php

namespace Modules\Admin\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\movie;
use App\Models\Moviedate;
use App\Models\MovieScreeningCost;
use App\Models\Movietime;
use App\Models\ticket;
use App\Models\TicketBooking;
use App\Models\TicketRate;
use Carbon\Carbon;
use CustomerCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Auth;
use DB;
// use App\Models\CustomerCard;

class MovieController extends Controller
{
    public function details(Request $request)
    {
        $movie_id = $request['movie_id'];
        $movie = movie::find($movie_id);
        return view('movies.details', ['movie' => $movie]);
    }


    public function show()
    {
        // Set default location if not already set
        if (!session()->has('location')) {
            // session(['location' => 'Kathmandu']);
            session()->put('location', 'Kathmandu');
        }

        $location = session()->get('location');
        $subQuery = Moviedate::select(DB::raw('MAX(id)'))
            ->where('movie_location', $location)
            ->groupBy('movie_id');
            // dd($subQuery->get());

        // dd($subQuery->get());
        // Main query to get the moviedates based on the IDs from the subquery
        $moviedates = Moviedate::whereIn('id', $subQuery)
            ->where('movie_location', $location)
            ->get();
        // dd($moviedates);
        $movielist_now = [];
        $movielist_future = [];
        $movielist_past = [];
        $today_date = Carbon::today();
        foreach ($moviedates as $moviedate) {
            $movie = $moviedate->movie;
            if ($today_date < $moviedate->startdate) {
                array_push($movielist_future, $movie);
            } elseif ($today_date > $moviedate->enddate) {
                array_push($movielist_past, $movie);
            } else {
                array_push($movielist_now, $movie);
            }
        }
        return view('welcome', ['movies_now' => $movielist_now, 'movies_past' => $movielist_past, 'moviesfuture' => $movielist_future]);
    }


    public function add()
    {
        return view('addmovie');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'movie_name' => 'required|string|',
            'movie_poster' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'genre' => 'required|array',
            'movie_details' => 'required|string',
            'movie_cast' => 'required|string',
            'movie_rating' => 'required|string',
            'movie_runtime' => 'required|string',
        ]);
        $movie_name = $request->movie_name;
        $movie_time = $request->movie_time;

        if ($request->hasFile('movie_poster')) {
            $file = $request->file('movie_poster');
            $extension = $file->getClientOriginalExtension();
            $destination = 'public';
            $newfilename = $movie_name . '.' . $extension;
            $file->storeAs($destination, $newfilename);
        } else {
            $newfilename = 'default.jpg';
        }
        $movie = movie::create(['movie_name' => $request['movie_name'], 'timing' => Carbon::now(), 'moviepath' => $newfilename, 'genre' => $request['genre'], 'details' => $request['movie_details'], 'cast' => $request['movie_cast'], 'rating' => $request['movie_rating'], 'runtime' => $request['movie_runtime']]);
        $movie_id = $movie->id;
        $screeningCost = $request->movieScreeningCost;
        // Now you can use $movie_id for the next operation
        MovieScreeningCost::create([
            'movie_id' => $movie_id,
            'screening_cost' => $screeningCost // Assuming $screeningCost is defined and valid
        ]); 
        return redirect()->route('movie.show')->with('success', 'movie added');
    }
    public function edit(Request $request)
    {
        $movie_id = $request['id'];
        // Validate incoming request data
        $request->validate([
            'movie_name' => 'required|string',
            'movie_poster' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'genre' => 'required|array',
            'movie_details' => 'required|string',
            'movie_cast' => 'required|string',
            'movie_rating' => 'required|string',
            'movie_runtime' => 'required|string',
        ]);

        // Retrieve the movie id based on the movie name
        $movie_name = $request->movie_name;
        // Check if the movie exists
        if (is_null(movie::find($movie_id))) {
            // dd('movies not found');
            return redirect()->route('admin.editMovies')->with('error', 'Movie not found.');
        }

        // Handle file upload for movie poster
        if ($request->hasFile('movie_poster')) {
            $file = $request->file('movie_poster');
            $extension = $file->getClientOriginalExtension();
            $destination = 'public';
            $newfilename = $movie_name . '.' . $extension;
            $file->storeAs($destination, $newfilename);
        } else {
            $newfilename = 'default.jpg';
        }

        // Prepare data for update
        $updateData = [
            'movie_name' => $request->movie_name,
            'moviepath' => $newfilename,
            'genre' => json_encode($request->genre),
            'details' => $request->movie_details,
            'cast' => $request->movie_cast,
            'rating' => $request->movie_rating,
            'runtime' => $request->movie_runtime,
        ];
        movie::where('id', $movie_id)->update($updateData);
        $newScreeningCost = $request->movieScreeningCost;
        // dd($newScreeningCost);
        MovieScreeningCost::updateOrInsert(
            ['movie_id' => $movie_id], // Condition to check
            ['screening_cost' => $newScreeningCost] // Values to update or insert
        );
        return redirect()->route('admin.editMovies')->with('success', 'Movie updated successfully.');
    }


    public function search(Request $request)
    {
        
        $genres = $request['genre'];
        if ($genres) {
            // Initialize the query
            $query = movie::query();

            // Add whereJsonContains clauses for each genre
            foreach ($genres as $genre) {
                $query->orWhereJsonContains('genre', $genre);
            }

            // Execute the query to get the movies
            $movies = $query->get();
            $searchquery = $request['searchbox'];
            $movielist = [];
            foreach ($movies as $movie) {
                array_push($movielist, $movie->movie_name);
            }
            // dd($movielist);
            if ($searchquery) {
                $pattern = '/' . preg_quote($searchquery, '/') . '/i'; // Create a regex pattern, case insensitive
                $updatedquery = preg_grep($pattern, $movielist);
            } else {
                $updatedquery = $movielist;
            }
            // \Log::channel('custom')->info($updatedquery);
            $updatedmovies = movie::whereIn('movie_name', $updatedquery)->get();
            // \Log::channel('custom')->info($updatedmovies);
            $today_date = Carbon::today();
            $now = [];
            $future = [];
            $past = [];
            $location = session('location');
            
                
            foreach ($updatedmovies as $movie) {
                $mv= Moviedate::where('movie_id', $movie->id)->where('movie_location', $location)->latest()->first();
                
                // $mv = $mv->newCollection();
                // \Log::channel('custom')->info($mv);
                if (!is_null($mv)) {
                    if ($today_date < $mv->startdate) {
                        array_push($future, $movie->id);
                    } elseif ($today_date > $mv->enddate) {
                        array_push($past, $movie->id);
                    } else {
                        array_push($now, $movie->id);
                    }
                }
            }
            $movies_now = movie::whereIn('id', $now)->get();
            $movies_future = movie::whereIn('id', $future)->get();
            $movies_past = movie::whereIn('id', $past)->get();

            if ($request->ajax()) {
                return response()->json([
                    'movies_now' => $movies_now,
                    'movies_future' => $movies_future,
                    'movies_past' => $movies_past
                ]);
            }
            if ($movies_now->isNotEmpty() or $movies_future->isNotEmpty() or $movies_past->isNotEmpty()) {
                return view('welcome', ['movies_now' => $movies_now, 'moviesfuture' => $movies_future, 'movies_past' => $movies_past]);
            }
        }
        return view('movies.notfound');
    }

    public function location(Request $request)
    {
        $location = $request['location'];
        // session(['location'=>$location]);
        $request->session()->put('location', $location);
        $userLocation = $request->session()->get('location');
        $moviedates = Moviedate::where('movie_location', $location)->get();
        $movielist = [];
        foreach ($moviedates as $moviedate) {
            $movie = $moviedate->movie;
            // Access the related movie for each Moviedate
            array_push($movielist, $movie);
        }
        // dd($movielist);
        return redirect()->route('movie.show');


    }

    public function time(Request $request)
    {
        $movie_time_id = $request['movie_time_id'];
        $movietime_id = (int) $request['time'];
        $movietime = Movietime::find((int) $movie_time_id);
        $hall = Location::find($movietime_id);
        $movie_id = $request['movie_id'];
        $movie = movie::find($movie_id);
        $tickets = ticket::where('movie_name', $movie->movie_name)->where('location_id', $request['time'])->where('movie_time_id', $movie_time_id)->pluck('ticket_seat')->toArray();
        $ticket_bookings = (TicketBooking::where('movie_name',$movie->movie_name)->where('location_id',$request['time'])->where('movie_time_id',$movie_time_id)->pluck('selected_seats')->toArray());
        if(!is_null($ticket_bookings)){
            $result = [];
            foreach ($ticket_bookings as $item) {
                $item = trim($item, '[]'); // Remove the square brackets
                $itemArray = explode(',', $item); // Split the string into an array
                foreach ($itemArray as $value) {
                    $result[] = (int) trim($value, '"'); // Convert the string to integer and add to the result array
                }
            }
            $tickets = array_merge($result, $tickets);
        }
        $ticketrate = TicketRate::where('movie_time_id', $movie_time_id)->first()->ticket_rate;
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        $customer = Auth::user();
        $customerId = $customer->StripeCustomerId;
        $paymentMethods = $stripe->paymentMethods->all([
            'customer' => $customerId,
            'type' => 'card',
        ]);
        $defaultCardId = \App\Models\CustomerCard::where('CustomerId',$customerId)->where('isDefault',true)->first('CardId');
        $currentTime = Carbon::now('Asia/Kathmandu'); // Keep as Carbon instance
        // dd($movietime);
        $movietimeCarbon = Carbon::parse($movietime->movie_time)->setTimezone('Asia/Kathmandu');
        $timeGap = $currentTime->diffInHours($movietimeCarbon);
        $bookableFlag = true;
        if ($timeGap < 2){
            $bookableFlag = false;
        };
        // dd($timeGap,$movietimeCarbon,$currentTime);
        return view('movies.selecthall', ['hall' => $hall, 'movie' => $movie, 'tickets' => $tickets, 'movietime' => $request['time'], 'movie_time_id' => $movie_time_id, 'movietime_' => $movietime, 'ticketrate' => $ticketrate, 'paymentMethods' => $paymentMethods,'defaultCardId'=>$defaultCardId->CardId,'bookableFlag'=>$bookableFlag]);
    }

    public function adddate(Request $request)
    {
        $movie = movie::all();
        $location = Location::pluck('location')->unique();
        return view('admin::admin.adddate', ['movie' => $movie, 'locations' => $location]);
    }

    public function insertnewdate(Request $request)
    {
        foreach ($request['location'] as $location) {
            $id = movie::where('movie_name', $request['movie_name'])->value('id');
            // $locations = Location::where('location',$location)->pluck('id');
            Moviedate::create(['movie_id' => $id, 'movie_location' => $location, 'startdate' => $request['s_date'], 'enddate' => $request['e_date']]);
            // Moviedate::create([$id, $location,$request['s_date'],$request['e_date']]);
        }
        return redirect()->route('movies.add');

    }

    public function addscreening()
    {
        $movie = movie::all();
        $locations = Location::pluck('location')->unique();
        return view('admin::admin.addscreening', ['movie' => $movie, 'locations' => $locations]);
    }

    public function insertnewscreening(Request $request)
    {
        $movie_id = movie::where('movie_name', $request['movie_name'])->pluck('id')[0];

        // Filter the time array and reindex the keys
        $filtered_times = array_values(array_filter($request['time']));
        // dd($filtered_times); // This will show the reindexed array

        foreach ($request['location'] as $k => $location) {
            $movie_time = $filtered_times[$k] ?? null; // Use reindexed $filtered_times array

            if (!is_null($movie_time)) {
                foreach ($request[$location] as $hallname) {
                    $location_id = Location::where('location', $location)->where('hall_name', $hallname)->pluck('id')[0];
                    $movietm_id = Movietime::create([
                        'movie_id' => $movie_id,
                        'location_id' => $location_id,
                        'movie_time' => $movie_time
                    ]);
                    TicketRate::create([
                        'movie_time_id' => $movietm_id->id,
                        'ticket_rate' => $request[$location . '-pricerate'][0]
                    ]);
                }
            }
        }

        return redirect()->route('movies.add');
    }


    public function delete(Request $request){
        $movieId= $request['id'];
        movie::find($movieId)->delete();
        return redirect()->route('admin.movieslist');
    }
}