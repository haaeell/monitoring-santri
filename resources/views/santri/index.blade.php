@extends('layouts.dashboard')

@section('content')
    @if (Auth::user()->role == 'admin')
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('download-template') }}" class="btn btn-danger fw-bold text-white mb-3">Download
                            Template</a>

                        <form action="{{ route('santri.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="file" class="form-control" required>
                            <button type="submit" class="btn btn-danger text-white fw-bold mt-3 text-end">Import
                                Excel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title">Data Santri</p>
                    <div class="d-flex justify-content-between">
                        @if (Auth::user()->role == 'admin')
                            <a href="/santri/create" class="btn btn-primary btn-rounded btn-sm mb-3"><i
                                    class="ti-plus fw-bold fs-7"></i></a>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="dataTable" class="table table-bordered w-100">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>NIS</th>
                                            <th>Nama</th>
                                            <th>Kamar</th>
                                            <th>JK</th>
                                            <th>Alamat</th>
                                            <th>Telp</th>
                                            <th>Tanggal Lahir</th>
                                            <th>Foto</th>
                                            <th>Nama Ayah</th>
                                            <th>Nama Ibu</th>
                                            <th>Kelas</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function() {
            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('santriData') }}',
                scrollX: true,
                fixedColumns: {
                    leftColumns: 2
                },
                columnDefs: [{
                    className: "text-nowrap",
                    targets: "_all"
                }],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'nis',
                        name: 'nis'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'kamar',
                        name: 'kamar'
                    },
                    {
                        data: 'jenis_kelamin',
                        name: 'jenis_kelamin'
                    },
                    {
                        data: 'alamat',
                        name: 'alamat'
                    },
                    {
                        data: 'telp',
                        name: 'telp'
                    },
                    {
                        data: 'tanggal_lahir',
                        name: 'tanggal_lahir'
                    },
                    {
                        data: 'foto',
                        name: 'foto',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_ayah',
                        name: 'nama_ayah'
                    },
                    {
                        data: 'nama_ibu',
                        name: 'nama_ibu'
                    },
                    {
                        data: 'kelas.nama_kelas',
                        name: 'kelas.nama_kelas'
                    },
                    @if (Auth::user()->role == 'admin')
                        {
                            data: 'aksi',
                            name: 'aksi',
                            orderable: false,
                            searchable: false
                        },
                    @endif
                ]
            });
        });
    </script>
@endsection
