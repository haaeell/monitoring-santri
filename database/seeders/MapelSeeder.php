<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MapelSeeder extends Seeder
{
    public function run()
    {
        $mapel = [
            'B Arab', 'Fiqh', 'Shorof', 'Akhlaq', 'Tauhid', 'Tajwid', 
            "Imla'", 'Nahwu', "I'lal", 'Mustholah', 'Balaghoh', 
            'Usul fiqh', "Tasme'"
        ];

        foreach ($mapel as $nama) {
            DB::table('mapel')->insert([
                'nama_mapel' => $nama,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
