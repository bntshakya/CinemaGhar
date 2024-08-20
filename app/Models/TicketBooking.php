<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketBooking extends Model
{
    use HasFactory;
    protected $fillable = ['movie_name','location_id','movie_time_id','email','selected_seats','paymentDone'];
}
