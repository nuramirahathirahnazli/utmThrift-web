<?php

namespace Database\Seeders;

use App\Models\User;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class SellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Creating 5 dummy sellers with 'pending' verification status
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => 'Seller ' . $i,
                'email' => 'seller' . $i . '@graduate.utm.my',
                'is_verified' => 1, 
                'password' => Hash::make('password'), // Default password for testing
                'contact' => '0123456789',
                'gender' => 'Male',
                'location' => 'Location ' . $i,
                'status' => 'Student', // Or 'inactive', depending on your needs
                'matric' => 'S12345' . $i,
                'user_type' => 'Seller', // Seller type
                'verification_status' => 'pending', // Pending status
                
                
            ]);
        }
    }
}
