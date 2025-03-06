<?php

namespace App\Http\Controllers;

use App\Exports\SantriTemplateExport;
use App\Imports\SantriImport;
use App\Models\Santri;
use App\Models\User;
use App\Models\WaliSantri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class SantriController extends Controller
{
    public function index()
    {
        $santri = Santri::all();
        return view('santri.index', compact('santri'));
    }

    public function create()
    {
        return view('santri.create');
    }
    public function edit($id)
    {
        $santri = Santri::find($id);
        return view('santri.edit', compact('santri'));
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
            'nis' => 'required|unique:santri,nis|numeric',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'required|date',
            'kamar' => 'required|string|max:255',
            'telp' => 'nullable|string|max:15',
            'alamat' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
        ], $messages);


        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('santri_photos', 'public');
        }

        $santri = Santri::create([
            'nama' => $data['nama'],
            'nis' => $data['nis'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'tanggal_lahir' => $data['tanggal_lahir'],
            'kamar' => $data['kamar'],
            'telp' => $data['telp'] ?? null,
            'alamat' => $data['alamat'],
            'foto' => $fotoPath ?? null,
            'nama_ayah' => $data['nama_ayah'],
            'nama_ibu' => $data['nama_ibu'],
        ]);

        $user = User::create([
            'name' => $data['nama_ayah'],
            'email' => $data['nis'] . '@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'wali_santri',
        ]);

        WaliSantri::create([
            'santri_id' => $santri->id,
            'user_id' => $user->id
        ]);

        return redirect()->route('santri.index')->with('success', 'Data santri berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {
        $messages = [
            'nama.required' => 'Nama lengkap harus diisi.',
            'nis.required' => 'NIS harus diisi.',
            'nis.unique' => 'NIS sudah terdaftar.',
            'nis.nupmeric' => 'NIS harus berupa angka.',
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
            'nis' => 'required|numeric|unique:santri,nis,' . $id,
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'required|date',
            'kamar' => 'required|string|max:255',
            'telp' => 'nullable|string|max:15',
            'alamat' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ], $messages);

        $santri = Santri::findOrFail($id);

        if ($request->hasFile('foto')) {
            if ($santri->foto && Storage::exists('public/' . $santri->foto)) {
                Storage::delete('public/' . $santri->foto);
            }

            $fotoPath = $request->file('foto')->store('santri_photos', 'public');
        } else {
            $fotoPath = $santri->foto;
        }

        $santri->update([
            'nama' => $data['nama'],
            'nis' => $data['nis'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'tanggal_lahir' => $data['tanggal_lahir'],
            'kamar' => $data['kamar'],
            'telp' => $data['telp'] ?? null,
            'alamat' => $data['alamat'],
            'foto' => $fotoPath,
        ]);

        return redirect()->route('santri.index')->with('success', 'Data santri berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $santri = Santri::findOrFail($id);
        if ($santri->foto && file_exists(storage_path('app/public/' . $santri->foto))) {
            unlink(storage_path('app/public/' . $santri->foto));
        }

        $santri->delete();

        return redirect()->route('santri.index')->with('success', 'Santri has been deleted successfully!');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new SantriImport, $request->file('file'));

        return redirect()->route('santri.index')->with('success', 'Data santri berhasil diimpor.');
    }

    public function downloadTemplate()
    {
        return Excel::download(new SantriTemplateExport, 'santri_template.xlsx');
    }

    public function santriNilai()
    {
        $waliSantri = WaliSantri::where('user_id', auth()->user()->id)->first();
        $santri = $waliSantri->santri;
        
        return view('santri.nilai', compact('santri'));
    }
}
