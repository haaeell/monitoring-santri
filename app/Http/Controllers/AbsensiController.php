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
    public function index(Request $request)
    {
        $kelas = Kelas::all();
        $selectedKelas = $request->kelas_id ? Kelas::find($request->kelas_id) : null;
        $santris = [];

        if ($selectedKelas) {
            $santris = Santri::where('kelas_id', $selectedKelas->id)
                ->with(['absensi' => function ($query) use ($selectedKelas) {
                    $query->where('kelas_id', $selectedKelas->id);
                }])
                ->get();
        }


        return view('absensi.index', compact('kelas', 'selectedKelas', 'santris'));
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
        dd($request->all());
        foreach ($request->absensi as $santri_id => $absensiData) {
            foreach ($absensiData as $pertemuan => $status) {
                Absensi::updateOrCreate(
                    [
                        'santri_id' => $santri_id,
                        'kelas_id' => $request->kelas_id,
                        'mapel_id' => $request->mapel_id,
                        'pertemuan' => $pertemuan,
                        'tanggal' => now(),
                    ],
                    [
                        'status' => $status
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Absensi berhasil diperbarui!');
    }


    public function filter(Request $request)
    {
        $kelas_id = $request->kelas_id;
        $tanggal = $request->tanggal ? Carbon::parse($request->tanggal)->toDateString() : Carbon::today()->toDateString();

        $kelas = Kelas::where('id', $kelas_id)->with(['santris', 'walikelas.user'])->first();

        if ($kelas) {
            $kelas->santris->map(function ($santri) use ($tanggal) {
                $absensiToday = Absensi::where('santri_id', $santri->id)
                    ->whereDate('tanggal', $tanggal)
                    ->first();
                $santri->absensi_today = $absensiToday;
                return $santri;
            });

            return response()->json([
                'kelas' => $kelas->santris,
            ]);
        }

        return response()->json(['message' => 'Kelas tidak ditemukan'], 404);
    }
}
