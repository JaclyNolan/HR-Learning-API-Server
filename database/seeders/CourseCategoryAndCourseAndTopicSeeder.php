<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Topic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseCategoryAndCourseAndTopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courseCategories = CourseCategory::factory(3)->create();
        foreach ($courseCategories as $courseCategorie) {
            Course::factory(5)->for($courseCategorie)->has(Topic::factory(3))->create();
        }
    }
}
