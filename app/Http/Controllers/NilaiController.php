<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Hafalan;
use App\Models\Kelas;
use App\Models\Nilai;
use App\Models\Santri;
use App\Models\TahunAjaran;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Http\Request;
class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $guru = Guru::where('user_id', $user->id)->first();
        $kelas = Kelas::whereHas('mapels', function ($query) use ($guru) {
            $query->where('guru_id', $guru->id);
        })->get();
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
    public function detail($santri_id, Request $request)
    {
        $santri = Santri::find($santri_id);
        $kelasId = request('kelas_id');
        $tahunAjaranId = request('tahun_ajaran_id');

        $tahunAjaran = TahunAjaran::findOrFail($tahunAjaranId)->nama;
        $kelas = Kelas::findOrFail($kelasId);

        $absensi = Absensi::where('santri_id', $santri_id)->where('status', 'H')->where('tahun_ajaran_id', $tahunAjaranId)->where('kelas_id', $kelasId)->get();
        $nilai = Nilai::with('mapel')->where('santri_id', $santri_id)->where('tahun_ajaran_id', $tahunAjaranId)->get();

        $hadir = Absensi::where('santri_id', $santri->id)
            ->where('kelas_id', $kelas->id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->where('status', 'H')
            ->count();

        $izin = Absensi::where('santri_id', $santri->id)
            ->where('kelas_id', $kelas->id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->where('status', 'I')
            ->count();

        $sakit = Absensi::where('santri_id', $santri->id)
            ->where('kelas_id', $kelas->id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->where('status', 'S')
            ->count();

        $alfa = Absensi::where('santri_id', $santri->id)
            ->where('kelas_id', $kelas->id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->where('status', 'A')
            ->count();

        $totalHafalan = $santri->hafalan->sum('total');
        $namaHafalan = Hafalan::where('kelas_id', $kelasId)->pluck('nama')->first();
        $target = Hafalan::where('kelas_id', $kelasId)->pluck('target')->first();
        $keteranganHafalan = ($totalHafalan >= $target) ? 'Tercapai' : 'Belum Tercapai';
        $statusKenaikan = ($totalHafalan >= $target) ? 'Naik Kelas' : 'Tidak Naik Kelas';

        return view('nilai.show', compact('santri', 'absensi', 'nilai', 'tahunAjaran', 'kelas', 'hadir', 'izin', 'sakit', 'alfa', 'totalHafalan', 'namaHafalan', 'target', 'keteranganHafalan', 'statusKenaikan'));
    }

    public function generatePDF($santri_id, Request $request)
    {
        $santri = Santri::find($santri_id);
        $kelasId = request('kelas_id');
        $tahunAjaranId = request('tahun_ajaran_id');

        $tahunAjaran = TahunAjaran::findOrFail($tahunAjaranId)->nama;
        $kelas = Kelas::findOrFail($kelasId);

        $absensi = Absensi::where('santri_id', $santri_id)->where('status', 'H')->where('tahun_ajaran_id', $tahunAjaranId)->where('kelas_id', $kelasId)->get();
        $nilai = Nilai::with('mapel')->where('santri_id', $santri_id)->where('tahun_ajaran_id', $tahunAjaranId)->get();

        $hadir = Absensi::where('santri_id', $santri->id)
            ->where('kelas_id', $kelas->id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->where('status', 'H')
            ->count();

        $izin = Absensi::where('santri_id', $santri->id)
            ->where('kelas_id', $kelas->id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->where('status', 'I')
            ->count();

        $sakit = Absensi::where('santri_id', $santri->id)
            ->where('kelas_id', $kelas->id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->where('status', 'S')
            ->count();

        $alfa = Absensi::where('santri_id', $santri->id)
            ->where('kelas_id', $kelas->id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->where('status', 'A')
            ->count();

        $totalHafalan = $santri->hafalan->sum('total');
        $namaHafalan = Hafalan::where('kelas_id', $kelasId)->pluck('nama')->first();
        $target = Hafalan::where('kelas_id', $kelasId)->pluck('target')->first();
        $keteranganHafalan = ($totalHafalan >= $target) ? 'Tercapai' : 'Belum Tercapai';
        $statusKenaikan = ($totalHafalan >= $target) ? 'Naik Kelas' : 'Tidak Naik Kelas';
        // Membuat PDF
        $pdf = FacadePdf::loadView('nilai.pdf', compact(
            'santri',
            'absensi',
            'nilai',
            'tahunAjaran',
            'kelas',
            'hadir',
            'izin',
            'sakit',
            'alfa',
            'totalHafalan',
            'namaHafalan',
            'target',
            'keteranganHafalan',
            'statusKenaikan'
        ));

        dd($pdf);

        // Mengunduh PDF
        return $pdf->download('rapor_santri_' . $santri_id . '.pdf');
    }
}
