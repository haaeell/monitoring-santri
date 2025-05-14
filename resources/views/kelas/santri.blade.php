@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Santri di Kelas {{ $kelas->nama_kelas }}</h4>
                    <a href="{{ route('kelas.index') }}" class="btn btn-outline-secondary mb-3">Kembali ke Kelas</a>

                    <h5>Pilih Santri untuk Kelas Ini</h5>
                    <form action="{{ route('kelas.addSantri', $kelas->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <select class="form-select select2" name="santri_id[]" multiple>
                                @foreach ($allSantris as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }} - {{ $item->nis }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Tambahkan Santri ke Kelas</button>
                    </form>

                    <hr>

                    <div class="table-responsive">
                        <table id="dataTable" class="display expandable-table" style="width:100%">
                            <thead>
                                <tr class="text-center">
                                    <th class="text-center">Nama Santri</th>
                                    <th class="text-center">NIS</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($santris as $santri)
                                    <tr>
                                        <td class="text-center">{{ $santri->nama }}</td>
                                        <td class="text-center">{{ $santri->nis }}</td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <button type="button" 
                                                        class="btn btn-danger btn-sm text-white fw-bold"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deleteModal"
                                                        data-santri-id="{{ $santri->id }}"
                                                        data-kelas-id="{{ $kelas->id }}"
                                                        data-bs-placement="top" 
                                                        title="Hapus">
                                                    <i class="ti-trash btn-icon-append"></i>
                                                </button>
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

    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus Santri</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus santri ini dari kelas?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteSantriForm" action="" method="POST">
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
    var deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var santriId = button.getAttribute('data-santri-id');
        var kelasId = button.getAttribute('data-kelas-id');
        
        var form = document.getElementById('deleteSantriForm');
        form.action = '/kelas/' + kelasId + '/santri/' + santriId;
    });
</script>
@endsection
