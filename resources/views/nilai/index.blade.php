@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h4>Rapor santri</h4>
                </div>

                {{-- Form Filter Kelas --}}
                <form method="GET" action="{{ route('nilai.index') }}">
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

                @if ($selectedKelas && $selectedTahunAjaran)
                    <h5 class="mt-3">Kelas: {{ $selectedKelas->nama_kelas }}</h5>
                    <h5 class="mt-3">Tahun Ajaran: {{ $selectedTahunAjaran->nama }}</h5>

                    <div class="table-responsive mt-3">
                        @php
                            $rekap = $rekap->sortByDesc(
                                fn($santri) => $santri->hitungRataRata($selectedKelas->id, $selectedTahunAjaran->id),
                            );
                            $peringkat = 1;
                        @endphp

                        <table class="display expandable-table" id="dataTable">
                            <thead>
                                <tr class="text-center">
                                    <th class="text-center">Nama Santri</th>
                                    <th class="text-center">NIS</th>
                                    <th class="text-center">Nilai Rata Rata</th>
                                    <th class="text-center">Peringkat</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rekap as $santri)
                                    <tr class="text-center">
                                        <td class="text-center">{{ $santri->nama }}</td>
                                        <td class="text-center">{{ $santri->nis }}</td>
                                        <td class="text-center">
                                            @php
                                                $rataRata = $santri->hitungRataRata(
                                                    $selectedKelas->id,
                                                    $selectedTahunAjaran->id,
                                                );
                                            @endphp
                                            {{ $rataRata }}
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $badge = '';
                                                if ($rataRata == 0) {
                                                    $badge =
                                                        '<span class="badge bg-secondary">Belum Ada Peringkat</span>';
                                                } elseif ($peringkat == 1) {
                                                    $badge = '<span class="badge bg-info">🏆 Juara 1</span>';
                                                } elseif ($peringkat == 2) {
                                                    $badge = '<span class="badge bg-primary">🥈 Juara 2</span>';
                                                } elseif ($peringkat == 3) {
                                                    $badge = '<span class="badge bg-warning">🥉 Juara 3</span>';
                                                } else {
                                                    $badge = $peringkat;
                                                }
                                            @endphp
                                            {!! $badge !!}
                                        </td>

                                        <td>
                                            <form action="{{ route('nilai.detail', $santri->id) }}" method="GET">
                                                <input type="hidden" name="kelas_id" value="{{ $selectedKelas->id }}">
                                                <input type="hidden" name="tahun_ajaran_id"
                                                    value="{{ $selectedTahunAjaran->id }}">
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <i class="ti-eye"></i> Detail
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @php $peringkat++; @endphp
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
