@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Hafalan</h4>
                    <form method="POST" action="{{ route('hafalan.update', $hafalan->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="nama">Nama Hafalan</label>
                            <input type="text" name="nama" class="form-control" value="{{ $hafalan->nama }}" required>
                        </div>

                        <div class="form-group">
                            <label for="target">Target</label>
                            <input type="number" name="target" value="{{ $hafalan->target }}" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="kelas_id">Kelas</label>
                            <input type="number" name="tingkatan" value="{{ $hafalan->tingkatan }}" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
