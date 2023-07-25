<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Topic;
use App\Models\Trainer;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(CourseCategoryAndCourseAndTopicSeeder::class);
        $this->call(TraineeSeeder::class);
        $this->call(TrainerSeeder::class);
        $this->call(UserSeeder::class);
    }
}
