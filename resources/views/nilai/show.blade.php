@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">Rapor Santri</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5>Nama Santri: <strong>{{ $santri->nama }}</strong></h5>
                            <h5>NIS: <strong>{{ $santri->nis }}</strong></h5>
                        </div>
                        <div class="col-md-6 text-end">
                            <h5>Kelas: <strong>{{ $kelas->nama_kelas }}</strong></h5>
                            <h5>Tahun Ajaran: <strong>{{ $tahunAjaran }}</strong></h5>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="bg-secondary text-white">
                                <tr class="text-center">
                                    <th>Mata Pelajaran</th>
                                    <th>Nilai UTS</th>
                                    <th>Nilai UAS</th>
                                    <th>Nilai Akhir</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($nilai as $item)
                                    <tr>
                                        <td>{{ $item->mapel->nama_mapel }}</td>
                                        <td class="text-center">{{ $item->nilai_uts  }}</td>
                                        <td class="text-center">{{ $item->nilai_uas }}</td>
                                        <td class="text-center">{{ $item->uts * 0.4 + $item->uas * 0.6 }}</td>
                                        <td class="text-center"><span class="badge bg-success">Lulus</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <h5>Catatan Wali Kelas:</h5>
                        <p class="border p-3 bg-light">Santri menunjukkan perkembangan yang baik, tetap semangat dalam
                            belajar.</p>
                    </div>

                    <div class="text-end mt-4">
                        <button class="btn btn-secondary" onclick="window.print()">
                            <i class="ti-printer"></i> Cetak Rapor
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
