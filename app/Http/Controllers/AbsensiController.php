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
                    $query->where('tahun_ajaran_id', $tahunAjaranId)->where('mapel_id',  Auth::user()->guru->mapel->id ?? null);
                }])
                ->get();
        }

        return view('absensi.index', compact('kelas', 'tahunAjaran', 'selectedKelas', 'selectedTahunAjaran', 'santris'));
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
                            'tanggal' => now()->toDateString()
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
