@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title">Data kelas</p>
                    <a href="/kelas/create" class="btn btn-primary btn-rounded btn-sm mb-3"><i
                            class="ti-plus fw-bold fs-7"></i></a>
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
                                                <td>{{ $item->walikelas->user->name }}</td>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        <a href="/kelas/{{ $item->id }}/edit"
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
                                                        <a href="{{ route('kelas.santri', $item->id) }}" class="btn btn-secondary text-white btn-sm fw-bold">Lihat Data Santri</a>
                                                        <a href="{{ route('kelas.mapel', $item->id) }}" class="btn btn-secondary text-white btn-sm fw-bold">Lihat Data Mapel</a>
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
                                                            <div class="fw-semibold my-4 fs-5 uppercase">Apakah Anda yakin ingin menghapus data kelas
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
