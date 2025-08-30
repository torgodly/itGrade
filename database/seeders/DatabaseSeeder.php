<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Exam;
use App\Models\Result;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'admin@admin.com',
            'type' => 'admin',
        ]);

        //2 teachers
        User::factory()->create([
            'name' => 'Teacher One',
            'email' => 't@t.com',
            'type' => 'teacher',
        ]);
        User::factory()->create([
            'name' => 'Teacher Two',
            'email' => 't2@t2.com',
            'type' => 'teacher',
        ]);
//        Course::factory(30)->create();
//        Exam::factory(30)->create();
//        Student::factory(10)->create();
//        Result::factory(300)->create();


    }
}
