<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminBookingNotification extends Mailable
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
            ->subject('New Booking Request')
            ->view('emails.admin-booking')
            ->with([
                'booking' => $this->booking,
            ]);
    }
}
