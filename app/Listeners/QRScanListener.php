<?php

namespace App\Listeners;

use App\Events\QRScanned;
use App\Models\scannedcustomers;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class QRScanListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
        
    }

    /**
     * Handle the event.
     */
    public function handle(QRScanned $event): void
    {
        //
        scannedcustomers::create(['movie' => $event->movie_name, 'movietime' => $event->movie_time, 'location' => $event->location, 'hall' => $event->hall,'seatNumber'=>$event->seat]);
    }
}
