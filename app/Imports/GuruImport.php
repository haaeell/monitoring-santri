<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GuruImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Cari user berdasarkan nama
        $user = User::where('name', $row['nama_user'])->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'nama_user' => 'User dengan nama ' . $row['nama_user'] . ' belum terdaftar di tabel users. Silakan buat terlebih dahulu.',
            ]);
        }

        // Cek apakah NIP sudah ada
        if (Guru::where('nip', $row['nip'])->exists()) {
            throw ValidationException::withMessages([
                'nip' => 'NIP ' . $row['nip'] . ' sudah terdaftar. NIP harus unik.',
            ]);
        }

        return new Guru([
            'user_id' => $user->id,
            'nip' => $row['nip'],
            'alamat' => $row['alamat'] ?? null,
            'no_telepon' => $row['no_telepon'] ?? null,
            'pendidikan_terakhir' => $row['pendidikan_terakhir'] ?? null,
            'jabatan' => $row['jabatan'] ?? null,
            'jenis_kelamin' => $row['jenis_kelamin'] ?? null,
        ]);
    }
}
