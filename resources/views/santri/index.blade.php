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
                                <table id="dataTable" class="display expandable-table" style="width:100%">
                                    <thead>
                                        <tr class="text-center">
                                            <th>NIS</th>
                                            <th>Nama</th>
                                            <th>Kamar</th>
                                            <th>Jenis Kelamin</th>
                                            <th>Alamat</th>
                                            <th>Telp</th>
                                            <th>Tanggal Lahir</th>
                                            <th>Foto</th>
                                            <th>Nama Ayah</th>
                                            <th>Nama Ibu</th>
                                            <th>Kelas</th>
                                            @if (Auth::user()->role == 'admin')
                                                <th>Aksi</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($santri as $item)
                                            <tr>
                                                <td>{{ $item->nis }}</td>
                                                <td class="text-nowrap">{{ $item->nama }}</td>
                                                <td>{{ $item->kamar }}</td>
                                                <td>{{ $item->jenis_kelamin }}</td>
                                                <td>{{ $item->alamat }}</td>
                                                <td>{{ $item->telp }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->tanggal_lahir)->format('d-m-Y') }}</td>
                                                <td>
                                                    <img src="{{ $item->foto ? asset('storage/' . $item->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($item->nama) }}"
                                                        width="80px" height="80px" class="rounded"
                                                        style="object-fit: cover;" alt="{{ $item->nama }}">
                                                </td>
                                                <td>{{ $item->nama_ayah }}</td>
                                                <td>{{ $item->nama_ibu }}</td>
                                                <td>{{ $item->kelas->nama_kelas ?? '-' }}</td>
                                                @if (Auth::user()->role == 'admin')
                                                    <td>
                                                        <div class="d-flex gap-1">
                                                            <a href="/santri/{{ $item->id }}/edit"
                                                                class="btn btn-info text-white btn-sm fw-bold"
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

                                            <!-- Modal delete -->
                                            <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Hapus Data Santri</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p class="fw-semibold">Yakin mau hapus
                                                                <strong>{{ $item->nama }}</strong>?
                                                            </p>
                                                            <form method="POST"
                                                                action="{{ route('santri.destroy', $item->id) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <div class="text-end">
                                                                    <button type="submit"
                                                                        class="btn btn-danger btn-sm fw-bold">Hapus</button>
                                                                    <button type="button" class="btn btn-secondary btn-sm"
                                                                        data-bs-dismiss="modal">Batal</button>
                                                                </div>
                                                            </form>
                                                        </div>
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

@section('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#dataTable').DataTable({
                scrollX: true,
                fixedColumns: {
                    leftColumns: 2
                },
                columnDefs: [{
                    className: "text-nowrap",
                    targets: "_all"
                }]
            });
        });
    </script>
@endsection
