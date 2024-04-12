<?php

namespace DGTournaments\Mail\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApiFieldsChanged extends Mailable
{
    use Queueable, SerializesModels;

    public $removed;
    public $added;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($removed, $added)
    {
        $this->removed = $removed;
        $this->added = $added;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Api Fields Changed')
            ->view('emails.admin.fields_changed');
    }
}
