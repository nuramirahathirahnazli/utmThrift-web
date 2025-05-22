<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'is_verified' => 1,
                'password' => bcrypt('password123'), // Just for testing
                'contact' => $faker->phoneNumber,
                'gender' => $faker->randomElement(['Male', 'Female']),
                'faculty' => $faker->word,
                'location' => 'KDOJ',
                'user_role' => 'Student',
                'matric' => 'A' . $faker->unique()->numberBetween(100000000, 999999999),
                'user_type' => 'Seller',
                'profile_picture' => $faker->imageUrl(),
                'otp' => $faker->randomNumber(6),
                'otp_expiry' => now()->addMinutes(5),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
