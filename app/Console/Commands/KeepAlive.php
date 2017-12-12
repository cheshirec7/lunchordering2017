<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class KeepAlive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'keepalive:touchdb';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Used to stop the system from hibernating on GoDaddy';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::table('los_useragents')
            ->whereIn('account_id', [1, 4])
            ->delete();
    }
}
