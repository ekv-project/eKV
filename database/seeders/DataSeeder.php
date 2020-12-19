<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\ClassroomCoordinator;
use App\Models\ClassroomStudent;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::upsert([
                [
                    'fullname' => 'studentOne',
                    'username' => 'student1',
                    'email' => 'student1',
                    'password' => Hash::make('student1'),
                    'role' => 'student'
                ],
                [
                    'fullname' => 'studentTwo',
                    'username' => 'student2',
                    'email' => 'student2',
                    'password' => Hash::make('student2'),
                    'role' => 'student'
                ]
                ], ['username'], ['fullname', 'username', 'email', 'password', 'role']);
        
        Classroom::upsert([
            [
                'programs_code' => 'ksk',
                'admission_year' => '2018',
                'study_year' => '2020',
                'study_levels_code' => 'dvm',
            ],
            [
                'programs_code' => 'kpd',
                'admission_year' => '2020',
                'study_year' => '2020',
                'study_levels_code' => 'svm',
            ]
            ], ['id'], ['programs_code', 'admission_year', 'study_year', 'study_levels_code',]
        );
        /*
        ClassroomStudent::upsert([

        ]);
        ClassroomCoordinator::upsert([

        ]);
        */
    }
}
