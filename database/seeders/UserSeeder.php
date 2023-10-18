<?php

namespace Database\Seeders;

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
        $admin = User::updateOrCreate([
            'email' => 'contato@lista11.com'
        ], [
            'name' => 'Admin',
            'email' => 'contato@lista11.com',
            'password' => bcrypt('123456'),
        ]);

        $admin->assignRole('admin');
    }
}
