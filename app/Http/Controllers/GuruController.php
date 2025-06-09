<?php

namespace App\Http\Controllers;

use App\Exports\TemplateGuruExport;
use App\Imports\GuruImport;
use App\Models\Guru;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class GuruController extends Controller
{
    public function index()
    {
        $guru = Guru::with('user')->whereHas('user', function ($query) {
            $query->where('role', 'guru');
        })->get();
        return view('guru.index', compact('guru'));
    }

    public function create()
    {
        return view('guru.create');
    }
    public function edit($id)
    {
        $guru = guru::find($id);
        return view('guru.edit', compact('guru'));
    }
    public function store(Request $request)
    {
        $messages = [
            'nama.required' => 'Nama lengkap harus diisi.',
            'nip.required' => 'NIP harus diisi.',
            'nip.unique' => 'NIP sudah terdaftar.',
            'nip.numeric' => 'NIP harus berupa angka.',
            'alamat.required' => 'Alamat harus diisi.',
            'jenis_kelamin.required' => 'Jenis Kelamin harus diisi.',
            'no_telepon.required' => 'No Telepon harus diisi.',
            'pendidikan_terakhir.required' => 'Pendidikan terakhir harus diisi.',
            'foto.image' => 'File yang diunggah harus berupa gambar.',
            'foto.mimes' => 'Foto harus berupa file dengan ekstensi: jpeg, jpg, png.',
            'foto.max' => 'Foto tidak boleh lebih dari 2MB.',
            'email.required' => 'Email harus diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password harus diisi.',
        ];

        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|unique:guru,nip|numeric',
            'alamat' => 'required|string',
            'jenis_kelamin' => 'required|string',
            'no_telepon' => 'required|string|max:15',
            'pendidikan_terakhir' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'email' => 'required|email|unique:users,email',
        ], $messages);

        DB::beginTransaction();

        try {
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('guru_photos', 'public');
            } else {
                $fotoPath = null;
            }
            $user = User::create([
                'name' => $data['nama'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => 'guru',
                'foto' => $fotoPath,
            ]);

            Guru::create([
                'user_id' => $user->id,
                'nip' => $data['nip'],
                'alamat' => $data['alamat'],
                'no_telepon' => $data['no_telepon'],
                'pendidikan_terakhir' => $data['pendidikan_terakhir'],
                'jenis_kelamin' => $data['jenis_kelamin'],
            ]);

            DB::commit();

            return redirect()->route('guru.index')->with('success', 'Data guru berhasil disimpan.');
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->route('guru.create')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $messages = [
            'nama.required' => 'Nama lengkap harus diisi.',
            'nip.required' => 'NIP harus diisi.',
            'nip.unique' => 'NIP sudah terdaftar.',
            'nip.numeric' => 'NIP harus berupa angka.',
            'alamat.required' => 'Alamat harus diisi.',
            '.required' => 'Jenis Kelamin harus diisi.',
            'no_telepon.required' => 'No Telepon harus diisi.',
            'pendidikan_terakhir.required' => 'Pendidikan terakhir harus diisi.',
            'foto.image' => 'File yang diunggah harus berupa gambar.',
            'foto.mimes' => 'Foto harus berupa file dengan ekstensi: jpeg, jpg, png.',
            'foto.max' => 'Foto tidak boleh lebih dari 2MB.',
        ];

        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|numeric|unique:guru,nip,' . $id,
            'alamat' => 'required|string',
            'jenis_kelamin' => 'required|string',
            'no_telepon' => 'required|string|max:15',
            'pendidikan_terakhir' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ], $messages);

        DB::beginTransaction();
        try {
            $guru = Guru::findOrFail($id);
            $user = $guru->user;

            if ($request->hasFile('foto')) {
                if ($guru->foto && Storage::exists('public/' . $guru->foto)) {
                    Storage::delete('public/' . $guru->foto);
                }

                $fotoPath = $request->file('foto')->store('guru_photos', 'public');
            } else {
                $fotoPath = $guru->foto;
            }

            $user->update([
                'name' => $data['nama'],
                'email' => $request->input('email', $user->email),
                // 'password' => Hash::make('password'),
                'foto' => $fotoPath,
            ]);

            $guru->update([
                'nip' => $data['nip'],
                'alamat' => $data['alamat'],
                'no_telepon' => $data['no_telepon'],
                'pendidikan_terakhir' => $data['pendidikan_terakhir'],
                'jenis_kelamin' => $data['jenis_kelamin'],
            ]);

            DB::commit();

            return redirect()->route('guru.index')->with('success', 'Data guru berhasil diperbarui.');
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->route('guru.edit', $id)->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $guru = Guru::findOrFail($id);
        $user = User::find($guru->user_id);

        if ($guru->foto && file_exists(storage_path('app/public/' . $guru->foto))) {
            unlink(storage_path('app/public/' . $guru->foto));
        }

        $guru->delete();
        $user->delete();

        return redirect()->route('guru.index')->with('success', 'guru has been deleted successfully!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new GuruImport, $request->file('file'));

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil diimport.');
    }

    public function downloadTemplate()
    {
        return Excel::download(new TemplateGuruExport, 'template_guru.xlsx');
    }
}
