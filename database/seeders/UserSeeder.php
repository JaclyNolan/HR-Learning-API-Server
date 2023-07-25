<?php

namespace Database\Seeders;

use App\Models\Trainer;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 1 admin user
        User::factory()->state(['role_id' => 1, 'email' => 'anhbg330011@gmail.com'])->create();

        // Create 3 staff users
        User::factory()->state(['role_id' => 2, 'email' => 'anhnmbh00203@fpt.edu.vn'])->create();
        User::factory()->state(['role_id' => 2])->count(3)->create();

        $trainers = Trainer::all();
        $bool = true;

        foreach ($trainers as $trainer) {
            if ($bool) {
                $bool = !$bool;
                User::factory(1)->state(['role_id' => 3, 'email' => 'ivansally0@gmail.com', 'trainer_id' => $trainer->id])->create();
                continue;
            }
            User::factory(1)->state(['role_id' => 3, 'trainer_id' => $trainer->id])->create();
        }
    }
}
