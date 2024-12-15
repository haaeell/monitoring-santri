@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="card-title mb-4">
                        <h3 class="text-center text-primary fw-bold">Absensi Kelas</h3>
                    </div>

                    <form id="kelas-form" method="POST">
                        @csrf
                        <div class="row d-flex justify-content-between">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="kelas_id" class="font-weight-bold">Pilih Kelas</label>
                                    <select name="kelas_id" id="kelas_id" class="form-control" required>
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach ($kelas as $kelasItem)
                                            <option value="{{ $kelasItem->id }}">{{ $kelasItem->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tanggal" class="font-weight-bold">Pilih Tanggal</label>
                                    <input type="date" id="tanggal" name="tanggal" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div id="kelas-details" class="mt-4" style="display: none;">
                        <div id="santri-section" class="my-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <div id="absensi-summary" class="bg-light p-3 rounded mb-4" style="display: none;">
                                        <h4 class="my-3 fw-semibold">Ringkasan Absensi</h4>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Status</th>
                                                        <th>Jumlah</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Hadir</td>
                                                        <td><span id="jumlah-hadir">0</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Tidak Hadir (Alfa)</td>
                                                        <td><span id="jumlah-alfa">0</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Izin</td>
                                                        <td><span id="jumlah-izin">0</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Sakit</td>
                                                        <td><span id="jumlah-sakit">0</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-6 bg-light p-3 rounded mb-4">
                                    <div id="hafalan-section" class="form-group">
                                        <h4 for="pembahasan" class="fw-bold my-3">Nama Mata Pelajaran</h4>
                                        <input type="text" id="namaMapel" name="nama_mapel" class="form-control"
                                            readonly>
                                        <input type="hidden" id="mapelId" name="mapel_id" class="form-control" readonly>
                                    </div>
                                    <div class="">
                                        <h4 for="pembahasan" class="fw-bold my-3">Pembahasan Kelas</h4>
                                        <textarea id="pembahasan" name="pembahasan" class="form-control" rows="4"
                                            placeholder="Tuliskan pembahasan materi kelas hari ini..." required></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <input type="text" id="searchSantri" class="form-control rounded-4"
                                    placeholder="Cari Santri...">
                            </div>

                            <form id="input-absensi-form" class="mt-3">
                                @csrf
                                <div class="bg-light p-4 rounded">
                                    <table class="table table-striped table-bordered mt-3">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Nama Santri</th>
                                                <th>Status</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody id="santri-list"></tbody>
                                    </table>
                                </div>

                                <div class="text-end">
                                    <button type="submit" id="submit-button" class="btn btn-primary mt-3"
                                        style="display: none;">
                                        Simpan Absensi
                                    </button>
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
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            const today = new Date().toISOString().split('T')[0];
            $('#tanggal').val(today);

            $('#kelas_id').on('change', function() {
                var kelasId = $(this).val();
                var tanggal = $('#tanggal').val();

                if (kelasId) {
                    $.get("{{ route('getMapelAndSantriByKelas.absensi') }}", {
                        kelas_id: kelasId,
                        tanggal: tanggal,
                    }, function(response) {
                        console.log(response);
                        $('#jumlah-hadir').text(response.jumlahHadir);
                        $('#jumlah-alfa').text(response.jumlahAlfa);
                        $('#jumlah-izin').text(response.jumlahIzin);
                        $('#jumlah-sakit').text(response.jumlahSakit);
                        $('#absensi-summary').show();

                        $('#namaMapel').val(response.mapel.nama_mapel).show();
                        $('#mapelId').val(response.mapel.id).show();

                        if (response.pembahasan) {
                            $('#pembahasan').val(response.pembahasan
                                .pembahasan);
                        } else {
                            $('#pembahasan').val('');
                        }

                        if (response.santris.length > 0) {
                            $('#santri-list').empty();

                            response.santris.forEach(function(santri) {
                                var statusValue = santri.absensi_today ? santri
                                    .absensi_today.status : '';
                                var keteranganValue = santri.absensi_today ? santri
                                    .absensi_today.keterangan : '';

                                $('#santri-list').append(`
                                <tr class="santri-row">
                                    <td>${santri.nama}</td>
                                    <td>
                                        <select name="status[${santri.id}]" class="form-control">
                                            <option value="hadir" ${statusValue === 'hadir' ? 'selected' : ''}>Hadir</option>
                                            <option value="alfa" ${statusValue === 'alfa' ? 'selected' : ''}>Tidak Hadir</option>
                                            <option value="izin" ${statusValue === 'izin' ? 'selected' : ''}>Izin</option>
                                            <option value="sakit" ${statusValue === 'sakit' ? 'selected' : ''}>Sakit</option>
                                        </select>   
                                    </td>
                                    <td><input type="text" name="keterangan[${santri.id}]" class="form-control" value="${keteranganValue}"></td>
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

            $('#tanggal').on('change', function() {
                $('#kelas_id').trigger('change');
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

            $('#input-absensi-form').on('submit', function(e) {
                e.preventDefault();

                var formData = $(this).serialize();

                var kelasId = $('#kelas_id').val();
                var mapelId = $('#mapelId').val();
                var pembahasan = $('#pembahasan').val();

                formData += '&kelas_id=' + encodeURIComponent(kelasId);
                formData += '&mapel_id=' + encodeURIComponent(mapelId);
                formData += '&pembahasan=' + encodeURIComponent(pembahasan);

                $.post("{{ route('absensi.store') }}", formData, function(response) {
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
