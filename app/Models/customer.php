<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customer extends Model
{
    protected $fillable = ['username','email','password'];
    use HasFactory;
    public function register(){
        return $this->hasOne(movie::class);
    }
}
