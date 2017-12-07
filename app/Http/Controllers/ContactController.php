<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\Contact\SendContact;
use App\Mail\Contact\SendContactCopy;
use Illuminate\Support\Facades\Mail;

/**
 * Class ContactController.
 */
class ContactController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('contact');
    }

    /**
     * @param ContactRequest $request
     *
     * @return mixed
     */
    public function send(ContactRequest $request)
    {
        Mail::send(new SendContact($request));

        if ($request->sendcopy) {
            Mail::send(new SendContactCopy($request));
        }

        return redirect()->back()->withFlashSuccess('Your information was successfully sent. We will respond as soon as we can.');
    }
}
