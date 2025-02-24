<?php

namespace App\Http\Controllers;

use App\Models\Hafalan;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Santri;
use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Nilai;
use App\Models\Pembahasan;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{

    public function index(Request $request)
    {
        $user = auth()->user();
        $guru = Guru::where('user_id', $user->id)->first(); 
    
        if (!$guru) {
            return abort(403, 'Anda bukan guru.');
        }
    
        $kelasId = $request->kelas_id;
        $tahunAjaranId = $request->tahun_ajaran_id;
        $kelas = Kelas::whereHas('mapels', function ($query) use ($guru) {
            $query->where('guru_id', $guru->id);
        })->get();
    
        $tahunAjaran = TahunAjaran::all();
    
        $selectedKelas = $kelasId ? Kelas::find($kelasId) : null;
        $selectedTahunAjaran = $tahunAjaranId ? TahunAjaran::find($tahunAjaranId) : null;
    
        $santris = [];
    
        if ($selectedKelas && $selectedTahunAjaran) {
            $santris = Santri::where('kelas_id', $kelasId)
                ->with(['absensi' => function ($query) use ($tahunAjaranId) {
                    $query->where('tahun_ajaran_id', $tahunAjaranId);
                }])
                ->get();
        }
    
        return view('absensi.index', compact('kelas', 'tahunAjaran', 'selectedKelas', 'selectedTahunAjaran', 'santris'));
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
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'nullable|exists:mapel,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'absensi' => 'required|array',
        ]);

        foreach ($request->absensi as $santriId => $absensiData) {
            foreach ($absensiData as $pertemuan => $status) {
                // Hanya proses jika status tidak kosong
                if (!empty($status)) {
                    Absensi::updateOrCreate(
                        [
                            'santri_id' => $santriId,
                            'kelas_id' => $request->kelas_id,
                            'mapel_id' => $request->mapel_id,
                            'tahun_ajaran_id' => $request->tahun_ajaran_id,
                            'pertemuan' => $pertemuan,
                        ],
                        [
                            'status' => $status
                        ]
                    );
                }
            }
        }

        foreach ($request->uts as $santriId => $nilaiUts) {
            Nilai::updateOrCreate(
                [
                    'santri_id' => $santriId,
                    'kelas_id' => $request->kelas_id,
                    'mapel_id' => $request->mapel_id,
                    'tahun_ajaran_id' => $request->tahun_ajaran_id,
                ],
                [
                    'nilai_uts' => $nilaiUts ?? 0,
                    'nilai_uas' => $request->uas[$santriId] ?? 0,
                ]
            );
        }

        return redirect()->route('absensi.index', ['kelas_id' => $request->kelas_id, 'tahun_ajaran_id' => $request->tahun_ajaran_id])
            ->with('success', 'Absensi berhasil disimpan.');
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
