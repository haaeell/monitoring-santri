@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h4>Absensi & Penilaian</h4>
                </div>

                {{-- Form Filter Kelas --}}
                <form method="GET" action="{{ route('absensi.index') }}">
                    <div class="form-group">
                        <label for="kelas_id">Pilih Kelas</label>
                        <select name="kelas_id" id="kelas_id" class="form-control" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($kelas as $kelasItem)
                                <option value="{{ $kelasItem->id }}"
                                    {{ request('kelas_id') == $kelasItem->id ? 'selected' : '' }}>
                                    {{ $kelasItem->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mt-2">
                        <label for="tahun_ajaran_id">Pilih Tahun Ajaran</label>
                        <select name="tahun_ajaran_id" id="tahun_ajaran_id" class="form-control" required
                            onchange="this.form.submit()">
                            <option value="">-- Pilih Tahun Ajaran --</option>
                            @foreach ($tahunAjaran as $tahun)
                                <option value="{{ $tahun->id }}"
                                    {{ request('tahun_ajaran_id') == $tahun->id ? 'selected' : '' }}>
                                    {{ $tahun->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>

                @if ($selectedKelas)
                    <h5 class="mt-3">Mata Pelajaran: {{ Auth::user()->guru->mapel->nama_mapel ?? 'Tidak ada data' }}</h5>

                    <form method="POST" action="{{ route('absensi.store') }}">
                        @csrf

                        <ul class="nav nav-tabs bg-success text-white p-2 rounded" id="pertemuanTabs" role="tablist">
                            @for ($i = 1; $i <= 12; $i++)
                                <li class="nav-item" role="presentation">
                                    <button
                                        class="nav-link text-white  fw-bold {{ $i == 1 ? 'active bg-light text-dark' : '' }}"
                                        id="tab-{{ $i }}" data-bs-toggle="tab"
                                        data-bs-target="#pertemuan-{{ $i }}" type="button" role="tab">
                                        Pertemuan {{ $i }}
                                    </button>
                                </li>
                            @endfor
                        </ul>

                        <div class="tab-content mt-3" id="pertemuanTabContent">
                            @for ($i = 1; $i <= 12; $i++)
                                <div class="tab-pane fade {{ $i == 1 ? 'show active' : '' }}"
                                    id="pertemuan-{{ $i }}" role="tabpanel">
                                    <div class="card shadow-sm">
                                        <div class="card-body">
                                            <h5 class="card-title">Pembahasan Pertemuan {{ $i }}</h5>
                                            <textarea name="pembahasan[{{ $i }}]" class="form-control" rows="3">{{ $pembahasan[$i] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>

                        <input type="hidden" name="kelas_id" value="{{ $selectedKelas->id }}">
                        <input type="hidden" name="mapel_id" value="{{ Auth::user()->guru->mapel->id ?? '' }}">
                        <input type="hidden" name="tahun_ajaran_id" value="{{ $selectedTahunAjaran->id ?? '' }}">

                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-striped" id="dataTable">
                                <thead>
                                    <tr class="table-success">
                                        <th rowspan="2">Nama Santri</th>
                                        <th rowspan="2">NPM</th>
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
                                    @foreach ($santris as $santri)
                                        @php

                                            $nilai_absensi = ($santri->total_h / 12) * 100;
                                            $nilai_absensi_persen = $nilai_absensi * 0.4;
                                            $nilai_uts =
                                                $santri->nilai
                                                    ->where('tahun_ajaran_id', $selectedTahunAjaran->id)
                                                    ->where('kelas_id', $selectedKelas->id)
                                                    ->where('mapel_id', Auth::user()->guru->mapel->id)
                                                    ->first()->nilai_uts ?? 0;
                                            $nilai_uas =
                                                $santri->nilai
                                                    ->where('tahun_ajaran_id', $selectedTahunAjaran->id)
                                                    ->where('kelas_id', $selectedKelas->id)
                                                    ->where('mapel_id', Auth::user()->guru->mapel->id)
                                                    ->first()->nilai_uas ?? 0;
                                            $nilai_akhir = $nilai_absensi * 0.4 + $nilai_uts * 0.3 + $nilai_uas * 0.3;
                                        @endphp
                                        <tr>
                                            <td>{{ $santri->nama }}</td>
                                            <td>{{ $santri->nis }}</td>
                                            @for ($i = 1; $i <= 12; $i++)
                                                @php
                                                    $nilaiAbsensi =
                                                        $santri->absensi->where('pertemuan', $i)->first()->status ?? '';
                                                    $warna = match ($nilaiAbsensi) {
                                                        'H' => 'bg-success',
                                                        'I' => 'bg-warning',
                                                        'S' => 'bg-info',
                                                        'A' => 'bg-danger',
                                                        default => '',
                                                    };
                                                @endphp
                                                <td>
                                                    <select name="absensi[{{ $santri->id }}][{{ $i }}]"
                                                        class="form-control {{ $warna }} fw-bold text-dark text-center"
                                                        style="width: 50px;">
                                                        <option value=""></option>
                                                        <option value="H"
                                                            {{ $nilaiAbsensi == 'H' ? 'selected' : '' }}>H</option>
                                                        <option value="I"
                                                            {{ $nilaiAbsensi == 'I' ? 'selected' : '' }}>I</option>
                                                        <option value="S"
                                                            {{ $nilaiAbsensi == 'S' ? 'selected' : '' }}>S</option>
                                                        <option value="A"
                                                            {{ $nilaiAbsensi == 'A' ? 'selected' : '' }}>A</option>
                                                    </select>
                                                </td>
                                            @endfor
                                            <td>{{ $santri->total_h }}</td>
                                            <td>{{ $santri->total_i }}</td>
                                            <td>{{ $santri->total_s }}</td>
                                            <td>{{ $santri->total_a }}</td>

                                            {{-- Persentase Nilai --}}
                                            <td>
                                                {{ number_format($nilai_absensi, 2) }}%
                                                <input type="hidden" name="presensi[{{ $santri->id }}]"
                                                    value="{{ $nilai_absensi }}">
                                            </td>
                                            <td>
                                                <input type="number" name="uts[{{ $santri->id }}]" class="form-control"
                                                    value="{{ $nilai_uts ?? '' }}" min="0" max="100">
                                            </td>
                                            <td>
                                                <input type="number" name="uas[{{ $santri->id }}]" class="form-control"
                                                    value="{{ $nilai_uas ?? '' }}" min="0" max="100">
                                            </td>


                                            {{-- Nilai Akhir --}}
                                            <td>
                                                {{ number_format($nilai_akhir, 2) }}
                                            </td>

                                            {{-- Nilai Mutu --}}
                                            <td>
                                                @php
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
                                                @endphp
                                                {{ $nilai_mutu }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>

                        {{-- Tombol Simpan --}}
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Simpan Data</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    {{-- Bootstrap 5 Script --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var firstTab = new bootstrap.Tab(document.querySelector("#tab-1"));
            firstTab.show();
        });

        // Mengubah warna tab yang aktif
        document.querySelectorAll('.nav-link').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.nav-link').forEach(el => el.classList.remove('bg-light',
                    'text-dark'));
                this.classList.add('bg-light', 'text-dark');
            });
        });
    </script>
@endsection
