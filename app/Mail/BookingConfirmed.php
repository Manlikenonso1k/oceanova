<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public array $booking;

    public function __construct(array $booking)
    {
        $this->booking = $booking;
    }

    public function build(): self
    {
        return $this->subject('Your Oceanova Booking is Confirmed')
            ->view('emails.booking-confirmed')
            ->with(['booking' => $this->booking]);
    }
}
