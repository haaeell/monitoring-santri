<?php

namespace App\Imports;

use App\Models\Santri;
use App\Models\User;
use App\Models\WaliSantri;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SantriImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $jenis_kelamin = strtolower(trim($row['jenis_kelamin']));
        $jenis_kelamin = $jenis_kelamin == 'laki-laki' ? 'Laki-laki' : 'Perempuan';

        $santri = Santri::create([
            'nama' => $row['nama'],
            'nis' => $row['nis'],
            'jenis_kelamin' => $jenis_kelamin,
            'tanggal_lahir' => $row['tanggal_lahir'] ?? null,
            'kamar' => $row['kamar'],
            'telp' => $row['telp'] ?? null,
            'alamat' => $row['alamat'] ?? null,
            'nama_ayah' => $row['nama_ayah'] ?? null,
            'nama_ibu' => $row['nama_ibu'] ?? null,
        ]);

        $user = User::create([
            'name' => $row['nama_ayah'] ?? 'Nama Ayah',
            'email' => $row['nis'] . '@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'wali_santri',
        ]);

        WaliSantri::create([
            'santri_id' => $santri->id,
            'user_id' => $user->id
        ]);

        return $santri;
    }

    public function rules(): array
    {
        return [
            '*.nama' => 'nullable|string|max:255',
            '*.nis' => 'nullable|unique:santri,nis|numeric',
            '*.jenis_kelamin' => 'nullable',
            '*.tanggal_lahir' => 'nullable|date',
            '*.kamar' => 'nullable|string|max:255',
            '*.telp' => 'nullable|string|max:15',
            '*.alamat' => 'nullable|string',
            '*.nama_ayah' => 'nullable|string|max:255',
            '*.nama_ibu' => 'nullable|string|max:255',
        ];
    }
}
