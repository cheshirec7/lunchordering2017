<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use App\Repositories\AccountRepository;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * @var AccountRepository
     */
    protected $user;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/orders';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AccountRepository $user)
    {
        $this->middleware('guest');
        $this->user = $user;
    }

    public function showResetForm(Request $request, $token = null)
    {
        if (!$token) {
            return redirect()->route('password.request');
        }

        $user = $this->user->findByPasswordResetToken($token);
        if ($user && app()->make('auth.password.broker')->tokenExists($user, $token)) {
            return view('auth.passwords.reset')->with(
                ['token' => $token, 'email' => $request->email]
            );
        }

        return redirect()->route('password.request')->withFlashWarning('There was a problem resetting your password. Please try again.');
    }
}
