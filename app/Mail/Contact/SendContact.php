<?php

namespace App\Mail\Contact;

use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;

/**
 * Class SendContact.
 */
class SendContact extends Mailable
{
    /**
     * The request.
     *
     * @var \Illuminate\Http\Request
     */
    public $request;

    /**
     * SendContact constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to(config('mail.from.address'), config('mail.from.name'))
            ->view('mail.contact')
            ->text('mail.contact-text')
            ->subject('A new contact form submission!')
            ->from($this->request->email, $this->request->name)
            ->replyTo($this->request->email, $this->request->name);
    }
}
