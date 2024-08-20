<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevenueSummary extends Model
{
    use HasFactory;
    protected $fillable = [
        'movie_id','movie_name','revenue','date'
    ];
}
