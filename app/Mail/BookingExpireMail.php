<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingExpireMail extends Mailable
{
    use Queueable, SerializesModels;
    public $RecepientMail;
    public $Location;
    public $movieName;
    public $movieTime;
    public $seats;


    /**
     * Create a new message instance.
     */
    public function __construct($RecepientMail,$location,$movieName,$movieTime,$seats)
    {
        //
        $this->RecepientMail = $RecepientMail;
        $this->Location = $location;
        $this->movieName = $movieName;
        $this->movieTime = $movieTime;
        $this->seats=$seats;
        \Log::channel('custom')->info('abc');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to:[
                new Address($this->RecepientMail),
            ],
            subject: 'Booking Expire Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view :'admin::mail.BookingExpired',
            with:[
                'location'=>$this->Location,
                'movieName'=>$this->movieName,
                'movieTime'=>$this->movieTime,
                'seats'=>$this->seats,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
