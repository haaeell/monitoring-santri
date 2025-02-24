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


                    {{-- Form Simpan Absensi & Nilai --}}
                    <form method="POST" action="{{ route('absensi.store') }}">
                        @csrf
                        <input type="hidden" name="kelas_id" value="{{ $selectedKelas->id }}">
                        <input type="hidden" name="mapel_id" value="{{ Auth::user()->guru->mapel->id ?? '' }}">
                        <input type="hidden" name="tahun_ajaran_id" value="{{ $selectedTahunAjaran->id ?? '' }}">

                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-striped" id="dataTable">
                                <thead>
                                    <tr>
                                        <th rowspan="2">Nama Santri</th>
                                        <th rowspan="2">NPM</th>
                                        <th colspan="12" class="text-center">Absensi</th>
                                        <th colspan="4" class="text-center">TOTAL</th>
                                        <th colspan="3" class="text-center">Persentase Nilai</th>
                                        <th rowspan="2">Nilai Akhir</th>
                                        <th rowspan="2">Nilai Mutu</th>
                                    </tr>
                                    <tr>
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
                                                        class="form-control {{ $warna }} fw-bold text-dark text-center" style="width: 50px;">
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
                                                @php
                                                    $nilai_absensi = ($santri->total_h / 12) * 100;
                                                    $nilai_absensi_persen = $nilai_absensi * 0.4;
                                                @endphp
                                                {{ number_format($nilai_absensi, 2) }}%
                                            </td>
                                            <td>
                                                <input type="number" name="uts[{{ $santri->id }}]" class="form-control"
                                                    value="{{ $santri->nilai->nilai_uts ?? '' }}" min="0"
                                                    max="100">
                                            </td>
                                            <td>
                                                <input type="number" name="uas[{{ $santri->id }}]" class="form-control"
                                                    value="{{ $santri->nilai->nilai_uas ?? '' }}" min="0"
                                                    max="100">
                                            </td>


                                            {{-- Nilai Akhir --}}
                                            <td>
                                                @php
                                                    $nilai_uts = $santri->nilai->nilai_uts ?? 0;
                                                    $nilai_uas = $santri->nilai->nilai_uas ?? 0;
                                                    $nilai_akhir =
                                                        $nilai_absensi * 0.4 + $nilai_uts * 0.3 + $nilai_uas * 0.3;
                                                @endphp
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
@endsection
