<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Models\WaliSantri;
use Illuminate\Http\Request;

class WaliSantriController extends Controller
{
    public function index()
    {
        $wali_santri = WaliSantri::with('santri', 'user')->whereHas('user', function ($query) {
            $query->where('role', 'wali_santri');
        })->get();
        return view('wali_santri.index', compact('wali_santri'));
    }
    public function edit($id)
    {
        $wali_santri = Santri::find($id);
        return view('wali_santri.edit', compact('wali_santri'));
    }

    public function update(Request $request, $id)
    {
        $messages = [
            'nama.required' => 'Nama lengkap harus diisi.',
            'alamat.required' => 'Alamat harus diisi.',
        ];

        $data = $request->validate([
            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'telp' => 'nullable|string|max:15',
            'alamat' => 'required|string',
        ], $messages);

        $santri = Santri::findOrFail($id);

        $santri->update([
            'nama_ayah' => $data['nama_ayah'],
            'nama_ibu' => $data['nama_ibu'],
            'telp' => $data['telp'] ?? null,
            'alamat' => $data['alamat'],
        ]);

        return redirect()->route('wali.index')->with('success', 'Data wali santri berhasil diperbarui.');
    }
}
