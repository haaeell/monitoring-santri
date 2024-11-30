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
        $kelasId = session('kelas_id', null);
        $kelas = Kelas::all();
        return view('setor_hafalan.index', compact('kelas', 'kelasId'));
    }
    public function getMapelAndSantriByKelas(Request $request)
    {
        $kelas = Kelas::with(['mapels', 'santris'])->find($request->kelas_id);
        $hafalan = Hafalan::where('kelas_id', $kelas->id)->first();

        if ($kelas) {
            session(['kelas_id' => $kelas->id]);

            $today = Carbon::today()->toDateString();
            $santrisWithSetoran = $kelas->santris->map(function ($santri) use ($today) {
                $setoranHafalanToday = SetorHafalan::where('santri_id', $santri->id)
                    ->whereDate('tanggal_setor', $today)
                    ->first();
                $santri->setoran_today = $setoranHafalanToday; 
                return $santri;
            });

            return response()->json([
                'mapels' => $kelas->mapels,
                'santris' => $kelas->santris,
                'nama_hafalan' => $hafalan->nama,
            ]);
        }
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'santri_id' => 'required|exists:santri,id',
            'nama_hafalan' => 'required|string|max:255',
            'mulai' => 'required|integer',
            'selesai' => 'required|integer',
            'total' => 'required|integer',
            'tanggal_setor' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        SetorHafalan::create($data);

        return redirect()->route('setor.index')->with('success', 'Setor hafalan berhasil disimpan.');
    }
}
