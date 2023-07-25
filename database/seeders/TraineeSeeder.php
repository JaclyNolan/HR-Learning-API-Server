<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trainee;
use App\Models\Course;

class TraineeSeeder extends Seeder
{
    public function run()
    {
        $trainees = Trainee::factory(50)->create();

        foreach ($trainees as $trainee) {
            $randomCourseIds = Course::inRandomOrder()->limit(5)->pluck('id');
            $trainee->courses()->attach($randomCourseIds);
        }
    }
}
