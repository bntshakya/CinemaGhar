<?php

namespace App\Http\Controllers;

use App;
use App\Mail\DemoMail;
use Illuminate\Http\Request;
use Mail;
use app\Mail\ExampleMail;
use App\Mail\SampleMail;

class MailController extends Controller
{
    //
    public function index()
    {
        $content = [
            'subject' => 'This is the mail subject',
            'body' => 'This is the email body of how to send email from laravel 10 with mailtrap.'
        ];

        Mail::to('your_email@gmail.com')->send(new SampleMail($content));

        return "Email has been sent.";
    }

    public function sendEmail()
    {
        $details = [
            'title' => 'Mail from Laravel',
            'body' => 'This is a test email sent from Laravel.'
        ];
        \Log::info($details);

        Mail::to('sarij95708@kinsef.com')->send(new App\Mail\ExampleMail($details));

        return "Email Sent";
    }
}
