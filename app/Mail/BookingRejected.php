<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingRejected extends Mailable
{
    use Queueable, SerializesModels;

    public array $booking;

    public function __construct(array $booking)
    {
        $this->booking = $booking;
    }

    public function build(): self
    {
        return $this->subject('Update on Your Oceanova Booking')
            ->view('emails.booking-rejected')
            ->with(['booking' => $this->booking]);
    }
}
