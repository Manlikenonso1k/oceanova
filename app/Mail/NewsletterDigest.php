<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterDigest extends Mailable
{
    use Queueable, SerializesModels;

    public string $headline;
    public string $intro;
    public string $ctaText;
    public string $ctaUrl;

    public function __construct(
        string $headline = 'Oceanova Updates',
        string $intro = 'Fresh flavors, curated events, and reservation highlights from Oceanova.',
        string $ctaText = 'Reserve a table',
        string $ctaUrl = ''
    ) {
        $this->headline = $headline;
        $this->intro = $intro;
        $this->ctaText = $ctaText;
        $this->ctaUrl = $ctaUrl;
    }

    public function build(): self
    {
        return $this->subject('Oceanova Newsletter')
            ->view('emails.newsletter-digest');
    }
}
