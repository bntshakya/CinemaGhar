<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoviesSummary extends Model
{
    use HasFactory;
    protected $fillable = ['movie_id','movieName','Revenue','isprofitable'];
}
