@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Form Data Guru</h4>
                    <form class="form-sample" method="POST" action="{{ route('guru.store') }}" enctype="multipart/form-data">
                        @csrf
                        <p class="card-description"> Informasi Pribadi </p>

                        <!-- Nama Field -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-3 col-form-label">Nama <span class="text-danger">*</span> <span
                                            style="float: right">:</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="nama" class="form-control form-control-sm" required
                                            value="{{ old('nama') }}" />
                                        @error('nama')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-3 col-form-label">email <span class="text-danger">*</span> <span
                                            style="float: right">:</span></label>
                                    <div class="col-sm-9">
                                        <input type="email" name="email" class="form-control form-control-sm" required
                                            value="{{ old('email') }}" />
                                        @error('email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-3 col-form-label">No Telepon <span class="text-danger">*</span> <span
                                            style="float: right">:</span></label>
                                    <div class="col-sm-9">
                                        <input type="number" name="no_telepon" class="form-control form-control-sm" required
                                            value="{{ old('no_telepon') }}" />
                                        @error('no_telepon')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- NIP Field -->
                            <div class="col-md-6">
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-3 col-form-label">NIP <span class="text-danger">*</span> <span
                                            style="float: right">:</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="nip" class="form-control form-control-sm" required
                                            value="{{ old('nip') }}" />
                                        @error('nip')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Jenis Kelamin Field -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-3 col-form-label">Jenis Kelamin <span class="text-danger">*</span>
                                        <span style="float: right">:</span></label>
                                    <div class="col-sm-9">
                                        <select name="jenis_kelamin" class="form-select" required>
                                            <option value="Laki-laki"
                                                {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                                            </option>
                                            <option value="Perempuan"
                                                {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan
                                            </option>
                                        </select>
                                        @error('jenis_kelamin')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-3 col-form-label">Pendidikan Terakhir <span class="text-danger">*</span> <span
                                            style="float: right">:</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="pendidikan_terakhir" class="form-control form-control-sm" rows="5" required>{{ old('pendidikan_terakhir') }}</input>
                                        @error('pendidikan_terakhir')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Alamat Field -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-3 col-form-label">Alamat <span class="text-danger">*</span> <span
                                            style="float: right">:</span></label>
                                    <div class="col-sm-9">
                                        <textarea name="alamat" class="form-control form-control-sm" rows="5" required>{{ old('alamat') }}</textarea>
                                        @error('alamat')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <!-- Foto Field -->
                            <div class="col-md-6">
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-3 col-form-label">Foto <span style="float: right">:</span></label>
                                    <div class="col-sm-9">
                                        <input type="file" name="foto" id="foto" class="form-control form-control-sm" />
                                        @error('foto')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-3 col-form-label"></label>
                                    <div class="col-sm-9">
                                        <img id="imagePreview" src="#" alt="Image Preview"
                                            style="display:none; width: 150px; height: auto;" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="/guru" class="btn btn-outline-primary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#foto').change(function() {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').attr('src', e.target.result).show();
                }
                reader.readAsDataURL(this.files[0]);
            });
        });
    </script>
@endsection
