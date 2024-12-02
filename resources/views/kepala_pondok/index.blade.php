@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Daftar Kepala Pondok</h4>
                    <a href="{{ route('kepala_pondok.create') }}" class="btn btn-primary mb-3">Tambah Kepala Pondok</a>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>NIP</th>
                                <th>Alamat</th>
                                <th>No Telepon</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kepalaPondok as $item)
                                <tr>
                                    <td>{{ $item->user->name }}</td>
                                    <td>{{ $item->user->email }}</td>
                                    <td>{{ $item->nip }}</td>
                                    <td>{{ $item->alamat }}</td>
                                    <td>{{ $item->no_telepon }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="/kepala_pondok/{{ $item->id }}/edit"
                                                class="btn btn-info text-white btn-sm fw-bold"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                <i class="ti-file btn-icon-append"></i>
                                            </a>
                                            <button type="button"
                                                class="btn btn-danger btn-sm text-white fw-bold"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $item->id }}">
                                                <i class="ti-trash btn-icon-append"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1"
                                    aria-labelledby="deleteModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel">Hapus Data kepala pondok
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="fw-semibold my-4 fs-5 uppercase">Apakah Anda yakin ingin menghapus data kepala_pondok
                                                    <strong>{{ $item->nama }} </strong>?
                                                </div>
                                                <form id="delete-form" method="POST"
                                                    action="{{ route('kepala_pondok.destroy', $item->id) }}">
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
@endsection
