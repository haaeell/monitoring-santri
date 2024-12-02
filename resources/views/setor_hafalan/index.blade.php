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
                    <div id="hafalan-section" class="form-group">
                        <label for="hafalan_id">Nama Hafalan</label>
                        <input type="text" id="namaHafalan" name="nama_hafalan" class="form-control" readonly>
                    </div>

                    <div id="santri-section" class="my-3">
                        <div class="col-md-4 justify-content-end">
                            <input type="text" id="searchSantri" class="form-control rounded-5" placeholder="Search Santri..."
                                style="margin-bottom: 10px;">
                        </div>

                        <form id="input-hafalan-form">
                            @csrf
                            <table class="table table-bordered table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Nama Santri</th>
                                        <th>Mulai</th>
                                        <th>Selesai</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody id="santri-list"></tbody>
                            </table>
                            <div class="text-end">
                                <button type="submit" id="submit-button" class="btn btn-primary mt-2 text-end"
                                    style="display: none;">
                                    Simpan Setoran Hafalan
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

            // When Kelas is selected, fetch Santri data
            $('#kelas_id').on('change', function() {
                var kelasId = $(this).val();

                if (kelasId) {
                    $.get("{{ route('getMapelAndSantriByKelas') }}", {
                        kelas_id: kelasId
                    }, function(response) {

                        $('#namaHafalan').val(response.nama_hafalan).show();

                        if (response.santris.length > 0) {
                            $('#santri-list').empty();

                            response.santris.forEach(function(santri) {
                                var mulaiValue = santri.setoran_today ? santri.setoran_today
                                    .mulai : '';
                                var selesaiValue = santri.setoran_today ? santri
                                    .setoran_today.selesai : '';
                                var totalValue = santri.setoran_today ? santri.setoran_today
                                    .total : '';

                                $('#santri-list').append(`
                                    <tr class="santri-row">
                                        <td>${santri.nama}</td>
                                        <td><input type="number" name="mulai[${santri.id}]" class="form-control mulai" style="width:100%;" value="${mulaiValue}"></td>
                                        <td><input type="number" name="selesai[${santri.id}]" class="form-control selesai" style="width:100%;" value="${selesaiValue}"></td>
                                        <td><input type="number" name="total[${santri.id}]" class="form-control total" style="width:100%;" value="${totalValue}" readonly></td>
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

            // Realtime Search for Santri
            $('#searchSantri').on('input', function() {
                var searchTerm = $(this).val().toLowerCase();

                // Filter Santri rows based on search term
                $('#santri-list tr').each(function() {
                    var santriName = $(this).find('td').first().text().toLowerCase();
                    if (santriName.indexOf(searchTerm) !== -1) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            // Calculate Total when Mulai or Selesai changes
            $(document).on('input', '.mulai, .selesai', function() {
                var row = $(this).closest('tr');
                var mulai = row.find('.mulai').val();
                var selesai = row.find('.selesai').val();

                if (mulai && selesai) {
                    var total = selesai - mulai;
                    row.find('.total').val(total);
                } else {
                    row.find('.total').val('');
                }
            });

            // Handle form submission
            $('#input-hafalan-form').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                var namaHafalan = $('#namaHafalan').val();
                formData += '&nama_hafalan=' + encodeURIComponent(namaHafalan);

                $.post("{{ route('setor.store') }}", formData, function(response) {
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
