<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\HafalanController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\KepalaPondokController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\SantriController;
use App\Http\Controllers\SetorHafalanController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\WaliSantriController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::resource('santri', SantriController::class);
    Route::resource('guru', GuruController::class);
    Route::resource('kelas', KelasController::class);
    Route::get('/kelas/{kelas}/santri', [KelasController::class, 'showSantri'])->name('kelas.santri');
    Route::post('/kelas/{kelas}/santri', [KelasController::class, 'addSantri'])->name('kelas.addSantri');
    Route::delete('/kelas/{kelas}/santri/{santri}', [KelasController::class, 'removeSantri'])->name('kelas.removeSantri');
    Route::resource('kelas', KelasController::class);
    Route::get('/kelas/{kelas}/mapel', [KelasController::class, 'showMapel'])->name('kelas.mapel');
    Route::post('/kelas/{kelas}/mapel', [KelasController::class, 'addMapel'])->name('kelas.addMapel');
    Route::delete('/kelas/{kelas}/mapel/{mapel}', [KelasController::class, 'removeMapel'])->name('kelas.removeMapel');
    Route::resource('kepala_pondok', KepalaPondokController::class);
    Route::resource('wali', WaliSantriController::class);

    Route::resource('hafalan', HafalanController::class);
    Route::resource('mapel', MapelController::class);

    Route::get('setor', [SetorHafalanController::class, 'index'])->name('setor.index');
    Route::post('setor/store', [SetorHafalanController::class, 'store'])->name('setor.store');

    Route::get('absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::post('absensi/store', [AbsensiController::class, 'store'])->name('absensi.store');

    Route::get('nilai', [NilaiController::class, 'index'])->name('nilai.index');
    Route::get('nilai/{santri_id}', [NilaiController::class, 'detail'])->name('nilai.detail');
    Route::get('detail-nilai/{santri_id}', [NilaiController::class, 'show'])->name('detail-nilai');
    Route::resource('tahun-ajaran', TahunAjaranController::class);

    Route::get('/rekap-hafalan', [SetorHafalanController::class, 'rekap'])->name('rekap.index');


});
