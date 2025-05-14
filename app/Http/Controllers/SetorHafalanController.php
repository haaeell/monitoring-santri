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

        $santris = Santri::where('kelas_id', $kelasId)
            ->with(['hafalan' => function ($query) use ($tahunAjaranId) {
                $query->where('tahun_ajaran_id', $tahunAjaranId);
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

        foreach ($request->mulai as $santriId => $mulai) {
            $selesai = $request->selesai[$santriId] ?? 0;

            if ($selesai <= $mulai) {
                return redirect()->back()->withErrors([
                    'selesai.' . $santriId => 'Jumlah selesai harus lebih besar dari mulai untuk Santri ID ' . $santriId,
                ]);
            }
        }

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
        if (Auth::user()->role == 'guru') {
            $kelas = Kelas::where('wali_kelas_id', Auth::user()->guru->id)->get();
        } else {
            $kelas = Kelas::all();
        }
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

    public function riwayat(Request $request)
    {
        $tahunAjaranId = $request->tahun_ajaran_id;
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->startOfWeek();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : now()->endOfWeek();

        $riwayat = SetorHafalan::with('santri')
            ->where('kelas_id', $request->kelas_id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->whereBetween('tanggal_setor', [$startDate, $endDate])
            ->orderBy('tanggal_setor', 'desc')
            ->get();

        foreach ($riwayat as $item) {
            $item->total = $item->selesai - $item->mulai;
        }

        $grouped = $riwayat->groupBy('santri_id')->map(function ($group) {
            $first = $group->first();
            return [
                'santri' => $first->santri,
                'total' => $group->sum('total'),
                'santri_id' => $first->santri_id,
            ];
        });

        $topSantriId = null;
        $leastSantriId = null;
        $maxTotal = 0;
        $minTotal = 0;

        if ($grouped->count() > 0) {
            $sorted = $grouped->sortByDesc('total');
            $top = $sorted->first();
            $least = $sorted->last();

            $topSantriId = $top['santri_id'];
            $leastSantriId = $least['santri_id'];
            $maxTotal = $top['total'];
            $minTotal = $least['total'];
        }

        return view('setor_hafalan.riwayat', [
            'riwayatPerSantri' => $grouped,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'topSantriId' => $topSantriId,
            'leastSantriId' => $leastSantriId,
            'maxTotal' => $maxTotal,
            'minTotal' => $minTotal,
        ]);
    }
}
