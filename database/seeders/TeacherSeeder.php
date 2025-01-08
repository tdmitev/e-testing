<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Petur Petrov',
            'email' => 'petur@teach.mon.com',
            'password' => bcrypt('12345678'), 
            'role' => 'teacher', 
        ]);
    }
}