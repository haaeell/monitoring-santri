@extends('layouts.dashboard')

@section('content')
    <div class="card">
        <div class="card-body">
            <h3 class="mb-4">Tahun Ajaran</h3>
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addModal">Tambah Tahun Ajaran</button>

            <table class="table display expandable-table" id="dataTable">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tahunAjaran as $tahun)
                        <tr>
                            <td>{{ $tahun->nama }}</td>
                            <td>{{ $tahun->tanggal_mulai }}</td>
                            <td>{{ $tahun->tanggal_selesai }}</td>
                            <td>{{ ucfirst($tahun->status) }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editModal{{ $tahun->id }}">Edit</button>
                                <form action="{{ route('tahun-ajaran.destroy', $tahun->id) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="editModal{{ $tahun->id }}" tabindex="-1"
                            aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Tahun Ajaran</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('tahun-ajaran.update', $tahun->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <label>Nama Tahun Ajaran</label>
                                            <input type="text" name="nama" value="{{ $tahun->nama }}"
                                                class="form-control" required>

                                            <label>Tanggal Mulai</label>
                                            <input type="date" name="tanggal_mulai" value="{{ $tahun->tanggal_mulai }}"
                                                class="form-control" required>

                                            <label>Tanggal Selesai</label>
                                            <input type="date" name="tanggal_selesai"
                                                value="{{ $tahun->tanggal_selesai }}" class="form-control" required>

                                            <label>Status</label>
                                            <select name="status" class="form-control">
                                                <option value="aktif" {{ $tahun->status == 'aktif' ? 'selected' : '' }}>
                                                    Aktif</option>
                                                <option value="nonaktif"
                                                    {{ $tahun->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                            </select>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">Update</button>
                                            <button type="button" class="btn btn-secondary"
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

    <!-- Modal Tambah -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Tahun Ajaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('tahun-ajaran.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <label>Nama Tahun Ajaran</label>
                        <input type="text" name="nama" class="form-control" required>

                        <label>Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="form-control" required>

                        <label>Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" class="form-control" required>

                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
