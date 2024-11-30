@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Form Data Santri</h4>
                    <form class="form-sample" method="POST" action="{{ route('santri.store') }}" enctype="multipart/form-data">
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

                            <!-- NIS Field -->
                            <div class="col-md-6">
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-3 col-form-label">NIS <span class="text-danger">*</span> <span
                                            style="float: right">:</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="nis" class="form-control form-control-sm" required
                                            value="{{ old('nis') }}" />
                                        @error('nis')
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

                            <!-- Tanggal Lahir Field -->
                            <div class="col-md-6">
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-3 col-form-label">Tanggal Lahir <span class="text-danger">*</span>
                                        <span style="float: right">:</span></label>
                                    <div class="col-sm-9">
                                        <input type="date" name="tanggal_lahir" class="form-control form-control-sm"
                                            required value="{{ old('tanggal_lahir') }}" />
                                        @error('tanggal_lahir')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-3 col-form-label">Nama Ayah <span class="text-danger">*</span> <span
                                            style="float: right">:</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="nama_ayah" class="form-control form-control-sm" required
                                            value="{{ old('nama_ayah') }}" />
                                        @error('nama_ayah')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-3 col-form-label">Nama Ibu <span
                                            style="float: right">:</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="nama_ibu" class="form-control form-control-sm"
                                            value="{{ old('nama_ibu') }}" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Kamar Field -->
                            <div class="col-md-6">
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-3 col-form-label">Kamar <span class="text-danger">*</span> <span
                                            style="float: right">:</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="kamar" class="form-control form-control-sm" required
                                            value="{{ old('kamar') }}" />
                                        @error('kamar')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Telp Field -->
                            <div class="col-md-6">
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-3 col-form-label">Nomor Telepon <span
                                            style="float: right">:</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="telp" class="form-control form-control-sm"
                                            value="{{ old('telp') }}" />
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
                                        <input type="file" id="foto" class="form-control form-control-sm" />
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
                        <a href="/santri" class="btn btn-outline-primary">Batal</a>
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
