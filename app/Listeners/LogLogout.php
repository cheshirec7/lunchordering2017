<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LogLogout
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Logout $event
     * @return void
     */
    public function handle(Logout $event)
    {
        if (config('app.log_user_agent')) {
            $insert = 'insert los_useragents(account_id,user_agent) values(:aid, :ua)';
            DB::statement($insert, array('aid' => Auth::id(), 'ua' => 'logout'));
        }
    }
}
