@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-success p-3 text-white text-center">
                    <h4 class="mb-0"><i class="bi bi-book"></i> Rapor Santri</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        {{-- Kolom Kiri --}}
                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-md-3"><i class="bi bi-person-fill"></i> Nama Santri</label>
                                <div class="col-md-9">: <strong>{{ $santri->nama }}</strong></div>
                            </div>
                            <div class="row">
                                <label class="col-md-3"><i class="bi bi-card-list"></i> NIS</label>
                                <div class="col-md-9">: <strong>{{ $santri->nis }}</strong></div>
                            </div>
                        </div>

                        {{-- Kolom Kanan --}}
                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-md-3"><i class="bi bi-house-door-fill"></i> Kelas</label>
                                <div class="col-md-9">: <strong>{{ $kelas->nama_kelas }}</strong></div>
                            </div>
                            <div class="row">
                                <label class="col-md-3 text-nowrap"><i class="bi bi-calendar-event"></i> Tahun Ajaran</label>
                                <div class="col-md-9">: <strong>{{ $tahunAjaran }}</strong></div>
                            </div>
                        </div>
                    </div>


                    {{-- Tabel Nilai --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-success text-white text-center">
                                <tr class="table-success">
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
                                    <tr class="text-center">
                                        <td class="text-start">{{ $item->mapel->nama_mapel }}</td>
                                        <td>{{ $item->nilai_uts }}</td>
                                        <td>{{ $item->nilai_uas }}</td>
                                        <td>{{ number_format($nilai_akhir, 2) }}</td>
                                        <td><strong>{{ $mutu }}</strong></td>
                                        <td>
                                            <span class="badge {{ $keterangan == 'Lulus' ? 'bg-success' : 'bg-danger' }}">
                                                {{ $keterangan }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr class="text-center">
                                    <th colspan="3">Rata-rata Nilai</th>
                                    <th>{{ $jumlahMapel > 0 ? number_format($totalNilaiAkhir / $jumlahMapel, 2) : '0.00' }}
                                    </th>
                                    <th colspan="2"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    {{-- Kehadiran dan Hafalan dalam 2 Kolom --}}
                    <div class="row mt-4">
                        {{-- Rekap Kehadiran --}}
                        <div class="col-md-6">
                            <h5><i class="bi bi-check-circle"></i> Rekap Kehadiran</h5>
                            <div class="bg-light p-3">
                                <div class="row mb-2">
                                    <label class="col-md-3"><i class="bi bi-check-circle text-success"></i> Hadir</label>
                                    <div class="col-md-9">: {{ $hadir }}</div>
                                </div>
                                <div class="row mb-2">
                                    <label class="col-md-3"><i class="bi bi-exclamation-triangle text-warning"></i>
                                        Izin</label>
                                    <div class="col-md-9">: {{ $izin }}</div>
                                </div>
                                <div class="row mb-2">
                                    <label class="col-md-3"><i class="bi bi-thermometer text-primary"></i> Sakit</label>
                                    <div class="col-md-9">: {{ $sakit }}</div>
                                </div>
                                <div class="row mb-2">
                                    <label class="col-md-3"><i class="bi bi-x-circle text-danger"></i> Alfa</label>
                                    <div class="col-md-9">: {{ $alfa }}</div>
                                </div>
                            </div>
                        </div>

                        {{-- Data Hafalan --}}
                        <div class="col-md-6">
                            <h5><i class="bi bi-bookmark-star"></i> Data Hafalan</h5>
                            <div class="bg-light p-3">
                                <div class="row mb-2">
                                    <label class="col-md-3 text-nowrap"> Nama Hafalan</label>
                                    <div class="col-md-9">: <strong>{{ $kelas->hafalan->nama }}</strong></div>
                                </div>
                                <div class="row mb-2">
                                    <label class="col-md-3 text-nowrap">Total Hafalan</label>
                                    <div class="col-md-9">: <strong>{{ $totalHafalan }}</strong></div>
                                </div>
                                <div class="row mb-2">
                                    <label class="col-md-3 text-nowrap">Target Hafalan</label>
                                    <div class="col-md-9">: <strong>{{ $kelas->hafalan->target }}</strong></div>
                                </div>
                                <div class="row mb-2">
                                    <label class="col-md-3">Keterangan</label>
                                    <div class="col-md-9">:
                                        <span
                                            class="badge fw-bold {{ $keteranganHafalan == 'Tercapai' ? 'bg-success' : 'bg-warning' }}">
                                            {{ $keteranganHafalan }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- Status Kenaikan Kelas --}}
                    <div class="mt-4 text-center">
                        <h5><i class="bi bi-arrow-up-circle"></i> Status Kenaikan Kelas:</h5>
                        <h3>
                            <span class="badge {{ $statusKenaikan == 'Naik Kelas' ? 'bg-success' : 'bg-danger' }}">
                                {{ $statusKenaikan }}
                            </span>
                        </h3>
                    </div>

                    <div class="text-end mt-4">
                        <a href="{{ route('nilai.detail', ['santri_id' => $santri->id, 'kelas_id' => $kelas->id, 'tahun_ajaran_id' => $tahunAjaranId, 'pdf' => 'true']) }}" class="btn btn-success text-white fw-bold">Cetak PDF</a>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
@endsection
