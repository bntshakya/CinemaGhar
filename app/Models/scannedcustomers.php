<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class scannedcustomers extends Model
{
    use HasFactory;
    protected $fillable= ['movie','movietime','location','hall','seatNumber'];
}
