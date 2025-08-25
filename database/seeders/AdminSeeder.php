<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'username' => 'admin',
            'fullname' => 'Administrator PLN',
            'email' => 'admin@pln.co.id',
            'password' => Hash::make('admin123'),
            'level' => 'admin',
            'status' => 'aktif',
            'email_verified_at' => now(),
        ]);

    }
}