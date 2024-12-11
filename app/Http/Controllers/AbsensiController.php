<?php

namespace App\Http\Controllers;

use App\Models\Hafalan;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Santri;
use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    public function index()
    {
        $kelas = Kelas::all();
        return view('absensi.index', compact('kelas'));
    }
    public function getMapelAndSantriByKelas(Request $request)
    {
        $kelas = Kelas::with(['mapels', 'santris'])->find($request->kelas_id);
        $mapel = Mapel::where('guru_id', Auth::user()->id)->first();

        if ($kelas) {
            $today = Carbon::today()->toDateString();
            $kelas->santris->map(function ($santri) use ($today) {
                $absensiToday = Absensi::where('santri_id', $santri->id)
                    ->whereDate('tanggal', $today)
                    ->first();
                $santri->absensi_today = $absensiToday;
                return $santri;
            });

            return response()->json([
                'santris' => $kelas->santris,
                'mapel' => $mapel,
            ]);
        }
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required|array',
            'keterangan' => 'nullable|array',
        ]);

        $today = Carbon::today()->toDateString(); 

        foreach ($request->status as $santriId => $status) {
            $absensi = Absensi::where('santri_id', $santriId)
                ->whereDate('tanggal', $today)
                ->first();

            if ($absensi) {
                $absensi->update([
                    'status' => $status,
                    'keterangan' => $request->keterangan[$santriId] ?? '',
                ]);
            } else {
                Absensi::create([
                    'santri_id' => $santriId,
                    'mapel_id' => $request->mapel_id,
                    'status' => $status,
                    'keterangan' => $request->keterangan[$santriId] ?? '',
                    'tanggal' => now(),
                ]);
            }
        }

        return response()->json(['success' => true]);
    }
}
