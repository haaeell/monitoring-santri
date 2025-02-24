<?php

namespace App\Http\Controllers;

use App\Models\Hafalan;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Santri;
use App\Models\SetorHafalan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SetorHafalanController extends Controller
{
    public function index()
    {
        $kelas = Kelas::all();
        return view('setor_hafalan.index', compact('kelas'));
    }
    public function getMapelAndSantriByKelas(Request $request)
    {
        $kelas = Kelas::with(['mapels', 'santris'])->find($request->kelas_id);
        $hafalan = Hafalan::where('kelas_id', $kelas->id)->first();

        if ($kelas) {
            $today = Carbon::today()->toDateString();
            $kelas->santris->map(function ($santri) use ($today) {
                $setoranHafalanToday = SetorHafalan::where('santri_id', $santri->id)
                    ->whereDate('tanggal_setor', $today)
                    ->first();
                $santri->setoran_today = $setoranHafalanToday;
                return $santri;
            });

            return response()->json([
                'santris' => $kelas->santris,
                'nama_hafalan' => $hafalan->nama,
            ]);
        }
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
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
                ]);
            } else {
                SetorHafalan::create([
                    'santri_id' => $santriId,
                    'nama_hafalan' => $request->nama_hafalan,
                    'mulai' => $mulai,
                    'selesai' => $request->selesai[$santriId],
                    'total' => $request->total[$santriId],
                    'tanggal_setor' => now(),
                ]);
            }
        }

        return response()->json(['success' => true]);
    }
}
