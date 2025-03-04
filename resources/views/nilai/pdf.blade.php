<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapor Santri</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #007BFF;
            margin-bottom: 30px;
        }
        header h1 {
            margin: 0;
            font-size: 36px;
            color: #007BFF;
        }
        .subtitle {
            font-size: 18px;
            color: #555;
        }
        .section-title {
            font-size: 20px;
            color: #007BFF;
            margin-top: 30px;
            text-transform: uppercase;
            border-bottom: 2px solid #007BFF;
            padding-bottom: 5px;
        }
        .content {
            margin-bottom: 20px;
        }
        .content strong {
            color: #555;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th,
        .table td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #ddd;
        }
        .table th {
            background-color: #f7f7f7;
            color: #007BFF;
        }
        .badge {
            padding: 5px 10px;
            font-size: 14px;
            color: #fff;
            border-radius: 12px;
        }
        .badge-success {
            background-color: #28a745;
        }
        .badge-danger {
            background-color: #dc3545;
        }
        .badge-warning {
            background-color: #ffc107;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Header -->
        <header>
            <h1>Rapor Santri</h1>
            <p class="subtitle">Laporan Penilaian dan Kehadiran Santri</p>
        </header>

        <!-- Santri Info -->
        <div class="content">
            <p><strong>Nama Santri:</strong> {{ $santri->nama }}</p>
            <p><strong>NIS:</strong> {{ $santri->nis }}</p>
            <p><strong>Kelas:</strong> {{ $kelas->nama_kelas }}</p>
            <p><strong>Tahun Ajaran:</strong> {{ $tahunAjaran }}</p>
        </div>

        <!-- Nilai -->
        <div class="section-title">Nilai</div>
        <table class="table">
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
                @php
                    $totalNilaiAkhir = 0;
                    $jumlahMapel = count($nilai);
                @endphp
                @foreach ($nilai as $item)
                    @php
                        $nilai_akhir = $item->nilai_uts * 0.4 + $item->nilai_uas * 0.6;
                        $totalNilaiAkhir += $nilai_akhir;

                        if ($nilai_akhir >= 85) {
                            $mutu = 'A';
                        } elseif ($nilai_akhir >= 75) {
                            $mutu = 'B';
                        } elseif ($nilai_akhir >= 60) {
                            $mutu = 'C';
                        } elseif ($nilai_akhir >= 50) {
                            $mutu = 'D';
                        } else {
                            $mutu = 'E';
                        }

                        $keterangan = in_array($mutu, ['A', 'B', 'C']) ? 'Lulus' : 'Tidak Lulus';
                    @endphp
                    <tr>
                        <td>{{ $item->mapel->nama_mapel }}</td>
                        <td>{{ $item->nilai_uts }}</td>
                        <td>{{ $item->nilai_uas }}</td>
                        <td>{{ number_format($nilai_akhir, 2) }}</td>
                        <td><strong>{{ $mutu }}</strong></td>
                        <td>
                            <span class="badge {{ $keterangan == 'Lulus' ? 'badge-success' : 'badge-danger' }}">
                                {{ $keterangan }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Kehadiran -->
        <div class="section-title">Rekap Kehadiran</div>
        <ul>
            <li><strong>Hadir:</strong> {{ $hadir }}</li>
            <li><strong>Izin:</strong> {{ $izin }}</li>
            <li><strong>Sakit:</strong> {{ $sakit }}</li>
            <li><strong>Alfa:</strong> {{ $alfa }}</li>
        </ul>

        <!-- Data Hafalan -->
        <div class="section-title">Data Hafalan</div>
        <ul>
            <li><strong>Nama Hafalan:</strong> {{ $namaHafalan }}</li>
            <li><strong>Total Hafalan:</strong> {{ $totalHafalan }}</li>
            <li><strong>Target Hafalan:</strong> {{ $target }}</li>
            <li><strong>Keterangan:</strong> 
                <span class="badge {{ $keteranganHafalan == 'Tercapai' ? 'badge-success' : 'badge-warning' }}">
                    {{ $keteranganHafalan }}
                </span>
            </li>
        </ul>

        <!-- Status Kenaikan Kelas -->
        <div class="section-title">Status Kenaikan Kelas</div>
        <p>
            <strong>Status:</strong> 
            <span class="badge {{ $statusKenaikan == 'Naik Kelas' ? 'badge-success' : 'badge-danger' }}">
                {{ $statusKenaikan }}
            </span>
        </p>

        <!-- Footer -->
        <div class="footer">
            <p>Generated on: {{ now()->format('d M Y H:i') }}</p>
        </div>
    </div>

</body>
</html>
