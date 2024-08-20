<?php

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

class WebHookStripeController extends Controller
{
    public function handleStripeWebhook(Request $request)
    {
       
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = '';

        try {
            $event = Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if ($event->type == 'checkout.session.completed') {
            $session = $event->data->object;
            Log::channel('custom')->info('@ webhookcontroller');
            // Retrieve the necessary details from the session
            $email = $session->customer_email;
            $metadata = $session->metadata; // Assuming you have metadata stored in the session
            $selected_seats = json_decode($metadata->selected_seats);
            $movie_time_id = $metadata->movie_time_id;

            $ticket_rate = TicketRate::where('movie_time_id', $movie_time_id)->first();

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
}
