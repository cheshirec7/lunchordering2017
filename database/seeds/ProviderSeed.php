<?php

use Illuminate\Database\Seeder;
use App\Models\Provider;

class ProviderSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $provider = Provider::create([
            'provider_name'         => 'No Lunch (No School)',
            'provider_image'        => 'noschool2017.png',
            'provider_url'          => 'http://www.chandlerchristianacademy.org/',
            'allow_orders'          => false,
        ]);

        $provider = Provider::create([
            'provider_name'         => 'No Lunch (Early Dismissal)',
            'provider_image'        => 'earlydismissal2017.png',
            'provider_url'          => 'http://www.chandlerchristianacademy.org/',
            'allow_orders'          => false,
        ]);

        $provider = Provider::create([
            'provider_name'         => 'Lunch Provided',
            'provider_image'        => 'lunchprovided2017.png',
            'provider_url'          => 'http://www.chandlerchristianacademy.org/',
            'allow_orders'          => false,
        ]);

        //4
        $provider = Provider::create([
            'provider_name'         => 'Panera - "Clean Commitment"',
            'provider_image'        => 'panera2017.png',
            'provider_url'          => 'http://www.panera.com',
            'provider_includes'     => 'All orders include include a water bottle',
        ]);

        //5
        $provider = Provider::create([
            'provider_name'         => 'Chick-Fil-A',
            'provider_image'        => 'cfa2017.svg',
            'provider_url'          => 'http://www.chick-fil-a.com/',
            'provider_includes'     => 'All orders include choice of chips or fruit cup & water bottle',
        ]);

        //6
        $provider = Provider::create([
            'provider_name'         => "Elmer's Tacos",
            'provider_image'        => 'elmers2017.png',
            'provider_url'          => 'http://www.elmerstacos.com/',
            'provider_includes'     => 'All orders include chips, cheese & salsa packet and water bottle',
        ]);

        //7
        $provider = Provider::create([
            'provider_name'         => "Floridino's Pizza & Pasta",
            'provider_image'        => 'floridinos2017.png',
            'provider_url'          => 'http://floridinos.net/',
            'provider_includes'     => 'All orders include chips and water bottle',
        ]);

        //33
        $provider = Provider::create([
            'id'                    => 33,
            'provider_name'         => 'Firehouse Subs',
            'provider_image'        => 'firehouse2017.png',
            'provider_url'          => 'http://www.firehousesubs.com/',
            'provider_includes'     => 'All orders include chips chocolate chip cookie & water bottle, sandwiches have mayo/mustard on the side',
        ]);

        //34
        $provider = Provider::create([
            'id'                    => 34,
            'provider_name'         => 'Pei Wei',
            'provider_image'        => 'peiwei2017.png',
            'provider_url'          => 'https://www.peiwei.com/',
            'provider_includes'     => 'All orders include chips and water bottle',
        ]);

        //35
        $provider = Provider::create([
            'id'                    => 35,
            'provider_name'         => "Jason's Deli - \"Clean Commitment\"",
            'provider_image'        => 'jasons2017.png',
            'provider_url'          => 'http://www.jasonsdeli.com',
            'provider_includes'     => 'All orders include kettle chips, pickle, lettuce, tomato, chocolate chip cookie, and water bottle',
        ]);

        //36
        $provider = Provider::create([
            'id'                    => 36,
            'provider_name'         => 'Midday Gourmet Catering',
            'provider_image'        => 'midday2017.png',
            'provider_url'          => 'http://www.middaygourmet.com/',
            'provider_includes'     => 'All orders include chips, chocolate chip cookie, and water bottle (grapes, mayo and mustard packets on the side for meat and cheese sandwiches)',
        ]);

        //37
        $provider = Provider::create([
            'id'                    => 37,
            'provider_name'         => "Rubio's",
            'provider_image'        => 'rubios2017.png',
            'provider_url'          => 'https://www.rubios.com',
            'provider_includes'     => 'All orders include a water bottle',
        ]);
    }
}
