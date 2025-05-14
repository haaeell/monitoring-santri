@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title">Data kelas</p>
                    @if (Auth::user()->role == 'admin')
                        <a href="/kelas/create" class="btn btn-primary btn-rounded btn-sm mb-3"><i
                                class="ti-plus fw-bold"></i></a>
                        <button class="btn btn-success btn-sm fw-bold mb-3 text-white " data-bs-toggle="modal"
                            data-bs-target="#importModal">
                            Import Excel
                        </button>
                    @endif

                    <!-- Modal Import -->
                    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="{{ route('kelas.import') }}" method="POST" enctype="multipart/form-data"
                                class="modal-content">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="importModalLabel">Import Data Kelas</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Tutup"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="file" class="form-label fw-bold">Upload File Excel</label>
                                        <input type="file" name="file" class="form-control" required
                                            accept=".xlsx,.xls,.csv">
                                    </div>
                                    <div class="alert alert-warning">
                                        <strong>Catatan:</strong> Pastikan file Excel sesuai format template.
                                        <br>
                                        <a href="{{ route('kelas-template') }}" class="btn btn-sm btn-info mt-2 text-white">
                                            Download Template
                                        </a>                                        
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Import</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">

                            <div class="table-responsive">
                                <table id="dataTable" class="display expandable-table" style="width:100%">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Nama</th>
                                            <th>Wali Kelas</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kelas as $item)
                                            <tr>
                                                <td>{{ $item->nama_kelas }}</td>
                                                <td>{{ $item->walikelas?->user?->name }}</td>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        @if (Auth::user()->role == 'admin')
                                                            <a href="/kelas/{{ $item->id }}/edit"
                                                                class="btn btn-info text-white btn-sm fw-bold"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="Edit">
                                                                <i class="ti-pencil btn-icon-append"></i>
                                                            </a>
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm text-white fw-bold"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#deleteModal{{ $item->id }}">
                                                                <i class="ti-trash btn-icon-append"></i>
                                                            </button>
                                                        @endif
                                                        <a href="{{ route('kelas.santri', $item->id) }}"
                                                            class="btn btn-secondary text-white btn-sm fw-bold">Lihat Data
                                                            Santri</a>
                                                        <a href="{{ route('kelas.mapel', $item->id) }}"
                                                            class="btn btn-secondary text-white btn-sm fw-bold">Lihat Data
                                                            Mapel</a>
                                                    </div>
                                                </td>
                                            </tr>

                                            <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1"
                                                aria-labelledby="deleteModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteModalLabel">Hapus Data kelas
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="fw-semibold my-4 fs-5 uppercase">Apakah Anda yakin
                                                                ingin menghapus data kelas
                                                                <strong>{{ $item->nama }} </strong>?
                                                            </div>
                                                            <form id="delete-form" method="POST"
                                                                action="{{ route('kelas.destroy', $item->id) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                        </div>
                                                        <div class="card-footer text-end my-2">
                                                            <button type="submit"
                                                                class="btn btn-danger fw-bold btn-rounded text-white btn-sm">Hapus</button>
                                                            <button type="button"
                                                                class="btn btn-secondary fw-bold btn-rounded text-white btn-sm"
                                                                data-bs-dismiss="modal">Batal</button>
                                                        </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
