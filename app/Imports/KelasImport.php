<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Hafalan;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KelasImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $guru = Guru::whereHas('user', function ($query) use ($row) {
            $query->where('name', $row['wali_kelas']);
        })->first();

        if (!$guru) {
            throw ValidationException::withMessages([
                'wali_kelas' => 'Guru dengan nama ' . $row['wali_kelas'] . ' belum terdaftar di tabel guru. Silakan buat terlebih dahulu.',
            ]);
        }

        if (Kelas::where('nama_kelas', $row['nama_kelas'])->exists()) {
            throw ValidationException::withMessages([
                'nama_kelas' => 'Kelas dengan nama ' . $row['nama_kelas'] . ' sudah ada. Nama kelas harus unik.',
            ]);
        }

        $hafalan = Hafalan::where('tingkatan', $row['tingkatan'])->first();

        if (!$hafalan) {
            throw ValidationException::withMessages([
                'hafalan_id' => 'Hafalan dengan tingkatan ' . $row['tingkatan'] . ' tidak ditemukan.',
            ]);
        }

        return new Kelas([
            'nama_kelas' => $row['nama_kelas'],
            'wali_kelas_id' => $guru->id,
            'tingkatan' => $row['tingkatan'] ?? null,
            'sub_kelas' => $row['sub_kelas'] ?? null,
            'hafalan_id' => $hafalan->id
        ]);
    }
}
