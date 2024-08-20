<?php

namespace App\Jobs;

use App\Models\Movietime;
use App\Models\TicketBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class StopBooking implements ShouldQueue
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
        $ticketIdCollection = Movietime::where('movie_time','<=',$currentTime->addHours(1)->format("H:i:s"))->pluck('id');
        TicketBooking::whereIn('movie_time_id',$ticketIdCollection)->where('paymentDone',false)->delete();
    }
    public function failed(\Exception $exception)
    {
        \Log::channel('custom')->error('Job Failed: ' . $exception->getMessage());
    }
}
