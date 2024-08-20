<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\movie;
use App\Models\MovieScreeningCost;
use App\Models\MoviesSummary;
use App\Models\Revenue;
use App\Models\RevenueSummary;
use App\Models\ticket;
use App\Models\TicketRate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Stripe\Webhook;
use Stripe\Event;
use Error;

class StripeController extends Controller
{
    //

    public function xyz(Request $request)
    {
        require 'vendor/autoload.php';

        // The library needs to be configured with your account's secret key.
        // Ensure the key is kept out of any version control system you might be using.
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));

        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = env('WEBHOOK_SK');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            exit();
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':    
                $paymentIntent = $event->data->object;
            // ... handle other event types
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        http_response_code(200);
    }

    public function handleStripeWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = env('WEBHOOK_SK');
        try {
            $event = Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response()->json(['error' => 'Invalid signature'], 400);
        }
        \Log::channel('custom')->info('@ handleStripewebhook');
        if ($event->type == 'checkout.session.completed') {
            \Log::channel('custom')->info('@ inside');

            // \Log::channel('custom')->info($request->all());
            $session = $event->data->object;
            // \Log::channel('custom')->info($event);
            // Retrieve the necessary details from the session
            $metadata = $session->metadata;
            $email = $metadata->email;
            $selected_seats = json_decode($metadata->selected_seats);
            $movie_time_id = $metadata->movie_time_id;
            $ticket_rate = TicketRate::where('movie_time_id', $movie_time_id)->first();
            \Log::channel('custom')->info($session);
            \Log::channel('custom')->info($metadata);

            // Save the tickets to the database
            foreach ($selected_seats as $seat) {
                ticket::create([
                    'movie_name' => $metadata->movie_name,
                    'movie_time' => $metadata->movie_time,
                    'ticket_seat' => $seat,
                    'email' => $email,
                    'location_id' => $metadata->location_id,
                    'movie_time_new' => $metadata->movie_time_new,
                    'movie_time_id' => $movie_time_id,
                ]);
            }

            // Your existing logic for revenue calculations, summaries, etc.

            $movie_id = movie::where('movie_name', $metadata->movie_name)->value('id');
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
                RevenueSummary::create(['movie_id' => $movie_id, 'movie_name' => $metadata->movie_name, 'revenue' => $revenue, 'date' => $date]);
            } else {
                $updated_rev_summary = $existing_rev_summary->revenue + $revenue;
                $existing_rev_summary->update(['revenue' => $updated_rev_summary]);
            }

            $exist_movie = MoviesSummary::where('movie_id', $movie_id)->where('movieName', $metadata->movie_name)->first();
            if (is_null($exist_movie)) {
                MoviesSummary::create([
                    'movie_id' => $movie_id,
                    'movieName' => $metadata->movie_name,
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
        }

        return response()->json(['status' => 'success'], 200);
    }


    public function handle()
    {
        require_once '../vendor/autoload.php';
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        header('Content-Type: application/json');
        try {
            // retrieve JSON from POST body
            $jsonStr = file_get_contents('php://input');
            $jsonObj = json_decode($jsonStr);
            $session = $stripe->checkout->sessions->retrieve($jsonObj->session_id);
            echo json_encode(['status' => $session->status, 'customer_email' => $session->customer_details->email]);
            http_response_code(200);
        } catch (Error $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function return($session_id)
    {
        return view('return');
    }

    public function getKey(){

    }
}
