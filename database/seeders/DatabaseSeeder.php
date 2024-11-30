<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $adminId = DB::table('users')->insertGetId([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'foto' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $guruUserId = DB::table('users')->insertGetId([
            'name' => 'Guru User',
            'email' => 'guru@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'foto' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $guruId = DB::table('guru')->insertGetId([
            'user_id' => $guruUserId,
            'nip' => '12345678',
            'alamat' => 'Jalan Guru',
            'no_telepon' => '081234567890',
            'pendidikan_terakhir' => 'S1 Pendidikan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kepala_pondok')->insertGetId([
            'user_id' => $guruUserId,
            'nip' => '87654321',
            'alamat' => 'Jalan Kepala',
            'no_telepon' => '081098765432',
            'pendidikan_terakhir' => 'S2 Pendidikan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $kelasId = DB::table('kelas')->insertGetId([
            'nama_kelas' => 'Kelas A',
            'wali_kelas_id' => $guruId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $hafalanId = DB::table('hafalan')->insertGetId([
            'nama' => 'Jurumiyah',
            'kelas_id' => $kelasId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $santriId = DB::table('santri')->insertGetId([
            'nama' => 'Santri A',
            'nis' => '12345',
            'kamar' => 'Kamar 1',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jalan Santri',
            'telp' => '08123456789',
            'tanggal_lahir' => '2005-05-15',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('santri_kelas')->insert([
            'santri_id' => $santriId,
            'kelas_id' => $kelasId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('wali_santri')->insertGetId([
            'user_id' => $adminId,
            'santri_id' => $santriId,
            'hubungan' => 'Orang Tua',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $mapelId = DB::table('mapel')->insertGetId([
            'nama_mapel' => 'Matematika',
            'guru_id' => $guruUserId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('mapel_kelas')->insert([
            'mapel_id' => $mapelId,
            'kelas_id' => $kelasId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
