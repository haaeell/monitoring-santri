@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white fw-bold">
                    History Kelas - {{ $santri->nama }}
                </div>
                <div class="card-body table-responsive">
                    @php
                        $grouped = $santri->riwayatKelas->groupBy(
                            fn($item) => $item->tahun_ajaran . '-' . $item->semester,
                        );
                    @endphp

                    <table class="table table-bordered table-hover table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Tahun Ajaran</th>
                                <th>Semester</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($grouped as $key => $items)
                                @php
                                    [$tahun, $semester] = explode('-', $key);
                                    $modalId = 'modal-' . Str::slug($key);
                                @endphp
                                <tr>
                                    <td>{{ $tahun }}</td>
                                    <td>{{ $semester }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                            data-bs-target="#{{ $modalId }}">
                                            Lihat Nilai
                                        </button>
                                    </td>
                                </tr>

                                {{-- Modal di luar baris tabel --}}
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Belum ada data.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Modal Diletakkan Terpisah --}}
                    @foreach ($grouped as $key => $items)
                        @php
                            [$tahun, $semester] = explode('-', $key);
                            $modalId = 'modal-' . Str::slug($key);
                        @endphp
                        <div class="modal fade" id="{{ $modalId }}" tabindex="-1"
                            aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
                            <div class="modal-dialog modal-md modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="{{ $modalId }}Label">Nilai - {{ $tahun }}
                                            Semester {{ $semester }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Mapel</th>
                                                    <th>Nilai</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($items as $item)
                                                    <tr>
                                                        <td>{{ $item->mapel->nama_mapel }}</td>
                                                        <td>{{ $item->nilai }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">Kembali</a>

                </div>
            </div>
        </div>
    </div>
@endsection
