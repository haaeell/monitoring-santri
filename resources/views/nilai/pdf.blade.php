<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapor Santri</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            text-align: center;
            margin-bottom: 10px;
        }
        .section {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table th, .table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .table th {
            background: #f1f1f1;
        }
        .badge {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            color: #fff;
        }
        .badge-success { background: #28a745; }
        .badge-danger { background: #dc3545; }
        .badge-warning { background: #ffc107; }
        .footer {
            text-align: center;
            font-size: 14px;
            margin-top: 20px;
            color: #777;
        }
        .btn-print {
            display: block;
            width: 100px;
            margin: 10px auto;
            padding: 10px;
            text-align: center;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-print:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Rapor Santri</h1>
        <h2>{{ $santri->nama }}</h2>
        <div class="section">
            <p><strong>NIS:</strong> {{ $santri->nis }}</p>
            <p><strong>Kelas:</strong> {{ $kelas->nama_kelas }}</p>
            <p><strong>Tahun Ajaran:</strong> {{ $tahunAjaran }}</p>
        </div>
        
        <div class="section">
            <h3>Nilai</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Mata Pelajaran</th>
                        <th>UTS</th>
                        <th>UAS</th>
                        <th>Nilai Akhir</th>
                        <th>Mutu</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($nilai as $item)
                    <tr>
                        <td>{{ $item->mapel->nama_mapel }}</td>
                        <td>{{ $item->nilai_uts }}</td>
                        <td>{{ $item->nilai_uas }}</td>
                        <td>{{ number_format($item->nilai_akhir, 2) }}</td>
                        <td>{{ $item->mutu }}</td>
                        <td><span class="badge {{ $item->status == 'Lulus' ? 'badge-success' : 'badge-danger' }}">{{ $item->status }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="section">
            <h3>Rekap Kehadiran</h3>
            <p><strong>Hadir:</strong> {{ $hadir }}</p>
            <p><strong>Izin:</strong> {{ $izin }}</p>
            <p><strong>Sakit:</strong> {{ $sakit }}</p>
            <p><strong>Alfa:</strong> {{ $alfa }}</p>
        </div>
        <div class="section">
            <h3>Hafalan</h3>
            <p><strong>Nama Hafalan:</strong> {{ $kelas->hafalan->nama }}</p>
            <p><strong>Total Hafalan:</strong> {{ $totalHafalan }}</p>
            <p><strong>Target Hafalan:</strong> {{ $kelas->hafalan->target }}</p>
            <p><strong>Keterangan:</strong> {{ $keteranganHafalan }}</p>
        </div>

        <div class="footer">
            <p>Generated on: {{ now()->format('d M Y H:i') }}</p>
        </div>
    </div>
</body>
</html>
