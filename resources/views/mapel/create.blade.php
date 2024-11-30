@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Tambah Mapel</h4>
                    <form method="POST" action="{{ route('mapel.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="nama_mapel">Nama Mapel</label>
                            <input type="text" name="nama_mapel" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="guru_id">Pilih Guru</label>
                            <select name="guru_id" class="form-control" required>
                                @foreach ($guru as $guru)
                                    <option value="{{ $guru->id }}">{{ $guru->user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
