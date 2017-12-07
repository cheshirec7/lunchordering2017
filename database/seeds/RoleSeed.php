<?php

use Illuminate\Database\Seeder;
//use Silber\Bouncer\Bouncer;

class RoleSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bouncer::allow('Administrator')->to('manage-backend');
//        Bouncer::allow('Executive')->to('view-backend');
        Bouncer::allow('User')->to('view-frontend');
    }
}
