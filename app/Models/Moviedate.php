<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Moviedate extends Model
{
    protected $fillable = ['movie_id','movie_location','startdate','enddate'];
    use HasFactory;
    public function movie()
    {
    
        return $this->belongsTo(movie::class);
    }

}
