<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class movie extends Model
{
    use HasFactory;
    protected $fillable = ['movie_name','timing','moviepath','details','cast','rating','genre','runtime'];
    protected $casts = [
        'genre' => 'array',
    ];

    public function movietimes(){
        $this->hasMany(Movietime::class);
    }
}
