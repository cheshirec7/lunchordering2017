<?php

use Illuminate\Database\Seeder;
use App\Models\Account;

class AccountSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $account = Account::create([
            'account_name'      => 'Totten, Eric',
            'email'             => 'eric.david.totten@gmail.com',
            'password'          => bcrypt('secret'),
            'active'            => 1,
        ]);
        $account->assign('Administrator');

        $account = Account::create([
            'id'                => 2,
            'account_name'      => 'Richmond, Kristi & Michael',
            'email'             => 'kristirichmond@yahoo.com',
            'password'          => bcrypt('123456'),
            'active'            => 1,
        ]);
        $account->assign('Administrator');

        $account = Account::create([
            'id'                => 4,
            'account_name'      => 'Totten, Eric',
            'email'             => 'erictotten@cox.net',
            'password'          => bcrypt('123456'),
            'active'            => 1,
        ]);
        $account->assign('User');

//        $account = Account::create([
//            'name'         => 'User',
//            'email'             => 'executive@executive.com',
//            'password'          => bcrypt('123456'),
//            'active'            => 1,
//        ]);
//        $account->assign('Executive');

    }
}
