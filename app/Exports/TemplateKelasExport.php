<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TemplateKelasExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return ['nama_kelas', 'wali_kelas', 'tingkatan', 'sub_kelas'];
    }

    public function array(): array
    {
        return [
            ['7A', 'Ust. Ahmad', '7', 'A'],
            ['8B', 'Bu Rahma', '8', 'B'],
        ];
    }
}
