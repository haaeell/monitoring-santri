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
                            <label for="kelas_id">Pilih Kelas</label>
                            <select name="kelas_id" class="form-control" required>
                                @foreach ($kelas as $kls)
                                    <option value="{{ $kls->id }}" {{ $kls->id == $hafalan->kelas_id ? 'selected' : '' }}>
                                        {{ $kls->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
