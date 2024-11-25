<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GuruController extends Controller
{
    public function index()
    {
        $guru = Guru::all();
        return view('guru.index', compact('guru'));
    }

    public function create()
    {
        return view('guru.create');
    }
    public function edit($id)
    {
        $guru = Guru::find($id);
        return view('guru.edit', compact('guru'));
    }

    public function store(Request $request)
    {
        $messages = [
            'nama.required' => 'Nama lengkap harus diisi.',
            'nis.required' => 'NIS harus diisi.',
            'nis.unique' => 'NIS sudah terdaftar.',
            'nis.numeric' => 'NIS harus berupa angka.',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih.',
            'tanggal_lahir.required' => 'Tanggal lahir harus diisi.',
            'kamar.required' => 'Kamar harus diisi.',
            'alamat.required' => 'Alamat harus diisi.',
            'foto.image' => 'File yang diunggah harus berupa gambar.',
            'foto.mimes' => 'Foto harus berupa file dengan ekstensi: jpeg, jpg, png.',
            'foto.max' => 'Foto tidak boleh lebih dari 2MB.',
        ];

        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'required|unique:guru,nis|numeric',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'required|date',
            'kamar' => 'required|string|max:255',
            'telp' => 'nullable|string|max:15',
            'alamat' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ], $messages);


        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('guru_photos', 'public');
        }

        Guru::create([
            'nama' => $data['nama'],
            'nis' => $data['nis'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'tanggal_lahir' => $data['tanggal_lahir'],
            'kamar' => $data['kamar'],
            'telp' => $data['telp'] ?? null,
            'alamat' => $data['alamat'],
            'foto' => $fotoPath ?? null,
        ]);

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {
        $messages = [
            'nama.required' => 'Nama lengkap harus diisi.',
            'nis.required' => 'NIS harus diisi.',
            'nis.unique' => 'NIS sudah terdaftar.',
            'nis.numeric' => 'NIS harus berupa angka.',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih.',
            'tanggal_lahir.required' => 'Tanggal lahir harus diisi.',
            'kamar.required' => 'Kamar harus diisi.',
            'alamat.required' => 'Alamat harus diisi.',
            'foto.image' => 'File yang diunggah harus berupa gambar.',
            'foto.mimes' => 'Foto harus berupa file dengan ekstensi: jpeg, jpg, png.',
            'foto.max' => 'Foto tidak boleh lebih dari 2MB.',
        ];

        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'required|numeric|unique:guru,nis,' . $id,
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'required|date',
            'kamar' => 'required|string|max:255',
            'telp' => 'nullable|string|max:15',
            'alamat' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ], $messages);

        $guru = Guru::findOrFail($id);

        if ($request->hasFile('foto')) {
            if ($guru->foto && Storage::exists('public/' . $guru->foto)) {
                Storage::delete('public/' . $guru->foto);
            }

            $fotoPath = $request->file('foto')->store('guru_photos', 'public');
        } else {
            $fotoPath = $guru->foto;
        }

        $guru->update([
            'nama' => $data['nama'],
            'nis' => $data['nis'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'tanggal_lahir' => $data['tanggal_lahir'],
            'kamar' => $data['kamar'],
            'telp' => $data['telp'] ?? null,
            'alamat' => $data['alamat'],
            'foto' => $fotoPath,
        ]);

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $guru = Guru::findOrFail($id);
        if ($guru->foto && file_exists(storage_path('app/public/' . $guru->foto))) {
            unlink(storage_path('app/public/' . $guru->foto)); 
        }

        $guru->delete();
        
        return redirect()->route('guru.index')->with('success', 'guru has been deleted successfully!');
    }
}
