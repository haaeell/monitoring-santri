<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Nilai;
use App\Models\SantriKelas;
use App\Models\Mapel;
use App\Models\Santri;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $kelas = Kelas::where('wali_kelas_id', Auth::user()->guru->waliKelas->id)->get();
        $tahunAjaran = TahunAjaran::all();

        $kelasId = $request->kelas_id;
        $tahunAjaranId = $request->tahun_ajaran_id;
        $selectedKelas = $kelasId ? Kelas::find($kelasId) : null;
        $selectedTahunAjaran = $tahunAjaranId ? TahunAjaran::find($tahunAjaranId) : null;

        $rekap = [];

        if ($selectedKelas && $selectedTahunAjaran) {
            $rekap = Santri::where('kelas_id', $kelasId)->get();
        }

        return view('nilai.index', compact('kelas', 'tahunAjaran', 'rekap', 'selectedKelas', 'selectedTahunAjaran'));
    }

    public function show($santri_id, Request $request)
    {
        $santri = Santri::find($santri_id);
        $kelasId = request('kelas_id');
        $tahunAjaranId = request('tahun_ajaran_id');

        $tahunAjaran = TahunAjaran::findOrFail($tahunAjaranId)->nama;
        $mapel = Mapel::findOrFail($request->mapel_id)->nama_mapel;

        $kelas = Kelas::findOrFail($kelasId);
        $mapels = $kelas->mapels;

        $absensi = Absensi::where('santri_id', $santri_id)->where('status', 'H')->where('tahun_ajaran_id', $tahunAjaranId)->where('kelas_id', $kelasId)->get();
        dd($absensi);
        return view('nilai.show', compact('santri', 'absensi', 'mapel', 'tahunAjaran'));
    }
    public function detail($santri_id, Request $request)
    {
        $santri = Santri::find($santri_id);
        $kelasId = request('kelas_id');
        $tahunAjaranId = request('tahun_ajaran_id');

        $tahunAjaran = TahunAjaran::findOrFail($tahunAjaranId)->nama;
        $kelas = Kelas::findOrFail($kelasId);

        $absensi = Absensi::where('santri_id', $santri_id)->where('status', 'H')->where('tahun_ajaran_id', $tahunAjaranId)->where('kelas_id', $kelasId)->get();
        $nilai = Nilai::with('mapel')->where('santri_id', $santri_id)->where('tahun_ajaran_id', $tahunAjaranId)->get();

        return view('nilai.show', compact('santri', 'absensi', 'nilai', 'tahunAjaran', 'kelas'));
    }
}
