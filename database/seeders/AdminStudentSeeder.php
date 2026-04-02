<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        User::updateOrCreate(
            ['email' => 'herofq@gmail.com'],
            [
                'username' => 'herofq',
                'full_name' => 'Hero FQ',
                'roles' => 'admin',
                'password' => Hash::make('Aldyansh240807_'),
            ]
        );

        // Student user
        User::updateOrCreate(
            ['email' => 'aldyaansh@gmail.com'],
            [
                'username' => 'aldyaansh',
                'full_name' => 'Aldyansh',
                'roles' => 'siswa',
                'password' => Hash::make('Aldyansh240807_'),
                'kelas' => 'XII RPL 1', // Example class name
            ]
        );
    }
}
