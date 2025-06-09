<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GuruImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $user = User::firstOrCreate(
            ['name' => $row['nama']],
            [
                'email' => $row['email'] ?? strtolower(str_replace(' ', '', $row['nama'])) . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'guru',
            ]
        );

        if (Guru::where('user_id', $user->id)->exists()) {
            throw ValidationException::withMessages([
                'guru' => 'Guru dengan nama ' . $row['nama'] . ' sudah ada.',
            ]);
        }

        return new Guru([
            'user_id' => $user->id,
            'nip' => $row['nip'] ?? null,
            'alamat' => $row['alamat'] ?? null,
            'no_telepon' => $row['no_telepon'] ?? null,
            'pendidikan_terakhir' => $row['pendidikan_terakhir'] ?? null,
            'jenis_kelamin' => $row['jenis_kelamin'] ?? null,
        ]);
    }
}
