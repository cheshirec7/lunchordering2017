<?php

namespace App\Mail\Contact;

use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;

/**
 * Class SendContactCopy.
 */
class SendContactCopy extends Mailable
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
        return $this->to($this->request->email, $this->request->name)
            ->view('mail.contact-copy')
            ->text('mail.contact-text-copy')
            ->subject('Copy of your contact form submission')
            ->from(config('mail.from.address'), config('mail.from.name'));
    }
}
