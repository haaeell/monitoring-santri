@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Tambah Hafalan</h4>
                    <form method="POST" action="{{ route('hafalan.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="nama">Nama Hafalan</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="target">Target</label>
                            <input type="number" name="target" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="kelas_id">Kelas</label>
                            <input type="number" name="tingkatan" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
