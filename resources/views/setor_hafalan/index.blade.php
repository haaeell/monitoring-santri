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
                                    {{ request('kelas_id') == $kelasItem->id ? 'selected' : '' }}>
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
                                    {{ request('tahun_ajaran_id') == $tahun->id ? 'selected' : '' }}>
                                    {{ $tahun->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>

                @if ($selectedKelas)
                    @php
                        $hafalan = \App\Models\Hafalan::where('kelas_id', $selectedKelas->id)->first();
                    @endphp
                    <h5 class="mt-3">Nama Hafalan: {{ $hafalan->nama }} </h5>
                    <h5 class="mt-3">Target : {{ $hafalan->target }} </h5>
                    <form method="POST" action="{{ route('setor.store') }}">
                        @csrf
                        <input type="hidden" name="kelas_id" value="{{ $selectedKelas->id }}">
                        <input type="hidden" name="hafalan_id" value="{{ $hafalan->id }}">
                        <input type="hidden" name="tahun_ajaran_id" value="{{ $selectedTahunAjaran->id }}">
                        <input type="hidden" name="nama_hafalan" value="{{ $hafalan->nama }}">
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-striped" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>Nama Santri</th>
                                        <th>NPM</th>
                                        <th>Mulai</th>
                                        <th>Selesai</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($santris as $santri)
                                    @php
                                        $setoranHariIni = $santri->hafalan->first();
                                    @endphp
                                    <tr>
                                        <td>{{ $santri->nama }}</td>
                                        <td>{{ $santri->nis }}</td>
                                        <td><input type="number" name="mulai[{{ $santri->id }}]" class="form-control mulai"
                                                value="{{ $setoranHariIni ? $setoranHariIni->mulai : '' }}" style="width:100%;"></td>
                                        <td><input type="number" name="selesai[{{ $santri->id }}]" class="form-control selesai"
                                                value="{{ $setoranHariIni ? $setoranHariIni->selesai : '' }}" style="width:100%;"></td>
                                        <td><input type="text" class="form-control total" name="total[{{ $santri->id }}]" style="width:100%;"
                                                value="{{ $setoranHariIni ? $setoranHariIni->total : '' }}" readonly></td>
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
            $('.mulai, .selesai').on('input', function() {
                var row = $(this).closest('tr');
                var mulai = parseInt(row.find('.mulai').val()) || 0;
                var selesai = parseInt(row.find('.selesai').val()) || 0;
                var total = selesai - mulai;

                row.find('.total').val(total >= 0 ? total : 0); // Pastikan tidak negatif
            });
        });
    </script>
@endsection
