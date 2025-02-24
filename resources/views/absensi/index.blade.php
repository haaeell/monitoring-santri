@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h4>Absensi</h4>
                </div>

                {{-- Form Filter Kelas --}}
                <form method="GET" action="{{ route('absensi.index') }}">
                    <div class="form-group">
                        <label for="kelas_id">Pilih Kelas</label>
                        <select name="kelas_id" id="kelas_id" class="form-control" required onchange="this.form.submit()">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($kelas as $kelasItem)
                                <option value="{{ $kelasItem->id }}"
                                    {{ request('kelas_id') == $kelasItem->id ? 'selected' : '' }}>
                                    {{ $kelasItem->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>

                @if ($selectedKelas)
                    <h5 class="mt-3">Mata Pelajaran: {{ Auth::user()->guru->mapel->nama_mapel ?? '-' }}</h5>

                    {{-- Form Simpan Absensi --}}
                    <form method="POST" action="{{ route('absensi.store') }}">
                        @csrf
                        <input type="hidden" name="kelas_id" value="{{ $selectedKelas->id }}">
                        <input type="hidden" name="mapel_id" value="{{ Auth::user()->guru->mapel->id }}">

                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-striped" id="dataTable">
                                <thead>
                                    <tr>
                                        <th rowspan="2">Nama Santri</th>
                                        <th rowspan="2">NPM</th>
                                        <th colspan="12" class="text-center">Absensi</th>
                                        <th rowspan="2" class="text-center">UTS</th>
                                        <th rowspan="2" class="text-center">UAS</th>
                                        <th colspan="3" class="text-center">TOTAL</th>
                                    </tr>
                                    <tr>
                                        @for ($i = 1; $i <= 12; $i++)
                                            <th>{{ $i }}</th>
                                        @endfor
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($santris as $santri)
                                        <tr>
                                            <td>{{ $santri->nama }}</td>
                                            <td>{{ $santri->nis }}</td>
                                            @for ($i = 1; $i <= 12; $i++)
                                                <td>
                                                    <select name="absensi[{{ $santri->id }}][{{ $i }}]"
                                                        class="form-control">
                                                        @php
                                                            $nilaiAbsensi =
                                                                $santri->absensi->where('pertemuan', $i)->first()
                                                                    ->status ?? null;
                                                        @endphp
                                                        <option value="Hadir"
                                                            {{ $nilaiAbsensi == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                                                        <option value="Izin"
                                                            {{ $nilaiAbsensi == 'Izin' ? 'selected' : '' }}>Izin</option>
                                                        <option value="Sakit"
                                                            {{ $nilaiAbsensi == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                                                        <option value="Alpha"
                                                            {{ $nilaiAbsensi == 'Alpha' ? 'selected' : '' }}>Alpha</option>
                                                    </select>
                                                </td>
                                            @endfor
                                            <td>{{ $santri->uts ?? '-' }}</td>
                                            <td>{{ $santri->uas ?? '-' }}</td>
                                            <td>{{ $santri->total_kehadiran ?? '-' }}</td>
                                            <td>{{ $santri->nilai_akhir ?? '-' }}</td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>

                        {{-- Tombol Simpan --}}
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Simpan Absensi</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
