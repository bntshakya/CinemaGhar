<?php

namespace App\Http\Controllers;

use App\DataTables\UsersDataTable;
use App\Jobs\CountToOneMillionJob;
use App\Models\CustomerCard;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\SetupIntent;
use Auth;
use Carbon\Carbon;
use App\Models\Movietime;
use App\Models\TicketBooking;
use App\Models\Location;
use Mail;
use App\Mail\BookingExpireMail;


class UserController extends Controller
{
    //
    public function index(UsersDataTable $dataTable)
    {
        return $dataTable->render('users.index');
    }

    public function submitDetails(Request $request){
        
        try {
            $user = \Auth::user();
            $stripeCustomerId = $user->StripeCustomerId;
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            $intent = SetupIntent::create([
                'confirm' => true,
                'customer' => $stripeCustomerId, // Assuming the customer ID is stored in a cookie
                'automatic_payment_methods' => ['enabled' => true],
                'confirmation_token' => $request->input('confirmationTokenId')
            ]);
            // \Log::channel('custom')->info($intent);
            // \Log::channel('custom')->info($request->input('confirmationTokenId'));
            $cardId = $intent->payment_method;
            CustomerCard::create(['CustomerId'=>$stripeCustomerId,'CardId'=>$cardId]);
            return response()->json([
                'client_secret' => $intent->client_secret,
                'status' => $intent->status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function count(){
        $currentTime = Carbon::now('Asia/Kathmandu');
        $ticketIdCollection = Movietime::where('movie_time', '<=', $currentTime->addHours(0)->format("H:i:s"))->pluck('id');
        \Log::channel('custom')->info($ticketIdCollection);
        $tickets = TicketBooking::where('paymentDone', false)->get();
        // dd($tickets);
        foreach ($tickets as $ticket) {
            $locationName = Location::find($ticket->location_id)->location . Location::find($ticket->location_id)->hall_name;
            $movieName = $ticket->movie_name;
            $movieTime = Movietime::find($ticket->movie_time_id)->movie_time;
            $seats = $ticket->selected_seats;
            Mail::send(new BookingExpireMail($ticket->email, $locationName, $movieName, $movieTime, $seats));
        }

    }
}
