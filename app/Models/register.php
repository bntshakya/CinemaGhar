<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class register extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = ['email', 'password', 'username','StripeCustomerId'];

    public function movies()
    {
        return $this->hasOne(customer::class);
    }

    public function comments(){
        return $this->hasMany(comment::class);
    }
}