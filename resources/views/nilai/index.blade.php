@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h4>Nilai </h4>
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
                    <div id="hafalan-section" class="form-group">
                        <label for="nama_mapel">Nama Mata Pelajaran</label>
                        <input type="text" id="namaMapel" name="nama_mapel" class="form-control" readonly>
                        <input type="hidden" id="mapelId" name="mapel_id" class="form-control" readonly>
                        <input type="hidden" id="kelasId" name="mapel_id" class="form-control" readonly>
                    </div>

                    <div id="santri-section" class="my-3">
                        <div class="col-md-4 justify-content-end">
                            <input type="text" id="searchSantri" class="form-control rounded-5" placeholder="Cari Nama Santri..."
                                style="margin-bottom: 10px;">
                        </div>

                        <form id="input-nilai-form">
                            @csrf
                            <table class="table table-bordered table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Santri</th>
                                        <th>NIS</th>
                                        <th>Hadir</th>
                                        <th>Izin</th>
                                        <th>Sakit</th>
                                        <th>Alfa</th>
                                        <th>Nilai UTS</th>
                                        <th>Nilai UAS</th>
                                        <th>Hafalan</th>
                                    </tr>
                                </thead>
                                <tbody id="santri-list"></tbody>
                            </table>
                            <div class="text-end">
                                <button type="submit" id="submit-button" class="btn btn-primary mt-2 text-end"
                                    style="display: none;">
                                    Simpan Nilai
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#kelas_id').on('change', function() {
                var kelasId = $(this).val();

                if (kelasId) {
                    $.get("{{ route('getMapelAndSantriByKelas.nilai') }}", {
                        kelas_id: kelasId
                    }, function(response) {
                        console.log(response);
                        $('#namaMapel').val(response.mapel.nama_mapel).show();
                        $('#mapelId').val(response.mapel.id).show();
                        $('#kelasId').val(response.kelas.id).show();
                        
                        if (response.santris.length > 0) {
                            $('#santri-list').empty();

                            response.santris.forEach(function(santri) {
                                var presensiValue = santri.nilai ? santri.nilai
                                    .presensi : '';
                                var hafalanValue = santri.nilai ? santri
                                    .nilai.hafalan : '';
                                var uasValue = santri.nilai ? santri
                                    .nilai.nilai_uas : '';
                                var utsValue = santri.nilai ? santri
                                    .nilai.nilai_uts : '';

                                $('#santri-list').append(`
                                    <tr class="santri-row">
                                        <td>${santri.nama}</td>
                                        <td>${santri.nis}</td>
                                        <td>${santri.presensi.hadir}</td>
                                        <td>${santri.presensi.izin}</td>
                                        <td>${santri.presensi.sakit}</td>
                                        <td>${santri.presensi.alfa}</td>
                                        <td><input type="number" name="nilai_uts[${santri.id}]" class="form-control selesai" style="width:100%;" value="${utsValue}"></td>
                                        <td><input type="number" name="nilai_uas[${santri.id}]" class="form-control selesai" style="width:100%;" value="${uasValue}"></td>
                                        <td><input type="number" name="hafalan[${santri.id}]" class="form-control selesai" style="width:100%;" value="${hafalanValue}"></td>
                                        <input type="hidden" name="hadir[${santri.id}]" class="form-control" style="width:100%;" value="${presensiValue}">
                                    </tr>
                                `);
                            });
                            $('#santri-section').show();
                            $('#kelas-details').show();
                            $('#submit-button').show();
                        } else {
                            $('#santri-section').hide();
                        }
                    });
                } else {
                    $('#santri-section').hide();
                    $('#kelas-details').hide();
                    $('#submit-button').hide();
                }
            });

            $('#searchSantri').on('input', function() {
                var searchTerm = $(this).val().toLowerCase();

                $('#santri-list tr').each(function() {
                    var santriName = $(this).find('td').first().text().toLowerCase();
                    if (santriName.indexOf(searchTerm) !== -1) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            $('#input-nilai-form').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                var mapelId = $('#mapelId').val();
                var kelasId = $('#kelasId').val();
                formData += '&mapel_id=' + encodeURIComponent(mapelId);
                formData += '&kelas_id=' + encodeURIComponent(kelasId);

                $.post("{{ route('nilai.store') }}", formData, function(response) {
                    if (response.success) {
                        swal.fire('Success', response.message, 'success');
                    } else {
                        swal.fire('Error', response.message, 'error');
                    }
                });
            });
        });
    </script>
@endsection
