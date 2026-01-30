<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserBookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public array $booking;

    public function __construct(array $booking)
    {
        $this->booking = $booking;
    }

    public function build(): self
    {
        return $this
            ->subject('Booking Confirmation')
            ->view('emails.user-booking')
            ->with([
                'booking' => $this->booking,
            ]);
    }
}
