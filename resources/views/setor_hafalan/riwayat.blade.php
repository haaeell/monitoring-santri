@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h4>Rekap Hafalan Mingguan per Santri</h4>
                </div>

                <a href="{{ route('setor.index', ['tahun_ajaran_id' => request('tahun_ajaran_id')]) }}"
                    class="btn btn-sm btn-secondary mb-3">← Kembali</a>

                <!-- Filter Date Range Form -->
                <form action="{{ route('setor.riwayat') }}" method="GET" class="mb-3">
                    <div class="row">
                        <div class="col">
                            <input type="date" name="start_date" class="form-control" value="{{ $startDate->toDateString() }}">
                        </div>
                        <div class="col">
                            <input type="date" name="end_date" class="form-control"
                                value="{{ $endDate->toDateString() }}">
                        </div>
                        <div class="col">
                            <input type="hidden" name="tahun_ajaran_id" value="{{ request('tahun_ajaran_id') }}">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                </form>

                @if ($riwayatPerSantri->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Santri</th>
                                    <th>NIS</th>
                                    <th>Total Hafalan Minggu Ini</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($riwayatPerSantri as $data)
                                    <tr
                                        class="
                                        @if ($data['santri_id'] === $topSantriId) table-success
                                        @elseif ($data['santri_id'] === $leastSantriId)
                                            table-danger @endif
                                    ">
                                        <td>
                                            {{ $data['santri']->nama ?? '-' }}
                                            @if ($data['santri_id'] === $topSantriId)
                                                <span class="badge bg-success ms-2">🏆 Terbanyak</span>
                                            @elseif ($data['santri_id'] === $leastSantriId)
                                                <span class="badge bg-danger ms-2">🐢 Tersedikit</span>
                                            @endif
                                        </td>
                                        <td>{{ $data['santri']->nis ?? '-' }}</td>
                                        <td>{{ $data['total'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                @else
                    <p>Tidak ada data setoran hafalan untuk minggu ini.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
