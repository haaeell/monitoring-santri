@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Mapel di Kelas {{ $kelas->nama_kelas }}</h4>
                    <a href="{{ route('kelas.index') }}" class="btn btn-outline-secondary mb-3">Kembali ke Kelas</a>

                    @if (Auth::user()->role == 'admin')
                        <h5>Pilih Mapel untuk Kelas Ini</h5>
                        <form action="{{ route('kelas.addMapel', $kelas->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <select class="form-select select2" name="mapel_id[]" multiple>
                                    @foreach ($allMapels as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama_mapel }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Tambahkan Mapel ke Kelas</button>
                        </form>
                    @endif

                    <hr>

                    <div class="table-responsive">
                        <table id="dataTable" class="display expandable-table" style="width:100%">
                            <thead>
                                <tr class="text-center">
                                    <th>Nama Mapel</th>
                                    <th>Nama Guru</th>
                                    @if (Auth::user()->role == 'admin')
                                        <th>Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mapels as $mapel)
                                    <tr>
                                        <td>{{ $mapel->nama_mapel }}</td>
                                        <td>{{ $mapel->guru->user->name ?? '-' }}</td>
                                        @if (Auth::user()->role == 'admin')
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <button type="button" class="btn btn-danger btn-sm text-white fw-bold"
                                                        data-bs-toggle="modal" data-bs-target="#deleteMapelModal"
                                                        data-mapel-id="{{ $mapel->id }}"
                                                        data-kelas-id="{{ $kelas->id }}" data-bs-placement="top"
                                                        title="Hapus">
                                                        <i class="ti-trash btn-icon-append"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="deleteMapelModal" tabindex="-1" aria-labelledby="deleteMapelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteMapelModalLabel">Konfirmasi Hapus Mapel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus mapel ini dari kelas?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteMapelForm" action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var deleteMapelModal = document.getElementById('deleteMapelModal');
        deleteMapelModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var mapelId = button.getAttribute('data-mapel-id');
            var kelasId = button.getAttribute('data-kelas-id');

            var form = document.getElementById('deleteMapelForm');
            form.action = '/kelas/' + kelasId + '/mapel/' + mapelId;
        });
    </script>
@endsection
