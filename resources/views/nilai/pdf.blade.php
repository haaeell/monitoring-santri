<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapor Santri</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
        .badge {
            padding: 5px;
            border-radius: 5px;
        }
        .bg-success {
            background-color: green;
            color: white;
        }
        .bg-danger {
            background-color: red;
            color: white;
        }
        .bg-warning {
            background-color: yellow;
            color: black;
        }
    </style>
</head>
<body>

    <h3 class="text-center">Rapor Santri</h3>
    <p><strong>Nama Santri:</strong> {{ $santri->nama }}</p>
    <p><strong>NIS:</strong> {{ $santri->nis }}</p>
    <p><strong>Kelas:</strong> {{ $kelas->nama_kelas }}</p>
    <p><strong>Tahun Ajaran:</strong> {{ $tahunAjaran }}</p>

    <table>
        <thead>
            <tr>
                <th>Mata Pelajaran</th>
                <th>Nilai UTS</th>
                <th>Nilai UAS</th>
                <th>Nilai Akhir</th>
                <th>Nilai Mutu</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($nilai as $item)
                @php
                    $nilai_akhir = $item->nilai_uts * 0.4 + $item->nilai_uas * 0.6;
                    $mutu = $nilai_akhir >= 85 ? 'A' : ($nilai_akhir >= 75 ? 'B' : ($nilai_akhir >= 60 ? 'C' : ($nilai_akhir >= 50 ? 'D' : 'E')));
                    $keterangan = in_array($mutu, ['A', 'B', 'C']) ? 'Lulus' : 'Tidak Lulus';
                @endphp
                <tr>
                    <td>{{ $item->mapel->nama_mapel }}</td>
                    <td>{{ $item->nilai_uts }}</td>
                    <td>{{ $item->nilai_uas }}</td>
                    <td>{{ number_format($nilai_akhir, 2) }}</td>
                    <td>{{ $mutu }}</td>
                    <td><span class="badge {{ $keterangan == 'Lulus' ? 'bg-success' : 'bg-danger' }}">{{ $keterangan }}</span></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h4 class="text-center">Rekap Kehadiran</h4>
    <p><strong>Hadir:</strong> {{ $hadir }}</p>
    <p><strong>Izin:</strong> {{ $izin }}</p>
    <p><strong>Sakit:</strong> {{ $sakit }}</p>
    <p><strong>Alfa:</strong> {{ $alfa }}</p>

    <h4 class="text-center">Data Hafalan</h4>
    <p><strong>Nama Hafalan:</strong> {{ $namaHafalan }}</p>
    <p><strong>Total Hafalan:</strong> {{ $totalHafalan }}</p>
    <p><strong>Target Hafalan:</strong> {{ $target }}</p>
    <p><strong>Keterangan:</strong> <span class="badge {{ $keteranganHafalan == 'Tercapai' ? 'bg-success' : 'bg-warning' }}">{{ $keteranganHafalan }}</span></p>

    <h4 class="text-center">Status Kenaikan Kelas</h4>
    <h3 class="text-center"><span class="badge {{ $statusKenaikan == 'Naik Kelas' ? 'bg-success' : 'bg-danger' }}">{{ $statusKenaikan }}</span></h3>

</body>
</html>
