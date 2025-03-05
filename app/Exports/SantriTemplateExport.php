<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class SantriTemplateExport implements FromArray, WithHeadings, WithColumnWidths
{
    public function headings(): array
    {
        return [
            'nama', 'nis', 'jenis_kelamin', 'tanggal_lahir', 'kamar', 'telp', 'alamat', 'nama_ayah', 'nama_ibu'
        ];
    }

    public function array(): array
    {
        return [
            ['Ahmad', '12345', 'Laki-laki', '2005-01-15', 'A-101', '08123456789', 'Jl. Contoh', 'Budi', 'Siti']
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30, // nama
            'B' => 30, // nis
            'C' => 30, // jenis_kelamin
            'D' => 30, // tanggal_lahir
            'E' => 30, // kamar
            'F' => 30, // telp
            'G' => 30, // alamat
            'H' => 30, // nama_ayah
            'I' => 30, // nama_ibu
        ];
    }
}
