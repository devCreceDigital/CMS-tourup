<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\TripBooking;

class NewBookingNotification extends Mailable
{
    use Queueable, SerializesModels;

    public TripBooking $booking;

    public function __construct(TripBooking $booking)
    {
        $this->booking = $booking;
    }

    public function build()
    {
        return $this->subject('Nueva reserva recibida - ' . $this->booking->trip->name)
            ->view('emails.new-booking-notification');
    }
}
