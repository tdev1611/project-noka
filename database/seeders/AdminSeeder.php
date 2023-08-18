<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $plainPassword = 'Anhhai123';
        $hashedPassword = Hash::make($plainPassword);
        Admin::create([
            'name' => 'Duchai',
            'email' => 'haitr121@gmail.com',
            'password' => $hashedPassword,
        ]);
    }
}