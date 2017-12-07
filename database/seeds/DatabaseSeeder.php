<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(GradeLevelSeed::class);
        $this->call(ProviderSeed::class);
//        $this->call(MenuItemSeed::class);
//        $this->call(NoLunchExceptionSeed::class);
        $this->call(RoleSeed::class);
        $this->call(AccountSeed::class);
        $this->call(UserSeed::class);

       

    }
}
