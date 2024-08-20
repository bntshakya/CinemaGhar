<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackMessage extends Model
{
    use HasFactory;
    protected $casts = [
        'msg_list' =>  'array',
    ];
    protected $fillable = ['user_id','msg_list'];
}
