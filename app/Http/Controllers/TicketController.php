<?php

namespace App\Http\Controllers;

use App\Models\comment;
use App\Models\Location;
use App\Models\movie;
use App\Models\Moviedate;
use App\Models\MovieScreeningCost;
use App\Models\MoviesSummary;
use App\Models\Movietime;
use App\Models\Revenue;
use App\Models\RevenueSummary;
use App\Models\ticket;
use App\Models\TicketBooking;
use App\Models\TicketRate;
use CustomerCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use DB;
use Number;
use Auth;

class TicketController extends Controller
{
    //
    public function show(Request $request)
    {

        $data = session()->all();
        $movie_name = $request['movie_name'];
        $movie_time = $request['timing'];
        $movie_id = $request['movie_id'];
        $movie = movie::find($movie_id);
        $tickets = ticket::where('movie_name', $movie_name)->where('movie_time', $movie_time)->pluck('ticket_seat')->toArray();
        $comments = comment::all();
        $location = session('location');
        $location_id = Location::where('location', $location)->pluck('id');
        $startDate = Moviedate::where('movie_id',$movie_id)->where('movie_location',$location)->orderByDesc('startdate')->first();
        $endDate = Moviedate::where('movie_id', $movie_id)->where('movie_location', $location)->orderByDesc('enddate')->first();
        
        $movie_ti = Movietime::select('movie_time', 'location_id', 'id')->where('movie_id', $movie_id)->whereIn('location_id', $location_id)->where('created_at','>=',$startDate->startdate)->where('created_at','<=',$endDate->enddate)->groupBy('location_id', 'movie_time', 'id')->get();
        // dd($movie_ti);
        $grouped_movie_times = [];

        // Group movie times by location_id
        foreach ($movie_ti as $movie_time) {
            $grouped_movie_times[$movie_time->location_id][] = $movie_time->id;
        }
        // dd($grouped_movie_times);
        $moviedate = Moviedate::where('movie_id', $movie_id)->where('movie_location',$location)->get()->last();
        // dd($moviedate);
        $today_date = Carbon::today();
        $movie_times = Movietime::select('movietimes.movie_time')->where('movie_id', $movie_id)->whereIn('location_id', (Location::where('location', $location)->pluck('id')))->join('locations', 'movietimes.location_id', '=', 'locations.id')->groupBy('locations.location', 'locations.hall_name', 'movietimes.location_id', 'movietimes.movie_time')->get();
        // dd($movie_times);
        if ($today_date < $moviedate->startdate) {
            $movie_time = [];
        } elseif ($today_date > $moviedate->enddate) {
            $movie_time = [];
        }
        $location_ids = Location::where('location', $location)->pluck('id');
        return view('tickets.show', ['movie_name' => $movie_name, 'timing' => $movie_time, 'tickets' => $tickets, 'comments' => $comments, 'movie_id' => $movie_id, 'movie_times' => $movie_time, 'movie' => $movie, 'movie_ti' => $grouped_movie_times]);
    }

    public function buy(Request $request)
    {
        $selected_seats = array_filter($request->input('selected_seats'));
        $movie_time_id = (int) ($request['movie_time_id']);
        $ticket_rate = TicketRate::where('movie_time_id', $movie_time_id)->first();
        require_once '../vendor/autoload.php';
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        $metadata = [
            'email' => session('email'),
            'movie_name' => $request['movie_name'],
            'movie_time' => $request['timing'],
            'location_id' => (int) $request['location_id'],
            'movie_time_new' => $request['movie_time_new'],
            'movie_time_id' => $movie_time_id,
            'selected_seats' => json_encode($selected_seats), // Encode the array as a JSON string
        ];

        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Tickets',
                        ],
                        'unit_amount' => $ticket_rate->ticket_rate * 100,
                    ],
                    'quantity' => count($selected_seats),
                ],
            ],
            'mode' => 'payment',
            'success_url' => route('tickets.view'),
            'cancel_url' => route('tickets.show'),
            'payment_intent_data' => [
                'metadata' => $metadata,
            ], // Add the metadata to the session
        ]);

        return redirect($checkout_session->url);
    }

    // public function stripeEmbed(Request $request){

    //         require_once '../vendor/autoload.php';
//         $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
//         header('Content-Type: application/json');
//         $PRICE_ID = 'pr_1';
//         $YOUR_DOMAIN = 'http://localhost:8000';
//         $checkout_session = $stripe->checkout->sessions->create([
//             'ui_mode' => 'embedded',
//             'line_items' => [
//                 [
//                     'price_data' => [
//                         'currency' => 'usd',
//                     ],

    //                     # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
