<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name')) // pengirim tetap SMTP
                    ->replyTo($this->data['email'], $this->data['name']) //supaya bisa dibalas ke user
                    ->subject('Pesan Baru dari VisitMerauke')
                    ->view('emails.contact')
                    ->with([
                        'name'    => $this->data['name'],
                        'email'   => $this->data['email'],
                        'message' => $this->data['message'],
                    ]);
    }
}