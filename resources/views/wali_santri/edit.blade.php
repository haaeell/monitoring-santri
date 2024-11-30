@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Wali Santri</h4>
                    <form method="POST" action="{{ route('wali.update', $wali_santri->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="nama_ayah">Nama Ayah</label>
                            <input type="text" name="nama_ayah" class="form-control" value="{{ $wali_santri->nama_ayah }}" required>
                        </div>
                        <div class="form-group">
                            <label for="nama_ibu">Nama Ibu</label>
                            <input type="text" name="nama_ibu" class="form-control" value="{{ $wali_santri->nama_ibu }}" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <input type="text" name="alamat" class="form-control" value="{{ $wali_santri->alamat }}" required>
                        </div>
                        <div class="form-group">
                            <label for="telp">No Telepon</label>
                            <input type="text" name="telp" class="form-control" value="{{ $wali_santri->telp }}" required>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
