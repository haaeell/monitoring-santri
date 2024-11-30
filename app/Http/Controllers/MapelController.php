<?php
namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Mapel;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    public function index()
    {
        $mapel = Mapel::with('guru')->get();
        return view('mapel.index', compact('mapel'));
    }

    public function create()
    {
        $guru = Guru::all();
        return view('mapel.create', compact('guru'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:255',
            'guru_id' => 'required|exists:guru,id',
        ]);

        try {
            Mapel::create($request->all());
            return redirect()->route('mapel.index')->with('success', 'mapel berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->route('mapel.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $mapel = Mapel::findOrFail($id);
        $guru = Guru::all();
        return view('mapel.edit', compact('mapel', 'guru'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:255',
            'guru_id' => 'required|exists:guru,id',
        ]);

        try {
            $mapel = Mapel::findOrFail($id);
            $mapel->update($request->all());
            return redirect()->route('mapel.index')->with('success', 'mapel berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('mapel.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $mapel = Mapel::findOrFail($id);
            $mapel->delete();
            return redirect()->route('mapel.index')->with('success', 'mapel berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('mapel.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
