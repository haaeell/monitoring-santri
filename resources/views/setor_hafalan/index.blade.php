@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h4>Setor Hafalan</h4>
                </div>
                <form id="kelas-form" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="kelas_id">Pilih Kelas</label>
                        <select name="kelas_id" id="kelas_id" class="form-control" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($kelas as $kelasItem)
                                <option value="{{ $kelasItem->id }}">{{ $kelasItem->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>

                <div id="kelas-details" style="display: none;">
                    <div id="mapel-section" class="form-group">
                        <label for="mapel_id">Pilih Mata Pelajaran</label>
                        <select name="mapel_id" id="mapel_id" class="form-control">
                            <option value="">-- Pilih Mata Pelajaran --</option>
                        </select>
                    </div>

                    <div id="santri-section" class="my-3">
                        <h4>Santri</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Santri</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="santri-list"></tbody>
                        </table>
                    </div>
                </div>

                <div class="modal fade" id="modalHafalan" tabindex="-1" aria-labelledby="modalHafalanLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-centered">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('setor.store') }}">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalHafalanLabel">Input Setoran Hafalan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="santri_id" id="santri_id">

                                    <div class="form-group">
                                        <label for="nama_hafalan">Nama Hafalan</label>
                                        <input type="text" name="nama_hafalan" id="nama_hafalan" class="form-control"
                                            required readonly>
                                    </div>

                                    <div class="form-group">
                                        <label for="mulai">Mulai</label>
                                        <input type="number" name="mulai" id="mulai" class="form-control" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="selesai">Selesai</label>
                                        <input type="number" name="selesai" id="selesai" class="form-control" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="total">Total</label>
                                        <input type="number" name="total" id="total" class="form-control" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="tanggal_setor">Tanggal Setor</label>
                                        <input type="date" name="tanggal_setor" id="tanggal_setor" class="form-control"
                                            required>
                                    </div>

                                    <div class="form-group">
                                        <label for="keterangan">Keterangan</label>
                                        <textarea name="keterangan" id="keterangan" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Simpan Setoran Hafalan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#kelas_id').on('change', function() {
            var kelasId = $(this).val();
            console.log(kelasId);

            if (kelasId) {
                $.get("{{ route('getMapelAndSantriByKelas') }}", {
                    kelas_id: kelasId
                }, function(response) {
                    console.log(response);
                    if (response.mapels.length > 0) {
                        $('#mapel_id').empty().append(
                            '<option value="">-- Pilih Mata Pelajaran --</option>');
                        response.mapels.forEach(function(mapel) {
                            $('#mapel_id').append('<option value="' + mapel.id + '">' + mapel
                                .nama_mapel + '</option>');
                        });
                        $('#mapel-section').show();
                    } else {
                        $('#mapel-section').hide();
                    }

                    if (response.santris.length > 0) {
                        $('#santri-list').empty();
                        response.santris.forEach(function(santri) {
                            console.log(response)
                            $('#santri-list').append(
                                '<tr>' +
                                '<td>' + santri.nama + '</td>' +
                                '<td><button class="btn btn-primary btn-sm" onclick="openHafalanModal(' +
                                santri.id + ', \'' + response.hafalan_id + '\', \'' + response
                                .nama_hafalan + '\')">Input Hafalan</button></td>' +
                                '</tr>'
                            );
                        });
                        $('#santri-section').show();
                        $('#kelas-details').show();
                    } else {
                        $('#santri-section').hide();
                    }
                });
            } else {
                $('#mapel-section').hide();
                $('#santri-section').hide();
                $('#kelas-details').hide();
            }
        });

        function openHafalanModal(santriId, hafalanId, namaHafalan) {
            $('#santri_id').val(santriId);
            $('#hafalan_id').val(hafalanId);
            $('#nama_hafalan').val(namaHafalan);
            $('#modalHafalan').modal('show');
        }
    </script>
@endsection
