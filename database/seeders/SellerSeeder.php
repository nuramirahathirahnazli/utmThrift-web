<?php

namespace Database\Seeders;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SellerSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index) {
            $user = User::inRandomOrder()->first(); // Randomly pick a user from the users table

            Seller::create([
                'user_id' => $user->id,
                'store_name' => $faker->company,
                'matric_card_file' => $faker->imageUrl(), // You can replace this with a path to an image if needed
                'verification_status' => $faker->randomElement(['pending', 'approved', 'rejected']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

