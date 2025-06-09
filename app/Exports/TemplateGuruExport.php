<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TemplateGuruExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'nama',
            'email',
            'nip',
            'alamat',
            'no_telepon',
            'pendidikan_terakhir',
            'jenis_kelamin'
        ];
    }

    public function array(): array
    {
        return [
            [
                'Ust. Ahmad Subkhi',
                'ahmad@mail.com',
                '1987654321',
                'Jl. Pesantren No. 5',
                '081234567890',
                'S1 Pendidikan Agama',
                'Laki-laki'
            ],
            [
                'Bu Fitri Rahmah',
                'fitri@mail.com',
                '1987654322',
                'Jl. Merpati No. 10',
                '082134567891',
                'S2 Pendidikan Matematika',
                'Perempuan'
            ],
        ];
    }
}
