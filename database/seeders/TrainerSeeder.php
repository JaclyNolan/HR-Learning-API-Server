<?php

namespace Database\Seeders;

use App\Models\Topic;
use App\Models\Trainer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TrainerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trainers = Trainer::factory(10)->create();

        foreach ($trainers as $trainer) {
            $randomTopicIds = Topic::inRandomOrder()->limit(5)->pluck('id');

            $trainer->topics()->attach($randomTopicIds);
        }
    }
}
