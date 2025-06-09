@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title">Data Guru</p>
                    @if (Auth::user()->role == 'admin')
                        <a href="/guru/create" class="btn btn-primary btn-rounded btn-sm mb-3"><i
                                class="ti-plus fw-bold fs-7"></i></a>
                        <button class="btn btn-success btn-sm fw-bold mb-3 text-white " data-bs-toggle="modal"
                            data-bs-target="#importModal">
                            Import Excel
                        </button>
                    @endif
                    <div class="row">
                        <div class="col-12">

                            <div class="table-responsive">
                                <table id="dataTable" class="display expandable-table" style="width:100%">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>Foto</th>
                                            <th>NIP</th>
                                            <th>Jenis Kelamin</th>
                                            @if (Auth::user()->role == 'admin')
                                                <th>Aksi</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($guru as $item)
                                            <tr>
                                                <td>{{ $item->user->name }}</td>
                                                <td>{{ $item->user->email }}</td>
                                                <td>
                                                    <img src="{{ $item->user->foto ? asset('storage/' . $item->user->foto) : 'https://ui-avatars.com/api/?name=' . $item->nama }}"
                                                        width="100px" height="100px" class="rounded"
                                                        style="object-fit: cover" alt="Foto guru">
                                                </td>
                                                <td>{{ $item->nip }}</td>
                                                <td>{{ $item->jenis_kelamin }}</td>
                                                @if (Auth::user()->role == 'admin')
                                                    <td>
                                                        <div class="d-flex gap-1">
                                                            <a href="/guru/{{ $item->id }}/edit"
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
                                                        </div>
                                                    </td>
                                                @endif
                                            </tr>

                                            <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1"
                                                aria-labelledby="deleteModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteModalLabel">Hapus Data guru
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="fw-semibold my-4 fs-5 uppercase">Apakah Anda yakin
                                                                ingin menghapus data guru
                                                                <strong>{{ $item->nama }} </strong>?
                                                            </div>
                                                            <form id="delete-form" method="POST"
                                                                action="{{ route('guru.destroy', $item->id) }}">
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
    <!-- Modal Import -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('guru.import') }}" method="POST" enctype="multipart/form-data" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Data guru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label fw-bold">Upload File Excel</label>
                        <input type="file" name="file" class="form-control" required accept=".xlsx,.xls,.csv">
                    </div>
                    <div class="alert alert-warning">
                        <strong>Catatan:</strong> Pastikan file Excel sesuai format template.
                        <br>
                        <a href="{{ route('guru-template') }}" class="btn btn-sm btn-info mt-2 text-white">
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
@endsection
