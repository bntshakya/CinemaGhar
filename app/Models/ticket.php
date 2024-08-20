<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ticket extends Model
{
    use HasFactory;
    protected $fillable = ['movie_name','movie_time','status','ticket_seat','email','location_id','movie_time_new','movie_time_id'];

    public function location(){
        return $this->belongsTo(Location::class,'location_id');
    }

    public function movietime(){
        return $this->belongsTo(Movietime::class,'movie_time_id');
    }
}
