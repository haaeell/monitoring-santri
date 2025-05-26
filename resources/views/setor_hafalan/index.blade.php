@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h4>Setor Hafalan</h4>
                </div>

                {{-- Form Filter Kelas --}}
                <form method="GET" action="{{ route('setor.index') }}">
                    <div class="form-group">
                        <label for="kelas_id">Pilih Kelas</label>
                        <select name="kelas_id" id="kelas_id" class="form-control" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($kelas as $kelasItem)
                                <option value="{{ $kelasItem->id }}"
                                    {{ request('kelas_id') == $kelasItem->id || $selectedKelas ? 'selected' : '' }}>
                                    {{ $kelasItem->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mt-2">
                        <label for="tahun_ajaran_id">Pilih Tahun Ajaran</label>
                        <select name="tahun_ajaran_id" id="tahun_ajaran_id" class="form-control" required
                            onchange="this.form.submit()">
                            <option value="">-- Pilih Tahun Ajaran --</option>
                            @foreach ($tahunAjaran as $tahun)
                                <option value="{{ $tahun->id }}"
                                    {{ request('tahun_ajaran_id') == $tahun->id || $selectedTahunAjaran ? 'selected' : '' }}>
                                    {{ $tahun->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>

                @if ($selectedKelas)
                    <h5 class="mt-3">Nama Hafalan: {{ $selectedKelas->hafalan->nama }} </h5>
                    <h5 class="mt-3">Target : {{ $selectedKelas->hafalan->target }}</h5>

                    <div class="mt-3">
                        <a href="{{ route('setor.riwayat', ['kelas_id' => $selectedKelas->id, 'tahun_ajaran_id' => $selectedTahunAjaran->id]) }}"
                            class="btn btn-success text-white fw-bold">
                            Lihat Riwayat Mingguan
                        </a>
                    </div>


                    <form method="POST" action="{{ route('setor.store') }}">
                        @csrf
                        <input type="hidden" name="kelas_id" value="{{ $selectedKelas->id }}">
                        <input type="hidden" name="hafalan_id" value="{{ $selectedKelas->hafalan->id }}">
                        <input type="hidden" name="tahun_ajaran_id" value="{{ $selectedTahunAjaran->id }}">
                        <input type="hidden" name="nama_hafalan" value="{{ $selectedKelas->hafalan->nama }}">

                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama Santri</th>
                                        <th>NIS</th>
                                        <th>Mulai</th>
                                        <th>Selesai</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($santris as $santri)
                                        @php
                                            $lastSetoran = $santri->hafalan->sortByDesc('tanggal_setor')->first();
                                            $mulai = $lastSetoran ? $lastSetoran->selesai : 0;
                                        @endphp
                                        <tr>
                                            <td>{{ $santri->nama }}</td>
                                            <td>{{ $santri->nis }}</td>

                                            <td>
                                                <input type="number" name="mulai[{{ $santri->id }}]"
                                                    class="form-control mulai" value="{{ $mulai }}">
                                            </td>

                                            <td>
                                                <input type="number" name="selesai[{{ $santri->id }}]"
                                                    class="form-control selesai" value="0" style="width:100%;">
                                            </td>

                                            <td>
                                                <span class="total" style="width:100%;">0</span>
                                                <input type="hidden" class="form-control total_hidden"
                                                    name="total[{{ $santri->id }}]" value="0" readonly>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Simpan Data</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.selesai').on('input', function() {
                var row = $(this).closest('tr');
                var mulai = parseInt(row.find('.mulai').val()) || 0;
                var selesai = parseInt(row.find('.selesai').val()) || 0;

                var errorMessage = row.find('.invalid-feedback');

                if (selesai <= mulai) {
                    if (errorMessage.length === 0) {
                        row.find('.selesai').addClass('is-invalid');
                        row.find('.selesai').after(
                            '<div class="invalid-feedback">Jumlah selesai harus lebih besar dari mulai</div>'
                        );
                    }
                    row.find('.total').text('Error');
                    row.find('.total_hidden').val('');
                } else {
                    row.find('.selesai').removeClass('is-invalid');
                    row.find('.total').text(selesai - mulai);
                    row.find('.total_hidden').val(selesai - mulai);
                    errorMessage.remove();
                }
            });
        });
    </script>
@endsection
