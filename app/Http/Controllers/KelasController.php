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
        $kelas = Kelas::with('waliKelas')->orderBy('nama_kelas', 'desc')->get();
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
        $santris = Santri::where('kelas_id', $kelasId)->get();

        $allSantris = Santri::whereDoesntHave('kelas')->get();

        return view('kelas.santri', compact('kelas', 'santris', 'allSantris'));
    }


    public function addSantri(Request $request, $kelas_id)
    {
        $request->validate([
            'santri_id' => 'required|array',
            'santri_id.*' => 'exists:santri,id'
        ]);

        $kelas = Kelas::findOrFail($kelas_id);

        Santri::whereIn('id', $request->santri_id)->update(['kelas_id' => $kelas->id]);

        return redirect()->back()->with('success', 'Santri berhasil ditambahkan ke kelas!');
    }

    public function removeSantri($kelas_id, $santri_id)
    {
        $santri = Santri::where('id', $santri_id)->where('kelas_id', $kelas_id)->firstOrFail();
        $santri->update(['kelas_id' => null]);

        return redirect()->back()->with('success', 'Santri berhasil dihapus dari kelas!');
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
