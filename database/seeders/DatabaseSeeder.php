<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\Program;
use App\Models\Classroom;
use App\Models\StudyLevel;
use Illuminate\Database\Seeder;
use App\Models\ClassroomStudent;
use App\Models\InstituteSetting;
use App\Models\ClassroomCoordinator;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::upsert(
            [
                [
                    'fullname' => 'studentOne',
                    'username' => 'student1',
                    'email' => 'student1',
                    'password' => Hash::make('student1'),
                    'role' => 'student',
                ],
                [
                    'fullname' => 'studentTwo',
                    'username' => 'student2',
                    'email' => 'student2',
                    'password' => Hash::make('student2'),
                    'role' => 'student',
                ],
                [
                    'fullname' => 'lecturerOne',
                    'username' => 'lecturer1',
                    'email' => 'lecturer1',
                    'password' => Hash::make('lecturer1'),
                    'role' => 'lecturer',
                ],
                [
                    'fullname' => 'lecturerTwo',
                    'username' => 'lecturer2',
                    'email' => 'lecturer2',
                    'password' => Hash::make('lecturer2'),
                    'role' => 'lecturer',
                ],
            ],
            ['username'],
            ['fullname', 'username', 'email', 'password', 'role']
        );
        Program::upsert(
            [
                [
                    'code' => 'ksk',
                    'name' => 'Teknologi Maklumat',
                ],
                [
                    'code' => 'kpd',
                    'name' => 'Sistem Pengurusan Pangkalan Data dan Aplikasi Web',
                ],
            ],
            ['code'],
            ['code', 'name']
        );
        StudyLevel::upsert(
            [
                [
                    'code' => 'svm',
                    'name' => 'Sijil Vokasional Malaysia',
                    'total_semester' => '1',
                ],
                [
                    'code' => 'dvm',
                    'name' => 'Diploma Vokasional Malaysia',
                    'total_semester' => '1',
                ],
            ],
            ['code'],
            ['code', 'name']
        );
        Classroom::upsert(
            [
                [
                    'id' => 1,
                    'name' => 'class1',
                    'programs_code' => 'ksk',
                    'admission_year' => '2018',
                    'study_year' => '2020',
                    'study_levels_code' => 'dvm',
                ],
                [
                    'id' => 2,
                    'name' => 'class2',
                    'programs_code' => 'kpd',
                    'admission_year' => '2020',
                    'study_year' => '2020',
                    'study_levels_code' => 'svm',
                ],
            ],
            ['id'],
            ['id', 'name', 'programs_code', 'admission_year', 'study_year', 'study_levels_code']
        );
        InstituteSetting::upsert(
            [
                [
                    'id' => 1,
                    'institute_name' => 'Kolej Vokasional Gerik',
                    'institute_email_address' => 'kvg@moe.gov.my',
                    'institute_address' => 'Gerik, Perak',
                    'institute_phone_number' => '056666666',
                    'institute_fax' => '056666666',
                ],
            ],
            ['id'],
            ['id', 'institute_name', 'institute_address', 'institute_phone_number', 'institute_fax']
        );
        ClassroomStudent::upsert(
            [
                [
                    'users_username' => 'student1',
                    'classrooms_id' => 1,
                ],
                [
                    'users_username' => 'student2',
                    'classrooms_id' => 2,
                ],
            ],
            ['users_username'],
            ['users_username', 'classrooms_id']
        );
        ClassroomCoordinator::upsert(
            [
                [
                    'users_username' => 'lecturer1',
                    'classrooms_id' => '1',
                ],
                [
                    'users_username' => 'lecturer2',
                    'classrooms_id' => '2',
                ],
            ],
            ['id'],
            ['users_username', 'classrooms_id']
        );
        Course::upsert(
            [
                [
                    'code' => 'dkb1111',
                    'name' => 'test1',
                ],
                [
                    'code' => 'dkb2222',
                    'name' => 'test2',
                ],
                [
                    'code' => 'dkb3333',
                    'name' => 'test3',
                ],
            ],
            ['code'],
            ['code', 'name']
        );
    }
}
