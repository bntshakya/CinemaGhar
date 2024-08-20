<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;


class adminusers extends Authenticatable
{
    use HasFactory;
    public static $roles = ['admin', 'sales', 'support'];
    protected $fillable = ['email', 'password', 'username', 'role'];
}
