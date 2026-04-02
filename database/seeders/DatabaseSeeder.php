<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kategori;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(AdminStudentSeeder::class);

        // SUPER ADMIN
        if (!User::where('username', 'superadmin')->exists()) {
            User::create([
                'username' => 'superadmin',
                'full_name' => 'Super Administrator',
                'roles' => 'super_admin',
                'password' => Hash::make('super123'),
            ]);
        }

        // ADMIN
        if (!User::where('username', 'admin')->exists()) {
            User::create([
                'username' => 'admin',
                'full_name' => 'Administrator',
                'roles' => 'admin',
                'password' => Hash::make('admin123'),
            ]);
        }

        // SISWA
        if (!User::where('username', 'siswa')->exists()) {
            User::create([
                'username' => 'siswa',
                'full_name' => 'Siswa Demo',
                'roles' => 'siswa',
                'password' => Hash::make('siswa123'),
            ]);
        }

        // KATEGORIS
        if (Kategori::count() === 0) {
            $categories = [
                ['name' => 'Akademik', 'details' => 'Aspirasi terkait akademik dan pembelajaran'],
                ['name' => 'Fasilitas & Sarana Prasarana', 'details' => 'Aspirasi tentang fasilitas dan sarana prasarana sekolah'],
                ['name' => 'Guru & Staff', 'details' => 'Aspirasi terkait guru dan staff sekolah'],
                ['name' => 'Kesiswaan', 'details' => 'Aspirasi terkait kegiatan kesiswaan'],
                ['name' => 'Administrasi & Keuangan', 'details' => 'Aspirasi tentang administrasi dan keuangan'],
                ['name' => 'Kantin & Kebersihan', 'details' => 'Aspirasi terkait kantin dan kebersihan sekolah'],
                ['name' => 'Keamanan', 'details' => 'Aspirasi tentang keamanan sekolah'],
                ['name' => 'Saran & Inovasi', 'details' => 'Saran dan inovasi untuk kemajuan sekolah'],
                ['name' => 'Lainnya', 'details' => 'Aspirasi lainnya yang tidak masuk kategori di atas'],
            ];

            foreach ($categories as $category) {
                Kategori::create($category);
            }
        }
    }
}
