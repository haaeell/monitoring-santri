@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title">Data Wali Santri</p>
                    <div class="row">
                        <div class="col-12">

                            <div class="table-responsive">
                                <table id="dataTable" class="display expandable-table" style="width:100%">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Nama Santri</th>
                                            <th>Nama Ayah</th>
                                            <th>Nama Ibu</th>
                                            <th>Email</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($wali_santri as $item)
                                            <tr>
                                                <td>{{ $item->santri->nama }}</td>
                                                <td>{{ $item->santri->nama_ayah }}</td>
                                                <td>{{ $item->santri->nama_ibu }}</td>
                                                <td>{{ $item->user->email }}</td>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        <a href="/wali/{{ $item->santri->id }}/edit"
                                                            class="btn btn-info text-white btn-sm fw-bold"
                                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                            <i class="ti-file btn-icon-append"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
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
