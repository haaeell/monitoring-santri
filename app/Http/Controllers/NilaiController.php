<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Nilai;
use App\Models\SantriKelas;
use App\Models\Mapel;
use App\Models\Santri;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $kelas = Kelas::all();
        return view('nilai.index', compact('kelas'));
    }
    public function getMapelAndSantriByKelas(Request $request)
    {
        $kelas = Kelas::with(['mapels', 'santris'])->find($request->kelas_id);
        $mapel = Mapel::where('guru_id', Auth::user()->id)->first();

        if ($kelas) {
            $kelas->santris->map(function ($santri) {
                $nilai = Nilai::where('santri_id', $santri->id)->first();
                $santri->nilai = $nilai;

                $hadirCount = Absensi::where('santri_id', $santri->id)
                    ->where('status', 'hadir')
                    ->count();

                $sakitCount = Absensi::where('santri_id', $santri->id)
                    ->where('status', 'sakit')
                    ->count();

                $izinCount = Absensi::where('santri_id', $santri->id)
                    ->where('status', 'izin')
                    ->count();

                $alfaCount = Absensi::where('santri_id', $santri->id)
                    ->where('status', 'alfa')
                    ->count();

                $santri->presensi = [
                    'hadir' => $hadirCount,
                    'sakit' => $sakitCount,
                    'izin'  => $izinCount,
                    'alfa'  => $alfaCount,
                ];

                return $santri;
            });

            return response()->json([
                'santris' => $kelas->santris,
                'mapel' => $mapel,
                'kelas' => $kelas
            ]);
        }

        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    public function store(Request $request)
    {
        foreach ($request->nilai_uts as $santriId => $nilaiUts) {
            $nilai = Nilai::where('santri_id', $santriId)
                ->first();

            if ($nilai) {
                $nilai->update([
                    'presensi' => $request->hadir[$santriId],
                    'nilai_uts' => $nilaiUts,
                    'nilai_uas' => $request->nilai_uas[$santriId],
                ]);
            } else {
                Nilai::create([
                    'santri_id' => $santriId,
                    'mapel_id' => $request->mapel_id,
                    'kelas_id' => $request->kelas_id,
                    'presensi' => $request->hadir[$santriId],
                    'nilai_uts' => $nilaiUts,
                    'nilai_uas' => $request->nilai_uas[$santriId],
                    'tanggal' => now(),
                ]);
            }
        }

        return response()->json(['success' => true]);
    }
}
