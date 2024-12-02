@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title">Data Santri</p>
                    <a href="/santri/create" class="btn btn-primary btn-rounded btn-sm mb-3"><i
                            class="ti-plus fw-bold fs-7"></i></a>
                    <div class="row">
                        <div class="col-12">

                            <div class="table-responsive">
                                <table id="dataTable" class="display expandable-table" style="width:100%">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Nama</th>
                                            <th>Foto</th>
                                            <th>NIS</th>
                                            <th>Jenis Kelamin</th>
                                            <th>Kamar</th>
                                            <th>Nama Orangtua</th>
                                            <th>Alamat</th>
                                            <th>Telepon</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($santri as $item)
                                            <tr>
                                                <td>{{ $item->nama }}</td>
                                                <td>
                                                    <img src="{{ $item->foto ? asset('storage/' . $item->foto) : 'https://ui-avatars.com/api/?name=' . $item->nama }}"
                                                        width="100px" height="100px" class="rounded"
                                                        style="object-fit: cover" alt="Foto Santri">
                                                </td>
                                                <td>{{ $item->nis }}</td>
                                                <td>{{ $item->jenis_kelamin }}</td>
                                                <td>{{ $item->kamar }}</td>
                                                <td>{{ $item->nama_ayah }} / {{ $item->nama_ibu }}
                                                </td>
                                                <td>{{ $item->alamat }}</td>
                                                <td>{{ $item->telp }}</td>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        <a href="/santri/{{ $item->id }}/edit"
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
                                                            <h5 class="modal-title" id="deleteModalLabel">Hapus Data Santri
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="fw-semibold my-4 fs-5 uppercase">Apakah Anda yakin ingin menghapus data santri
                                                                <strong>{{ $item->nama }} </strong>?
                                                            </div>
                                                            <form id="delete-form" method="POST"
                                                                action="{{ route('santri.destroy', $item->id) }}">
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
