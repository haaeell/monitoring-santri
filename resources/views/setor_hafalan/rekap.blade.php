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
                                    {{ request('kelas_id') || $selectedKelas->id == $kelasItem->id ? 'selected' : '' }}>
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
                                    {{ request('tahun_ajaran_id')  || $selectedTahunAjaran->id == $tahun->id ? 'selected' : '' }}>
                                    {{ $tahun->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>

                @if ($selectedKelas && $selectedTahunAjaran)
                    <h5 class="mt-3">Kelas: {{ $selectedKelas->hafalan->nama }}</h5>
                    <h5 class="mt-3">Tahun Ajaran: {{ $selectedTahunAjaran->nama }}</h5>

                    <div class="table-responsive mt-3">
                        <table class="table table-bordered table-striped" id="dataTable">
                            <thead>
                                <tr class="text-center">
                                    <th class="text-center">Nama Santri</th>
                                    <th class="text-center">NIS</th>
                                    <th class="text-center">Nama Hafalan</th>
                                    <th class="text-center">Total Hafalan</th>
                                    <th class="text-center">Target</th>
                                    <th class="text-center">Terakhir Setor</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rekap as $santri)
                                    @php
                                        $totalHafalan = $santri->hafalan->sum('total');
                                        $lastSetor = $santri->hafalan->sortByDesc('tanggal_setor')->first();
                                        $statusClass = $totalHafalan < intVal($selectedKelas->hafalan->target) ? 'table-danger' : ($totalHafalan > intVal($selectedKelas->hafalan->target) ? 'table-success' : '');
                                    @endphp
                                    <tr class="text-center table-success {{ $statusClass }}">
                                        <td class="text-center text-nowrap">{{ $santri->nama }}</td>
                                        <td class="text-center">{{ $santri->nis }}</td>
                                        <td class="text-center">{{ $selectedKelas->hafalan->nama }}</td>
                                        <td class="text-center">{{ $totalHafalan }}</td>
                                        <td class="text-center">{{ $selectedKelas->hafalan->target }}</td>
                                        <td>{{ $lastSetor ? \Carbon\Carbon::parse($lastSetor->tanggal_setor)->format('d F Y') : '-' }}
                                        </td>
                                        <td>
                                            @if ($totalHafalan < $selectedKelas->hafalan->target)
                                                <span class="badge bg-danger">Belum Mencapai Target</span>
                                            @elseif ($totalHafalan > $selectedKelas->hafalan->target)
                                                <span class="badge bg-success">Melebihi Target</span>
                                            @else
                                                <span class="badge bg-success">Sesuai Target</span>
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
