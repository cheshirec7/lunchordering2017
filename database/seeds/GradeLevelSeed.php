<?php

use Illuminate\Database\Seeder;
use App\Models\GradeLevel;

class GradeLevelSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $gradelevel = GradeLevel::create([
            'grade'         => '(n/a)',
            'grade_desc'    => '(unassigned)',
            'report_order'  => 0,
        ]);

        $gradelevel = GradeLevel::create([
            'grade'         => '1',
            'grade_desc'    => '1st Grade',
            'report_order'  => 5,
        ]);

        $gradelevel = GradeLevel::create([
            'grade'         => '2',
            'grade_desc'    => '2nd Grade',
            'report_order'  => 6,
        ]);

        $gradelevel = GradeLevel::create([
            'grade'         => '3',
            'grade_desc'    => '3rd Grade',
            'report_order'  => 7,
        ]);

        $gradelevel = GradeLevel::create([
            'grade'         => '4',
            'grade_desc'    => '4th Grade',
            'report_order'  => 8,
        ]);

        $gradelevel = GradeLevel::create([
            'grade'         => '5',
            'grade_desc'    => '5th Grade',
            'report_order'  => 9,
        ]);

        $gradelevel = GradeLevel::create([
            'grade'         => '6',
            'grade_desc'    => '6th Grade',
            'report_order'  => 10,
        ]);

        $gradelevel = GradeLevel::create([
            'grade'         => '7',
            'grade_desc'    => '7th Grade',
            'report_order'  => 11,
        ]);

        $gradelevel = GradeLevel::create([
            'grade'         => '8',
            'grade_desc'    => '8th Grade',
            'report_order'  => 12,
        ]);

        $gradelevel = GradeLevel::create([
            'grade'         => 'PK4',
            'grade_desc'    => 'Pre-Kindergarten',
            'report_order'  => 2,
        ]);

        $gradelevel = GradeLevel::create([
            'grade'         => 'KB',
            'grade_desc'    => 'KinderBridge',
            'report_order'  => 3,

        ]);

        $gradelevel = GradeLevel::create([
            'grade'         => 'K',
            'grade_desc'    => 'Kindergarten',
            'report_order'  => 4,
        ]);

        $gradelevel = GradeLevel::create([
            'grade'         => 'B',
            'grade_desc'    => 'Beginner',
            'report_order'  => 1,
        ]);
    }
}
