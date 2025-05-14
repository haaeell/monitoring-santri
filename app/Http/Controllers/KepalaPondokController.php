<?php
namespace App\Http\Controllers;

use App\Models\KepalaPondok;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class KepalaPondokController extends Controller
{
    public function index()
    {
        $kepalaPondok = KepalaPondok::with('user')
        ->whereHas('user', function ($query) {
            $query->where('role', 'kepala_pondok');
        })
        ->get();

        return view('kepala_pondok.index', compact('kepalaPondok'));
    }

    public function create()
    {
        $users = User::all(); 
        return view('kepala_pondok.create', compact('users'));
    }

    public function edit($id)
    {
        $kepalaPondok = KepalaPondok::findOrFail($id);
        $user = $kepalaPondok->user;
        return view('kepala_pondok.edit', compact('kepalaPondok', 'user'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'nip' => 'required|string|max:20',
                'alamat' => 'required|string|max:255',
                'no_telepon' => 'required|string|max:15',
                'pendidikan_terakhir' => 'required|string|max:100',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('password'),
                'role' => 'kepala_pondok',
            ]);

            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('kepala_pondok_foto', 'public');
                $user->update(['foto' => $fotoPath]);
            }

            KepalaPondok::create([
                'user_id' => $user->id,
                'nip' => $request->nip,
                'alamat' => $request->alamat,
                'no_telepon' => $request->no_telepon,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
            ]);

            return redirect()->route('kepala_pondok.index')->with('success', 'Kepala Pondok berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->route('kepala_pondok.create')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'nip' => 'required|string|max:20',
                'alamat' => 'required|string|max:255',
                'no_telepon' => 'required|string|max:15',
                'pendidikan_terakhir' => 'required|string|max:100',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $user = User::findOrFail($id);
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            $kepalaPondok = KepalaPondok::where('user_id', $user->id)->first();
            $fotoPath = $kepalaPondok->foto;

            if ($request->hasFile('foto')) {
                if ($fotoPath && Storage::exists('public/' . $fotoPath)) {
                    Storage::delete('public/' . $fotoPath);
                }
                $fotoPath = $request->file('foto')->store('kepala_pondok_foto', 'public');
            }

            $kepalaPondok->update([
                'nip' => $request->nip,
                'alamat' => $request->alamat,
                'no_telepon' => $request->no_telepon,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'foto' => $fotoPath,
            ]);

            return redirect()->route('kepala_pondok.index')->with('success', 'Data Kepala Pondok berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('kepala_pondok.edit', $id)->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $kepalaPondok = KepalaPondok::find($id);
            $user = User::find($kepalaPondok->user_id);

            if ($kepalaPondok->foto && file_exists(storage_path('app/public/' . $kepalaPondok->foto))) {
                unlink(storage_path('app/public/' . $kepalaPondok->foto));
            }

            $kepalaPondok->delete();
            $user->delete();

            return redirect()->route('kepala_pondok.index')->with('success', 'Kepala Pondok berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('kepala_pondok.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

