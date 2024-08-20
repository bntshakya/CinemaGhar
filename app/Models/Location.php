<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $fillable = ['location','hall_name','seats'];
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function movietimes(){
        return $this->hasMany(Movietime::class);
    }

}
