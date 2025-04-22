<?php

namespace App\Http\Controllers;

use App\Models\Hafalan;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Santri;
use App\Models\SetorHafalan;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetorHafalanController extends Controller
{
    public function index(Request $request)
    {
        $kelas = Kelas::where('wali_kelas_id', Auth::user()->guru->id)->get();
        
        $tahunAjaran = TahunAjaran::where('status', 'Aktif')->get();
        $kelasId = $request->kelas_id ?? $kelas->first()->id;
        $tahunAjaranId = $request->tahun_ajaran_id ?? $tahunAjaran->first()->id;
        $selectedKelas = $kelasId ? Kelas::find($kelasId) : null;
        $selectedTahunAjaran = $tahunAjaranId ? TahunAjaran::find($tahunAjaranId) : null;

        $santris = [];

        $today = Carbon::today()->toDateString();

        $santris = Santri::where('kelas_id', $kelasId)
            ->with(['hafalan' => function ($query) use ($tahunAjaranId) {
                $query->where('tahun_ajaran_id', $tahunAjaranId);
            }, 'hafalan' => function ($query) use ($today) {
                $query->whereDate('tanggal_setor', $today);
            }])
            ->get();


        return view('setor_hafalan.index', compact('kelas', 'tahunAjaran', 'santris', 'selectedKelas', 'selectedTahunAjaran'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'mulai' => 'required|array',
            'selesai' => 'required|array',
            'total' => 'required|array',
        ]);

        $today = Carbon::today()->toDateString();

        foreach ($request->mulai as $santriId => $mulai) {
            $setoranHafalan = SetorHafalan::where('santri_id', $santriId)
                ->whereDate('tanggal_setor', $today)
                ->first();

            if ($setoranHafalan) {
                $setoranHafalan->update([
                    'mulai' => $mulai,
                    'selesai' => $request->selesai[$santriId],
                    'selesai' => $request->selesai[$santriId],
                    'total' => $request->total[$santriId],
                    'tahun_ajaran_id' => $request->tahun_ajaran_id,
                ]);
            } else {
                SetorHafalan::create([
                    'santri_id' => $santriId,
                    'nama_hafalan' => $request->nama_hafalan,
                    'mulai' => $mulai,
                    'selesai' => $request->selesai[$santriId],
                    'total' => $request->total[$santriId],
                    'tanggal_setor' => now(),
                    'tahun_ajaran_id' => $request->tahun_ajaran_id,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Setoran hafalan berhasil disimpan.');
    }
    public function rekap(Request $request)
    {
        $kelas = Kelas::where('wali_kelas_id', Auth::user()->guru->id)->get();
        $tahunAjaran = TahunAjaran::where('status', 'Aktif')->get();

        $kelasId = $request->kelas_id ?? $kelas->first()->id;
        $tahunAjaranId = $request->tahun_ajaran_id ?? $tahunAjaran->first()->id;
        $selectedKelas = $kelasId ? Kelas::find($kelasId) : null;
        $selectedTahunAjaran = $tahunAjaranId ? TahunAjaran::find($tahunAjaranId) : null;

        $rekap = [];

        if ($selectedKelas && $selectedTahunAjaran) {
            $rekap = Santri::where('kelas_id', $kelasId)
                ->with(['hafalan' => function ($query) use ($tahunAjaranId) {
                    $query->where('tahun_ajaran_id', $tahunAjaranId);
                }])
                ->get();
        }

        return view('setor_hafalan.rekap', compact('kelas', 'tahunAjaran', 'rekap', 'selectedKelas', 'selectedTahunAjaran'));
    }
}
