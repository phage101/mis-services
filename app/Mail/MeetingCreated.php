<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MeetingCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $meeting;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($meeting)
    {
        $this->meeting = $meeting;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Meeting Request Created: ' . ($this->meeting->request_number ?? '#' . $this->meeting->id))
            ->markdown('emails.meetings.created');
    }
}
