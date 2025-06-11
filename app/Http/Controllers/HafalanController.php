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
            'target' => 'required',
        ]);

        try {
            $hafalan = Hafalan::create($request->all());

            Kelas::where('tingkatan', $hafalan->tingkatan)->update([
                'hafalan_id' => $hafalan->id
            ]);

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
            'target' => 'required',
            'tingkatan' => 'required|string|max:50',
        ]);

        try {
            $hafalan = Hafalan::findOrFail($id);
            $hafalan->update($request->all());

            Kelas::where('tingkatan', $hafalan->tingkatan)->update([
                'hafalan_id' => $hafalan->id
            ]);

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
