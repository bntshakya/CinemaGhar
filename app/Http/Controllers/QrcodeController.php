<?php

namespace App\Http\Controllers;

use App\Events\QRScanned;
use App\Models\scannedcustomers;
use App\Models\Ticket;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class QrcodeController extends Controller
{
    public function generateqr(Request $request)
    {
        $ticketIds = $request['ticketsId'];
        foreach($ticketIds as $ticketId){
            $ticket = Ticket::find($ticketId);

            if (!$ticket) {
                abort(404, 'Ticket not found');
            }
            
            $location = $ticket->location;
            $time = $ticket->movietime;
            $moviename = $ticket->movie_name;
            $movietime = $time->movie_time;
            $movie_location = $location->location;
            $movie_hall = $location->hall_name;
            $seat = $ticket->ticket_seat;

            $route[] = [
                'Movie' => $moviename,
                'MovieTime' => $movietime,
                'Location' => $movie_location,
                'Hall' => $movie_hall,
                'Seat' => $seat,
            ];
        }

        // Generate route URL without escaping
      

        $ticketdetails = [
            'Movie' => $moviename,
            'Movie Time' => $movietime,
            'Location' => $movie_location,
            'Hall' => $movie_hall,
        ];

        return view('qrcode', ['routes' => ($route),'ticket'=>json_encode($ticketdetails)]);
    }

    public function scannedcustomers(Request $request)
    {
        // dd($request->all());
        $movie_name = $request['Movie'];
        $movie_time = $request['MovieTime'];
        $location  = $request['Location'];
        $hall = $request['Hall'];
        $seat = $request['Seat'];
        \Event::dispatch(new QRScanned($movie_name,$movie_time,$hall,$location,$seat));
        return view('successfully-scanned');
    }

    public function viewscannedcustomers(){
        $tickets = scannedcustomers::all();
        return view('admin::admin.seatbought',['tickets'=>$tickets]);
    }
}
