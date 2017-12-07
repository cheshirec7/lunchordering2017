<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'id'                => 1,
            'account_id'        => 1,
            'last_name'         => '(unassigned)',
            'first_name'        => '(unassigned)',
            'grade_id'          => 1,
            'teacher_id'        => 1,
            'user_type'         => 3,
            'allowed_to_order'  => 0
        ]);

        $user = User::create([
            'id'                => 2,
            'account_id'        => 2,
            'last_name'         => 'Richmond',
            'first_name'        => 'Kristi',
            'grade_id'          => 1,
            'teacher_id'        => 1,
            'user_type'         => 2,
            'allowed_to_order'  => 1
        ]);

        $user = User::create([
            'id'                => 195,
            'account_id'        => 2,
            'last_name'         => 'Richmond',
            'first_name'        => 'McKenna',
            'grade_id'          => 8,
            'teacher_id'        => 1,
            'user_type'         => 3,
            'allowed_to_order'  => 1
        ]);

        $user = User::create([
            'id'                => 196,
            'account_id'        => 2,
            'last_name'         => 'Richmond',
            'first_name'        => 'Arianna',
            'grade_id'          => 5,
            'teacher_id'        => 1,
            'user_type'         => 3,
            'allowed_to_order'  => 1
        ]);

        $user = User::create([
            'id'                => 4,
            'account_id'        => 4,
            'last_name'         => 'Totten',
            'first_name'        => 'Eric',
            'grade_id'          => 1,
            'teacher_id'        => 1,
            'user_type'         => 2,
            'allowed_to_order'  => 0
        ]);
    }
}


//'user_type_none' => 1,
//    'user_type_admin' => 2,
//    'user_type_student' => 3,
//    'user_type_teacher' => 4,
//    'user_type_staff' => 5,
//    'user_type_parent' => 6,