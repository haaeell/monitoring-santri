@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Mapel di Kelas {{ $kelas->nama_kelas }}</h4>
                    <a href="{{ route('kelas.index') }}" class="btn btn-outline-secondary mb-3">Kembali ke Kelas</a>

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

                    <hr>

                    <div class="table-responsive">
                        <table id="dataTable" class="display expandable-table" style="width:100%">
                            <thead>
                                <tr class="text-center">
                                    <th>Nama Mapel</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mapels as $mapel)
                                    <tr>
                                        <td>{{ $mapel->nama_mapel }}</td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <form action="{{ route('kelas.removeMapel', [$kelas->id, $mapel->id]) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-danger btn-sm text-white fw-bold"
                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus">
                                                        <i class="ti-trash btn-icon-append"></i>
                                                    </button>
                                                </form>
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
@endsection
