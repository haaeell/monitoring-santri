<?php

namespace App\Http\Controllers;

use App\Models\Hafalan;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Santri;
use App\Models\Absensi;
use App\Models\Pembahasan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    public function index()
    {
        $guruId = Auth::user()->guru->id;

        $kelas = Kelas::whereHas('mapels', function ($query) use ($guruId) {
            $query->where('guru_id', $guruId);
        })->get();

        return view('absensi.index', compact('kelas'));
    }

    public function getMapelAndSantriByKelas(Request $request)
    {
        $kelas = Kelas::with(['mapels', 'santris'])->find($request->kelas_id);
        $mapel = Mapel::where('guru_id', Auth::user()->guru->id)->first();

        if ($kelas) {

            $tanggal = $request->tanggal ? Carbon::parse($request->tanggal)->toDateString() : Carbon::today()->toDateString();

            $jumlahHadir = 0;
            $jumlahAlfa = 0;
            $jumlahIzin = 0;
            $jumlahSakit = 0;

            $kelas->santris->map(function ($santri) use ($tanggal, &$jumlahHadir, &$jumlahAlfa, &$jumlahIzin, &$jumlahSakit) {
                $absensiToday = Absensi::where('santri_id', $santri->id)
                    ->whereDate('tanggal', $tanggal)
                    ->first();
                $santri->absensi_today = $absensiToday;

                if ($absensiToday) {
                    switch ($absensiToday->status) {
                        case 'hadir':
                            $jumlahHadir++;
                            break;
                        case 'alfa':
                            $jumlahAlfa++;
                            break;
                        case 'izin':
                            $jumlahIzin++;
                            break;
                        case 'sakit':
                            $jumlahSakit++;
                            break;
                    }
                }
                return $santri;
            });

            return response()->json([
                'santris' => $kelas->santris,
                'mapel' => $mapel,
                'jumlahHadir' => $jumlahHadir,
                'jumlahAlfa' => $jumlahAlfa,
                'jumlahIzin' => $jumlahIzin,
                'jumlahSakit' => $jumlahSakit,
                'pembahasan' => Pembahasan::where('kelas_id', $kelas->id)->where('mapel_id', $mapel->id)->where('guru_id', Auth::user()->guru->id)->whereDate('tanggal', $tanggal)->first(),
            ]);
        }
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }
    public function store(Request $request)
    {
        $tanggal = $request->tanggal ? Carbon::parse($request->tanggal)->toDateString() : Carbon::today()->toDateString();

        Pembahasan::create([
            'tanggal' => $tanggal,
            'guru_id' => Auth::user()->guru->id,
            'kelas_id' => $request->kelas_id,
            'mapel_id' => $request->mapel_id,
            'pembahasan' => $request->pembahasan,
        ]);

        foreach ($request->status as $santriId => $status) {
            $absensi = Absensi::where('santri_id', $santriId)
                ->whereDate('tanggal', $tanggal)
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

        return response()->json(['success' => true, 'message' => 'Absensi dan Pembahasan berhasil disimpan']);
    }
}
