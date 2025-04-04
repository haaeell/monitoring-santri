@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <!-- Informasi Santri -->
        <h4 class="fw-bold">Tahun Ajaran: {{ $selectedTahunAjaran->nama }}</h4>
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info p-3 text-white text-center">
                    <h4 class="mb-0"><i class="bi bi-person-circle"></i> Informasi Santri</h4>
                </div>
                <div class="card-body">
                    <table class="w-100">
                        <tbody>
                            <tr>
                                <td class="fw-bold" style="width: 25%;">Nama</td>
                                <td style="width: 5%;">:</td>
                                <td>{{ $santri->nama }}</td>
                                <td class="fw-bold" style="width: 25%;">Kamar</td>
                                <td style="width: 5%;">:</td>
                                <td>{{ $santri->kamar }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">NIS</td>
                                <td>:</td>
                                <td>{{ $santri->nis }}</td>
                                <td class="fw-bold">Nama Ayah</td>
                                <td>:</td>
                                <td>{{ $santri->nama_ayah ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Nama Ibu</td>
                                <td>:</td>
                                <td>{{ $santri->nama_ibu ?? '-' }}</td>
                                <td class="fw-bold">Alamat</td>
                                <td>:</td>
                                <td>{{ $santri->alamat ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Tanggal Lahir</td>
                                <td>:</td>
                                <td colspan="4">
                                    {{ $santri->tanggal_lahir ? \Carbon\Carbon::parse($santri->tanggal_lahir)->format('d M Y') : 'Tanggal lahir belum tersedia' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

       <!-- Hafalan Santri -->
<div class="col-md-12 mt-3">
    <div class="card shadow-sm">
        <div class="card-header bg-primary p-3 text-white text-center">
            <h4 class="mb-0"><i class="bi bi-journal-text"></i> Hafalan Santri</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Bagian Teks (Kiri) -->
                <div class="col-md-6">
                    @php
                        $totalHafalan = $santri->hafalan->sum('total');
                        $lastSetor = $santri->hafalan->sortByDesc('tanggal_setor')->first();
                    @endphp

                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Nama Hafalan</strong></td>
                            <td>: {{ $santri->kelas->hafalan->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Setoran Terakhir</strong></td>
                            <td>: {{ $lastSetor->tanggal_setor ?? 'Belum pernah setor' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Total Hafalan</strong></td>
                            <td>: {{ $totalHafalan }} Juz</td>
                        </tr>
                        <tr>
                            <td><strong>Target Hafalan</strong></td>
                            <td>: {{ $santri->kelas->hafalan->target ?? '-' }} Juz</td>
                        </tr>
                        <tr>
                            <td><strong>Status Hafalan</strong></td>
                            <td>: 
                                <span class="badge {{ $totalHafalan >= ($santri->kelas->hafalan->target ?? 0) ? 'bg-success' : 'bg-warning' }}">
                                    {{ $totalHafalan >= ($santri->kelas->hafalan->target ?? 0) ? 'Lulus' : 'Belum Lulus' }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Bagian Grafik (Kanan) -->
                <div class="col-md-6">
                    <p>Riwayat Setoran Hafalan</p>
                   <div>
                    <canvas id="hafalanChart"></canvas>
                   </div>
                </div>
            </div>
        </div>
    </div>
</div>

        <!-- Nilai Akademik -->
        <div class="table-responsive mt-3">
            <table class="table table-bordered table-striped" id="dataTable">
                <thead>
                    <tr class="table-success">
                        <th rowspan="2">Mata Pelajaran</th>
                        <th colspan="12" class="text-center">Absensi</th>
                        <th colspan="4" class="text-center">TOTAL</th>
                        <th colspan="3" class="text-center">Persentase Nilai</th>
                        <th rowspan="2">Nilai Akhir</th>
                        <th rowspan="2">Nilai Mutu</th>
                    </tr>
                    <tr class="table-success">
                        @for ($i = 1; $i <= 12; $i++)
                            <th>{{ $i }}</th>
                        @endfor
                        <th>HADIR</th>
                        <th>IZIN</th>
                        <th>SAKIT</th>
                        <th>ALPHA</th>
                        <th>Absensi (40%)</th>
                        <th>UTS (30%)</th>
                        <th>UAS (30%)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mapels as $mapel)
                        <tr>
                            <td>{{ $mapel->nama_mapel }}</td>

                            @php
                                $totalHadir = 0;
                                $totalIzin = 0;
                                $totalSakit = 0;
                                $totalAlpha = 0;
                                $absensiSantri = [];
                            @endphp

                            @foreach ($absensiData->where('mapel_id', $mapel->id)->where('santri_id', $santri->id) as $absensi)
                                @php
                                    $status = $absensi->status;
                                    $absensiSantri[] = $status;

                                    if ($status == 'H') {
                                        $totalHadir++;
                                    } elseif ($status == 'I') {
                                        $totalIzin++;
                                    } elseif ($status == 'S') {
                                        $totalSakit++;
                                    } elseif ($status == 'A') {
                                        $totalAlpha++;
                                    }
                                @endphp
                            @endforeach

                            @for ($i = 1; $i <= 12; $i++)
                                @php
                                    $status = $absensiSantri[$i - 1] ?? '';
                                    $bgColor =
                                        $status == 'H'
                                            ? 'bg-success'
                                            : ($status == 'I'
                                                ? 'bg-warning'
                                                : ($status == 'S'
                                                    ? 'bg-info'
                                                    : ($status == 'A'
                                                        ? 'bg-danger'
                                                        : 'bg-light')));
                                @endphp
                                <td class="text-center fw-bold text-dark {{ $bgColor }}">{{ $status }}</td>
                            @endfor

                            <td>{{ $totalHadir }}</td>
                            <td>{{ $totalIzin }}</td>
                            <td>{{ $totalSakit }}</td>
                            <td>{{ $totalAlpha }}</td>

                            {{-- Persentase Nilai --}}
                            @php
                                $nilai = $santri->nilai
                                    ->where('mapel_id', $mapel->id)
                                    ->where('tahun_ajaran_id', $selectedTahunAjaran->id)
                                    ->first();
                                $nilai_uts = $nilai ? $nilai->nilai_uts : 0;
                                $nilai_uas = $nilai ? $nilai->nilai_uas : 0;
                            @endphp
                            <td>{{ number_format(($totalHadir / 12) * 100, 2) }}%</td>
                            <td>{{ $nilai_uts ?? '0' }}</td>
                            <td>{{ $nilai_uas ?? '0' }}</td>

                            {{-- Nilai Akhir --}}
                            <td>
                                @php
                                    $absensiCount = optional(
                                        $absensiData->where('mapel_id', $mapel->id)->where('santri_id', $santri->id),
                                    )->count();
                                    if ($absensiCount < 12 || $nilai_uts == 0 || $nilai_uas == 0) {
                                        $nilai_akhir = 'Nilai belum lengkap';
                                    } else {
                                        $nilai_akhir =
                                            ($totalHadir / 12) * 100 * 0.4 + $nilai_uts * 0.3 + $nilai_uas * 0.3;
                                    }
                                @endphp
                                {{ is_numeric($nilai_akhir) ? number_format($nilai_akhir, 2) : $nilai_akhir }}
                            </td>

                            {{-- Nilai Mutu --}}
                            <td>
                                @php
                                    $nilai_mutu = 'Nilai belum lengkap';
                                    if (is_numeric($nilai_akhir)) {
                                        if ($nilai_akhir >= 85) {
                                            $nilai_mutu = 'A';
                                        } elseif ($nilai_akhir >= 75) {
                                            $nilai_mutu = 'B';
                                        } elseif ($nilai_akhir >= 60) {
                                            $nilai_mutu = 'C';
                                        } elseif ($nilai_akhir >= 50) {
                                            $nilai_mutu = 'D';
                                        } else {
                                            $nilai_mutu = 'E';
                                        }
                                    }
                                @endphp
                                {{ $nilai_mutu }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="table-warning fw-bold">
                        <td colspan="20" class="text-end">Rata-rata</td>
                        <td>
                            @php
                                $totalNilaiAkhir = 0;
                                $jumlahNilaiValid = 0;

                                foreach ($mapels as $mapel) {
                                    $absensiCount = optional(
                                        $absensiData->where('mapel_id', $mapel->id)->where('santri_id', $santri->id),
                                    )->count();
                                    $nilai = $santri->nilai
                                        ->where('mapel_id', $mapel->id)
                                        ->where('tahun_ajaran_id', $selectedTahunAjaran->id)
                                        ->first();
                                    $nilai_uts = $nilai ? $nilai->nilai_uts : 0;
                                    $nilai_uas = $nilai ? $nilai->nilai_uas : 0;
                                    $totalHadir = $absensiData
                                        ->where('mapel_id', $mapel->id)
                                        ->where('santri_id', $santri->id)
                                        ->where('status', 'H')
                                        ->count();

                                    if ($absensiCount >= 12 && $nilai_uts > 0 && $nilai_uas > 0) {
                                        $nilaiAkhir =
                                            ($totalHadir / 12) * 100 * 0.4 + $nilai_uts * 0.3 + $nilai_uas * 0.3;
                                        $totalNilaiAkhir += $nilaiAkhir;
                                        $jumlahNilaiValid++;
                                    }
                                }

                                $rataRataNilaiAkhir =
                                    $jumlahNilaiValid > 0
                                        ? $totalNilaiAkhir / $jumlahNilaiValid
                                        : 'Nilai belum lengkap';
                            @endphp
                            {{ is_numeric($rataRataNilaiAkhir) ? number_format($rataRataNilaiAkhir, 2) : $rataRataNilaiAkhir }}
                        </td>
                        <td>
                            @php
                                $nilai_mutu_rata = 'Nilai belum lengkap';
                                if (is_numeric($rataRataNilaiAkhir)) {
                                    if ($rataRataNilaiAkhir >= 85) {
                                        $nilai_mutu_rata = 'A';
                                    } elseif ($rataRataNilaiAkhir >= 75) {
                                        $nilai_mutu_rata = 'B';
                                    } elseif ($rataRataNilaiAkhir >= 60) {
                                        $nilai_mutu_rata = 'C';
                                    } elseif ($rataRataNilaiAkhir >= 50) {
                                        $nilai_mutu_rata = 'D';
                                    } else {
                                        $nilai_mutu_rata = 'E';
                                    }
                                }
                            @endphp
                            {{ $nilai_mutu_rata }}
                        </td>
                    </tr>
                </tfoot>

            </table>
        </div>

        <!-- Peringkat Santri -->
        <div class="col-md-12 mt-3">
            <div class="card shadow-sm">
                <div class="card-header bg-danger p-3 text-white text-center">
                    <h4 class="mb-0"><i class="bi bi-trophy"></i> Peringkat Santri</h4>
                </div>
                <div class="card-body text-center">
                    @if ($santri->kelas)
                        @php
                            $mapelsKelas = $santri->kelas->mapels;

                            // Cek apakah semua mapel di kelas ini sudah ada nilainya untuk santri ini
                            $nilaiSantri = $santri->nilai
                                ->whereIn('mapel_id', $mapelsKelas->pluck('id'))
                                ->where('tahun_ajaran_id', $selectedTahunAjaran->id);

                            $mapelBelumDinilai = $mapelsKelas->count() > $nilaiSantri->count();

                            if ($mapelBelumDinilai) {
                                $peringkatSantri = null; // Belum bisa hitung peringkat
                            } else {
                                // Hitung total nilai akhir santri ini
                                $totalNilaiSantri = $nilaiSantri->sum(function ($nilai) {
                                    return $nilai->nilai_absen * 0.4 +
                                        $nilai->nilai_uts * 0.3 +
                                        $nilai->nilai_uas * 0.3;
                                });

                                // Ambil semua santri dalam kelas ini
                                $santriKelas = $santri->kelas->santri;

                                // Hitung total nilai untuk semua santri di kelas
                                $peringkatKelas = $santriKelas
                                    ->map(function ($s) use ($mapelsKelas, $selectedTahunAjaran) {
                                        $nilai = $s->nilai
                                            ->whereIn('mapel_id', $mapelsKelas->pluck('id'))
                                            ->where('tahun_ajaran_id', $selectedTahunAjaran->id);

                                        if ($mapelsKelas->count() > $nilai->count()) {
                                            return null; // Kalau ada santri yang belum lengkap nilainya, dia nggak ikut ranking
                                        }

                                        return [
                                            'santri_id' => $s->id,
                                            'total_nilai' => $nilai->sum(function ($n) {
                                                return $n->nilai_absen * 0.4 +
                                                    $n->nilai_uts * 0.3 +
                                                    $n->nilai_uas * 0.3;
                                            }),
                                        ];
                                    })
                                    ->filter(); // Buang santri yang nilai mapelnya belum lengkap

                                // Urutkan dari yang terbesar ke terkecil
                                $peringkatKelas = $peringkatKelas->sortByDesc('total_nilai')->values();

                                // Cari peringkat santri ini
                                $peringkatSantri =
                                    $peringkatKelas->search(fn($s) => $s['santri_id'] == $santri->id) + 1;
                            }
                        @endphp

                        @if ($mapelBelumDinilai)
                            <h5 class="text-danger">Nilai belum lengkap</h5>
                        @else
                            <h5>Peringkat: {{ $peringkatSantri }} dari {{ $peringkatKelas->count() }}</h5>
                        @endif
                    @else
                        <h5 class="text-warning">Santri belum memiliki kelas</h5>
                    @endif
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Script Chart.js -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('hafalanChart').getContext('2d');

        const hafalanData = {
            labels: {!! json_encode($santri->hafalan->pluck('tanggal_setor')) !!}, // Tanggal setor
            datasets: [{
                label: 'Jumlah Hafalan',
                data: {!! json_encode($santri->hafalan->pluck('total')) !!}, // Total hafalan
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        };

        new Chart(ctx, {
            type: 'bar',
            data: hafalanData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endsection
