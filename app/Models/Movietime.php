<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movietime extends Model
{

    use HasFactory;

    protected $fillable = ['movie_id','location_id','movie_time'];

    public function movie(){
        return $this->belongsTo(movie::class);
    }

    public function location(){
        return $this->belongsTo(Location::class);
    }
}
