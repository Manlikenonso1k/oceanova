<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterWelcome extends Mailable
{
    use Queueable, SerializesModels;

    public function build(): self
    {
        return $this->subject('Welcome to Oceanova Updates')
            ->view('emails.newsletter-welcome');
    }
}
