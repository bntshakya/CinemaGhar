<?php

namespace App\Jobs;

use App\Mail\BookingExpireMail;
use App\Models\Location;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use App\Models\Movietime;
use App\Models\TicketBooking;
use Mail;

class BookingExpireMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
        

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        $currentTime = Carbon::now('Asia/Kathmandu');
        $ticketIdCollection = Movietime::where('movie_time', '<=', $currentTime->addHours(2)->format("H:i:s"))->where('movie_time', '>=', $currentTime->addHours(1)->format("H:i:s"))->pluck('id');
        $tickets = TicketBooking::whereIn('movie_time_id', $ticketIdCollection)->where('paymentDone', false)->get();
        foreach ($tickets as $ticket) {
            $locationName = Location::find($ticket->location_id)->location . Location::find($ticket->location_id)->hall_name;
            $movieName = $ticket->movie_name;
            $movieTime = Movietime::find($ticket->movie_time_id)->movie_time;
            $seats = $ticket->selected_seats;
            Mail::send(new BookingExpireMail($ticket->email, $locationName, $movieName, $movieTime, $seats));
        }
    }
}