//                     'quantity' => 1,
//                 ]
//             ],
//             'mode' => 'payment',
//             'return_url' => $YOUR_DOMAIN . '/return.html?session_id={CHECKOUT_SESSION_ID}',
//         ]);
// }
    public function stripeEmbed(Request $request)
    {
        require_once '../vendor/autoload.php';
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        header('Content-Type: application/json');

        // Define the amount and currency
        // $amount = 1000; // Amount in cents (1000 cents = 10 USD)
        // $currency = 'usd';

        // Create a new price object
        // $price = $stripe->prices->create([
        //     'unit_amount' => $amount,
        //     'currency' => $currency,
        //     'product_data' => [
        //         'name' => 'Tickets',
        //     ],
        // ]);

        $YOUR_DOMAIN = 'http://localhost:8000';

        $checkout_session = $stripe->checkout->sessions->create([
            'ui_mode' => 'embedded',
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Tickets',
                        ],
                        'unit_amount' => 10000,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'return_url' => $YOUR_DOMAIN . '/return/session_id={CHECKOUT_SESSION_ID}',
        ]);
        echo json_encode(array('clientSecret' => $checkout_session->client_secret));
    }


    public function view()
    {
        $email = session('email');

        // First, get all tickets for the user, ordered by created_at
        $allTickets = ticket::where('email', $email)
            ->orderBy('created_at', 'desc')
            ->get();

        // Group tickets by created_at
        $groupedTickets = $allTickets->groupBy(function ($ticket) {
            return $ticket->created_at->format('Y-m-d H:i:s');
        });

        // Paginate the grouped tickets
        $perPage = 10; // Number of ticket groups per page
        $page = request()->get('page', 1);
        $pagedData = $groupedTickets->forPage($page, $perPage);

        // Create a new paginator instance
        $ticketsPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $pagedData,
            $groupedTickets->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('tickets.view', ['tickets' => $ticketsPaginated]);
    }

    public function test(Request $request)
    {
        return view('checkout',['data'=>$request->all()]);
    }

    public function embedBuy(Request $request){
        $selected_seats = array_filter($request->input('selected_seats'));
        $movie_time_id = (int) ($request['movie_time_id']);
        $ticket_rate = TicketRate::where('movie_time_id', $movie_time_id)->first();

        require_once '../vendor/autoload.php';
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        header('Content-Type: application/json');
        $metadata = [
            'email' => session('email'),
            'movie_name' => $request['movie_name'],
            'movie_time' => $request['timing'],
            'location_id' => (int) $request['location_id'],
            'movie_time_new' => $request['movie_time_new'],
            'movie_time_id' => $movie_time_id,
            'selected_seats' => json_encode($selected_seats), // Encode the array as a JSON string
        ];

        // Define the amount and currency
        // $amount = 1000; // Amount in cents (1000 cents = 10 USD)
        // $currency = 'usd';

        // Create a new price object
        // $price = $stripe->prices->create([
        //     'unit_amount' => $amount,
        //     'currency' => $currency,
        //     'product_data' => [
        //         'name' => 'Your Product Name',
        //     ],
        // ]);

        $YOUR_DOMAIN = 'http://localhost:8000';

        $checkout_session = $stripe->checkout->sessions->create([
            'ui_mode' => 'embedded',
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Tickets',
                        ],
                        'unit_amount' => $ticket_rate->ticket_rate * 100,
                    ],
                    'quantity' => count($selected_seats),
                ],
            ],
            'mode' => 'payment',
            
                'metadata' => $metadata,
            
            'return_url' =>route('tickets.view'),
        ]);
        \Log::channel('custom')->info('@ embed buy');
        echo json_encode(array('clientSecret' => $checkout_session->client_secret));
    }

    public function apiBuy(){

    }

    public function test2(){
        require_once '../vendor/autoload.php';
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        $payment_intent = $stripe->paymentIntents->create([
            'amount'=>1999,
            'currency'=>'usd',
        ]);
        echo json_encode(array('clientSecret' => $payment_intent->client_secret));

    }

    public function apiPurchase(Request $request){
        $selected_seats = array_filter($request->input('selected_seats'));
        $movie_time_id = (int) ($request['movie_time_id']);
        $ticket_rate = TicketRate::where('movie_time_id', $movie_time_id)->first();
        $customerId = Auth::user()->StripeCustomerId;
        $paymentId = \App\Models\CustomerCard::where('CustomerId',$customerId)->where('isDefault',true)->first('CardId');
        $total = $ticket_rate->ticket_rate * count($selected_seats) * 100;

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        try {
            \Stripe\PaymentIntent::create([
                'amount' => $total,
                'currency' => 'usd',
                // In the latest version of the API, specifying the `automatic_payment_methods` parameter is optional because Stripe enables its functionality by default.
                'automatic_payment_methods' => ['enabled' => true],
                'customer' => $customerId,
                'payment_method' => $paymentId->CardId,
                'return_url' => 'https://google.com',
                'off_session' => true,
                'confirm' => true,
            ]);
            $email= session('email');
            $movie_name = $request['movie_name'];
            $movie_time = $request['timing'];
            $location_id = (int) $request['location_id'];
            $movie_time_new = $request['movie_time_new'];
            foreach ($selected_seats as $seat) {
                ticket::create([
                    'movie_name' => $movie_name,
                    'movie_time' => $movie_time,
                    'ticket_seat' => $seat,
                    'email' => $email,
                    'location_id' => $location_id,
                    'movie_time_new' => $movie_time_new,
                    'movie_time_id' => $movie_time_id,
                ]);
            }
            $movie_id = movie::where('movie_name', $movie_name)->value('id');
            $revenue = $ticket_rate->ticket_rate * count($selected_seats);
            $ticket_rate_id = $ticket_rate->id;
            $movieScreeningCost = MovieScreeningCost::where('movie_id', $movie_id)->first();
            $date = Carbon::today();
            $existing_revenue_record = Revenue::where('movie_id', $movie_id)->where('date', $date)->where('ticket_rate_id', $ticket_rate_id)->first();
            $existing_rev_summary = RevenueSummary::where('movie_id', $movie_id)->where('date', $date)->first();

            if (is_null($existing_revenue_record)) {
                Revenue::create(['movie_id' => $movie_id, 'revenue' => $revenue, 'date' => $date, 'ticket_rate_id' => $ticket_rate_id]);
            } else {
                $updated_revenue = $existing_revenue_record->revenue + $revenue;
                $existing_revenue_record->update(['revenue' => $updated_revenue]);
            }

            if (is_null($existing_rev_summary)) {
                RevenueSummary::create(['movie_id' => $movie_id, 'movie_name' => $movie_name, 'revenue' => $revenue, 'date' => $date]);
            } else {
                $updated_rev_summary = $existing_rev_summary->revenue + $revenue;
                $existing_rev_summary->update(['revenue' => $updated_rev_summary]);
            }

            $exist_movie = MoviesSummary::where('movie_id', $movie_id)->where('movieName', $movie_name)->first();
            if (is_null($exist_movie)) {
                MoviesSummary::create([
                    'movie_id' => $movie_id,
                    'movieName' => $movie_name,
                    'Revenue' => $revenue,
                    'isprofitable' => $movieScreeningCost->screening_cost <= $revenue,
                ]);
            } else {
                $newrev = $exist_movie->Revenue + $revenue;
                $exist_movie->update(['Revenue' => $newrev]);
                if ($newrev > $movieScreeningCost->screening_cost) {
                    $exist_movie->update(['isprofitable' => true]);
                }
            }
        } catch (\Stripe\Exception\CardException $e) {
            // Error code will be authentication_required if authentication is needed
            echo 'Error code is:' . $e->getError()->code;
            $payment_intent_id = $e->getError()->payment_intent->id;
            $payment_intent = \Stripe\PaymentIntent::retrieve($payment_intent_id);
        }
        return redirect()->route('tickets.view');
    }


    public function PaymentModalPay(Request $request)
    {
        
        $selected_seats = gettype(($request->input('selected_seats')))==='array'?($request->input('selected_seats')):json_decode($request->input('selected_seats'));
        $movie_time_id = (int) ($request['movie_time_id']);
        $ticket_rate = TicketRate::where('movie_time_id', $movie_time_id)->first();
        $customerId = Auth::user()->StripeCustomerId;
        $paymentId = $request->input('paymentMethod');
        $total = $ticket_rate->ticket_rate * count($selected_seats) * 100;

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        try {
            \Stripe\PaymentIntent::create([
                'amount' => $total,
                'currency' => 'usd',
                // In the latest version of the API, specifying the `automatic_payment_methods` parameter is optional because Stripe enables its functionality by default.
                'automatic_payment_methods' => ['enabled' => true],
                'customer' => $customerId,
                'payment_method' => $paymentId,
                'return_url' => 'https://google.com',
                'off_session' => true,
                'confirm' => true,
            ]);
            $email = session('email');
            $movie_name = $request['movie_name'];
            $movie_time = $request['timing'];
            $location_id = (int) $request['location_id'];
            $movie_time_new = $request['movie_time_new'];
            foreach ($selected_seats as $seat) {
                ticket::create([
                    'movie_name' => $movie_name,
                    'movie_time' => $movie_time,
                    'ticket_seat' => $seat,
                    'email' => $email,
                    'location_id' => $location_id,
                    'movie_time_new' => $movie_time_new,
                    'movie_time_id' => $movie_time_id,
                ]);
            }
            $movie_id = movie::where('movie_name', $movie_name)->value('id');
            $revenue = $ticket_rate->ticket_rate * count($selected_seats);
            $ticket_rate_id = $ticket_rate->id;
            $movieScreeningCost = MovieScreeningCost::where('movie_id', $movie_id)->first();
            $date = Carbon::today();
            $existing_revenue_record = Revenue::where('movie_id', $movie_id)->where('date', $date)->where('ticket_rate_id', $ticket_rate_id)->first();
            $existing_rev_summary = RevenueSummary::where('movie_id', $movie_id)->where('date', $date)->first();

            if (is_null($existing_revenue_record)) {
                Revenue::create(['movie_id' => $movie_id, 'revenue' => $revenue, 'date' => $date, 'ticket_rate_id' => $ticket_rate_id]);
            } else {
                $updated_revenue = $existing_revenue_record->revenue + $revenue;
                $existing_revenue_record->update(['revenue' => $updated_revenue]);
            }

            if (is_null($existing_rev_summary)) {
                RevenueSummary::create(['movie_id' => $movie_id, 'movie_name' => $movie_name, 'revenue' => $revenue, 'date' => $date]);
            } else {
                $updated_rev_summary = $existing_rev_summary->revenue + $revenue;
                $existing_rev_summary->update(['revenue' => $updated_rev_summary]);
            }

            $exist_movie = MoviesSummary::where('movie_id', $movie_id)->where('movieName', $movie_name)->first();
            if (is_null($exist_movie)) {
                MoviesSummary::create([
                    'movie_id' => $movie_id,
                    'movieName' => $movie_name,
                    'Revenue' => $revenue,
                    'isprofitable' => $movieScreeningCost->screening_cost <= $revenue,
                ]);
            } else {
                $newrev = $exist_movie->Revenue + $revenue;
                $exist_movie->update(['Revenue' => $newrev]);
                if ($newrev > $movieScreeningCost->screening_cost) {
                    $exist_movie->update(['isprofitable' => true]);
                }
            }
            $id = $request->input('id');
            if (!is_null($id)){
                TicketBooking::find($id)->update(['paymentDone'=>true]);
            }
  

        } catch (\Stripe\Exception\CardException $e) {
            // Error code will be authentication_required if authentication is needed
            echo 'Error code is:' . $e->getError()->code;
            $payment_intent_id = $e->getError()->payment_intent->id;
            $payment_intent = \Stripe\PaymentIntent::retrieve($payment_intent_id);
        }

        return redirect()->back()->with(['paymentStatus'=>'payment Success']);
    }

    public function bookTickets(Request $request){
        $movieName = $request->input('movie_name');
        $locationId = $request->input('location_id');
        $movieTimeId = $request->input('movie_time_id');
        $email = session('email');
        $seats = json_encode($request->input('selected_seats'));
        $bookInstance = TicketBooking::create(['movie_name'=>$movieName,'location_id'=>$locationId,'movie_time_id'=>$movieTimeId,'email'=>$email,'selected_seats'=>$seats]);
        if($bookInstance){
            $request->session()->flash('bookStatus', 'Task was successful!');
            return response()->json(['bookStatus'=>'created book tickets instance']);
        }
        return response()->json(['bookStatus'=>'failed to create book tickets instance']);
    }

    public function viewBookedTickets(){
        $email = session('email');
        $tickets = TicketBooking::where('email',$email)->orderByDesc('created_at')->paginate(4);
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        $customer = Auth::user();
        $customerId = $customer->StripeCustomerId;
        $paymentMethods = $stripe->paymentMethods->all([
            'customer' => $customerId,
            'type' => 'card',
        ]);
        $defaultCardId = \App\Models\CustomerCard::where('CustomerId', $customerId)->where('isDefault', true)->first('CardId');
        return view('user::BookingTable',['tickets'=>$tickets,'paymentMethods'=>$paymentMethods,'defaultCardId'=>$defaultCardId->CardId]);
    }
}


