<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Hafalan;
use App\Models\Kelas;
use App\Models\Santri;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $santriCount = Santri::count();
        $kelasCount = Kelas::count();
        $guruCount = Guru::count();
        $hafalanCount = Hafalan::count();

        return view('home', compact('santriCount', 'kelasCount', 'guruCount', 'hafalanCount'));
    }
}
