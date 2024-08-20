<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mail\TestMail;

class TestController extends Controller
{
    //
public static function index($email,$body){
        $subject = 'confirmation of tickets sold';
        $mail_body = $body;
        Mail::to($email)->send(new TestMail($subject,$mail_body));
    }    

}
