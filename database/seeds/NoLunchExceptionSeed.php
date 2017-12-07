<?php

use Illuminate\Database\Seeder;
use App\Models\NoLunchException;

class NoLunchExceptionSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $nle = NoLunchException::create([
            'reason'            => 'Catalina Trip',
            'description'       => 'Lunch Provided',
            'grade_id'          => 9,
            'exception_date'    => '2017-09-18'
        ]);

        $nle = NoLunchException::create([
            'reason'            => 'Catalina Trip',
            'description'       => 'Lunch Provided',
            'grade_id'          => 8,
            'exception_date'    => '2017-09-18'
        ]);

        $nle = NoLunchException::create([
            'reason'            => 'Catalina Trip',
            'description'       => 'Lunch Provided',
            'grade_id'          => 9,
            'exception_date'    => '2017-09-19'
        ]);

        $nle = NoLunchException::create([
            'reason'            => 'Catalina Trip',
            'description'       => 'Lunch Provided',
            'grade_id'          => 8,
            'exception_date'    => '2017-09-19'
        ]);

        $nle = NoLunchException::create([
            'reason'            => 'Catalina Trip',
            'description'       => 'Lunch Provided',
            'grade_id'          => 9,
            'exception_date'    => '2017-09-20'
        ]);

        $nle = NoLunchException::create([
            'reason'            => 'Catalina Trip',
            'description'       => 'Lunch Provided',
            'grade_id'          => 8,
            'exception_date'    => '2017-09-20'
        ]);

    }
}
