<?php
namespace App\Http\Controllers;

use App\Models\Hafalan;
use App\Models\Kelas;
use Illuminate\Http\Request;

class HafalanController extends Controller
{
    public function index()
    {
        $hafalan = Hafalan::with('kelas')->get();
        return view('hafalan.index', compact('hafalan'));
    }

    public function create()
    {
        $kelas = Kelas::all();
        return view('hafalan.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        try {
            Hafalan::create($request->all());
            return redirect()->route('hafalan.index')->with('success', 'Hafalan berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->route('hafalan.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $hafalan = Hafalan::findOrFail($id);
        $kelas = Kelas::all();
        return view('hafalan.edit', compact('hafalan', 'kelas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        try {
            $hafalan = Hafalan::findOrFail($id);
            $hafalan->update($request->all());
            return redirect()->route('hafalan.index')->with('success', 'Hafalan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('hafalan.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $hafalan = Hafalan::findOrFail($id);
            $hafalan->delete();
            return redirect()->route('hafalan.index')->with('success', 'Hafalan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('hafalan.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
