@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Data Santri</h4>
                    <form class="form-sample" method="POST" action="{{ route('santri.update', $santri->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <p class="card-description"> Informasi Pribadi </p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-3 col-form-label">Nama <span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="nama" class="form-control form-control-sm"
                                            value="{{ $santri->nama }}" required />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-3 col-form-label">NIS <span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="nis" class="form-control form-control-sm"
                                            value="{{ $santri->nis }}" required />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-3 col-form-label">Jenis Kelamin <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <select name="jenis_kelamin" class="form-select" required>
                                            <option value="Laki-laki"
                                                {{ $santri->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                                            </option>
                                            <option value="Perempuan"
                                                {{ $santri->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-3 col-form-label">Tanggal Lahir <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="date" name="tanggal_lahir" class="form-control form-control-sm"
                                            value="{{ $santri->tanggal_lahir }}" required />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-3 col-form-label">Kamar <span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="kamar" class="form-control form-control-sm"
                                            value="{{ $santri->kamar }}" required />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-3 col-form-label">Nomor Telepon</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="telp" class="form-control form-control-sm"
                                            value="{{ $santri->telp }}" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-3 col-form-label">Alamat <span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <textarea name="alamat" class="form-control form-control-sm" rows="5" required>{{ $santri->alamat }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-3 col-form-label">Foto</label>
                                    <div class="col-sm-9">
                                        <input type="file" name="foto" id="foto"
                                            class="form-control form-control-sm" />
                                        <small class="form-text text-muted">Kosongkan jika tidak ingin mengganti
                                            foto.</small>
                                            <div class="form-check mt-2">
    <input type="checkbox" name="remove_foto" value="1" class="form-check-input" id="removeFoto">
    <label class="form-check-label" for="removeFoto">Hapus Foto</label>
</div>

                                    </div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-3 col-form-label"></label>
                                    <div class="col-sm-9">
                                        @if ($santri->foto)
                                            <img id="imagePreview" src="{{ asset('storage/' . $santri->foto) }}"
                                                alt="Current Image" style="width: 150px; height: auto;" />
                                        @else
                                            <p>No image available</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                        <button type="submit" class="btn btn-primary">Perbarui</button>
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
                if (this.files && this.files[0]) {
                    reader.readAsDataURL(this.files[0]);
                }
            });
        });
    </script>
@endsection
