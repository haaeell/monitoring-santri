@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Tambah Kelas</h4>
                    <form method="POST" action="{{ route('kelas.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="nama_kelas">Nama Kelas</label>
                            <input type="text" name="nama_kelas" id="nama_kelas" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="tingkatan">Tingkatan</label>
                            <input type="text" name="tingkatan" id="tingkatan" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="sub_kelas">Sub kelas</label>
                            <input type="text" name="sub_kelas" id="sub_kelas" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="wali_kelas_id">Wali Kelas</label>
                            <select name="wali_kelas_id" id="wali_kelas_id" class="form-control" required>
                                <option value="">Pilih Wali Kelas</option>
                                @foreach($gurus as $guru)
                                    <option value="{{ $guru->id }}">{{ $guru->user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('kelas.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
