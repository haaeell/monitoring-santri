<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        DB::table('users')->insertGetId([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'foto' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        for ($i = 0; $i < 10; $i++) {
            $guruUserId = DB::table('users')->insertGetId([
                'name' => 'Guru ' . ($i + 1), 
                'email' => 'guru' . ($i + 1) . '@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'foto' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('guru')->insertGetId([
                'user_id' => $guruUserId,
                'nip' => $faker->randomNumber(8),
                'alamat' => $faker->address,
                'no_telepon' => $faker->phoneNumber,
                'pendidikan_terakhir' => 'S1 Pendidikan',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        for ($i = 0; $i < 10; $i++) {

            DB::table('kelas')->insertGetId([
                'nama_kelas' => 'KELAS ' . ($i + 1),
                'wali_kelas_id' => DB::table('guru')->inRandomOrder()->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    
             DB::table('mapel')->insertGetId([
                'nama_mapel' => 'Mapel ' . ($i + 1), 
                'guru_id' => DB::table('guru')->inRandomOrder()->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        for ($i = 0; $i < 100; $i++) {
            $namaAyah = $faker->name;
            $nis = $faker->randomNumber(5);
            
            $userId = DB::table('users')->insertGetId([
                'name' => 'Santri ' . chr(65 + ($i % 26)) . '-' . ($i + 1), 
                'email' => $nis . $i . '@gmail.com',
                'password' => Hash::make('password'),
                'foto' => null, 
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $santriId = DB::table('santri')->insertGetId([
                'nama' => 'Santri ' . chr(65 + ($i % 26)) . '-' . ($i + 1), 
                'nis' =>  $nis . $i ,
                'kamar' => 'Kamar ' . $faker->numberBetween(1, 5),
                'jenis_kelamin' => $faker->randomElement(['Laki-laki', 'Perempuan']),
                'alamat' => $faker->address,
                'telp' => $faker->phoneNumber,
                'tanggal_lahir' => $faker->date(),
                'nama_ayah' => $namaAyah,
                'nama_ibu' => $faker->name,
                'kelas_id' =>  DB::table('kelas')->inRandomOrder()->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('wali_santri')->insert([
                'user_id' => $userId,
                'santri_id' => $santriId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}