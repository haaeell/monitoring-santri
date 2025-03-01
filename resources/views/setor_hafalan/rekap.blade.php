@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h4>Rekap Hafalan</h4>
                </div>

                {{-- Form Filter Kelas --}}
                <form method="GET" action="{{ route('rekap.index') }}">
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
                        <table class="table table-bordered table-striped" id="dataTable">
                            <thead>
                                <tr class="text-center">
                                    <th>Nama Santri</th>
                                    <th>NIS</th>
                                    <th>Nama Hafalan</th>
                                    <th>Total Hafalan</th>
                                    <th>Target</th>
                                    <th>Terakhir Setor</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rekap as $santri)
                                    @php
                                        $totalHafalan = $santri->hafalan->sum('total');
                                        $lastSetor = $santri->hafalan->sortByDesc('tanggal_setor')->first();
                                        $statusClass = $totalHafalan < $target ? 'table-danger' : ($totalHafalan > $target ? 'table-success' : '');
                                    @endphp
                                    <tr class="text-center {{ $statusClass }}">
                                        <td>{{ $santri->nama }}</td>
                                        <td>{{ $santri->nis }}</td>
                                        <td>{{ $namaHafalan }}</td>
                                        <td>{{ $totalHafalan }}</td>
                                        <td>{{ $target }}</td>
                                        <td>{{ $lastSetor ? \Carbon\Carbon::parse($lastSetor->tanggal_setor)->format('d F Y') : '-' }}
                                        </td>
                                        <td>
                                            @if ($totalHafalan < $target)
                                                <span class="badge bg-danger">Belum Mencapai Target</span>
                                            @elseif ($totalHafalan > $target)
                                                <span class="badge bg-success">Melebihi Target</span>
                                            @else
                                                <span class="badge bg-warning">Sesuai Target</span>
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
