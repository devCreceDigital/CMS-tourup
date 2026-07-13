<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\TripBooking;

class BookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public TripBooking $booking;

    public function __construct(TripBooking $booking)
    {
        $this->booking = $booking;
    }

    public function build()
    {
        return $this->subject('Confirmación de reserva - ' . $this->booking->trip->name)
            ->view('emails.booking-confirmation');
    }
}
