<?php namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Support\Facades\Auth;
use Socialite;
use Illuminate\Support\Facades\Log;

class SocialController extends Controller
{
    public function gotoFacebook()
    {
        return Socialite::with('facebook')->redirect();
    }

    public function returnFromFacebook()
    {
        $user = Socialite::with('facebook')->user();
        $account = Account::where('email', $user->email)->first();

        if (!$account)
            $account = Account::where('fb_id', $user->id)->first();

        if (!$account)
            return redirect('/login')->withFlashDanger('Your Facebook account is not linked to a CCA account. Please contact us to create a Facebook -> CCA link.');

        if (!$account->active)
            return redirect('/')->withFlashDanger('Your account is disabled.');

        Auth::loginUsingId($account->id);
        Log::info('Successful Facebook login: ' . $user->name);
        return redirect()->route('orders.index');
    }
}