<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Appointment;
use App\Services\DateHelper;

class AppointmentNotification extends Mailable
{
    use Queueable, SerializesModels;

    private $appointment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from("rdv@allodoc.com", "AllO'Doc")
            ->subject("Annulation de votre rendez-vous")
            ->view('mail/appointmentNotification', [
                'appointment' => $this->appointment,
                'dateHelper'  => new DateHelper(),
            ]);
    }
}
