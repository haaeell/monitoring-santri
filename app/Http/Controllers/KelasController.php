<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Mapel;
use App\Models\Santri;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::with('waliKelas')->get();
        return view('kelas.index', compact('kelas'));
    }

    public function create()
    {
        $gurus = Guru::all();
        return view('kelas.create', compact('gurus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'wali_kelas_id' => 'required|exists:guru,id',
        ]);

        Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'wali_kelas_id' => $request->wali_kelas_id,
        ]);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dibuat');
    }

    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        $gurus = Guru::all();
        return view('kelas.edit', compact('kelas', 'gurus'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'wali_kelas_id' => 'required|exists:guru,id',
        ]);

        $kelas = Kelas::findOrFail($id);
        $kelas->update([
            'nama_kelas' => $request->nama_kelas,
            'wali_kelas_id' => $request->wali_kelas_id,
        ]);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diperbarui');
    }

    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus');
    }

    public function showSantri($kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $santris = $kelas->santris; 
        
        $allSantris = Santri::whereDoesntHave('kelas', function ($query) use ($kelasId) {
            $query->where('kelas.id', $kelasId);
        })->get();

        return view('kelas.santri', compact('kelas', 'santris', 'allSantris'));
    }


    public function addSantri(Request $request, $kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $santriIds = $request->input('santri_id');

        foreach ($santriIds as $santriId) {
            $santri = Santri::findOrFail($santriId);

            if ($santri->kelas()->count() >= 2) {
                return redirect()->route('kelas.santri', $kelasId)
                    ->with('error', 'Santri ' . $santri->nama . ' sudah terdaftar di 2 kelas.');
            }
        }

        $kelas->santris()->attach($santriIds);

        return redirect()->route('kelas.santri', $kelasId)->with('success', 'Santri berhasil ditambahkan ke kelas.');
    }


    public function removeSantri($kelasId, $santriId)
    {
        $kelas = Kelas::findOrFail($kelasId);

        $kelas->santris()->detach($santriId);

        return redirect()->route('kelas.santri', $kelasId)->with('success', 'Santri berhasil dihapus dari kelas.');
    }
    public function showMapel($mapelId)
    {
        $kelas = Kelas::findOrFail($mapelId);
        $mapels = $kelas->mapels; 

        $allMapels = Mapel::whereDoesntHave('kelas', function ($query) use ($mapelId) {
            $query->where('kelas.id', $mapelId);
        })->get();

        return view('kelas.mapel', compact('kelas', 'mapels', 'allMapels'));
    }


    public function addMapel(Request $request, $kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $mapelIds = $request->input('mapel_id');

        foreach ($mapelIds as $mapelId) {
            $mapel = Mapel::findOrFail($mapelId);

            if ($mapel->kelas()->count() >= 2) {
                return redirect()->route('kelas.mapel', $kelasId)
                    ->with('error', 'Mapel ' . $mapel->nama_mapel . ' sudah terdaftar di 2 kelas.');
            }
        }

        $kelas->mapels()->attach($mapelIds);

        return redirect()->route('kelas.mapel', $kelasId)->with('success', 'Mapel berhasil ditambahkan ke kelas.');
    }


    public function removeMapel($kelasId, $mapelId)
    {
        $kelas = Kelas::findOrFail($kelasId);

        $kelas->mapels()->detach($mapelId);

        return redirect()->route('kelas.mapel', $kelasId)->with('success', 'Mapel berhasil dihapus dari kelas.');
    }
}
