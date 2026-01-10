<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventRegistrationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $participant;
    public $qrCodeUrl;

    public function __construct($event, $participant)
    {
        $this->event = $event;
        $this->participant = $participant;
        $this->qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode(route('events.attendance.mark', [$event, $participant->uuid]));
    }

    public function build()
    {
        return $this->subject('Registration Confirmation: ' . $this->event->title)
            ->view('emails.event_registration');
    }
}
