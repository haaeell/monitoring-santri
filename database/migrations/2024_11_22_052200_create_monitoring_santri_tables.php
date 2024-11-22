<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonitoringSantriTables extends Migration
{
    public function up()
    {

        Schema::create('guru', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nip')->unique()->nullable();
            $table->string('alamat')->nullable();
            $table->string('no_telepon')->nullable();
            $table->string('pendidikan_terakhir')->nullable();
            $table->string('jabatan')->default('guru');
            $table->timestamps();
        });

        Schema::create('kepala_pondok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nip')->unique()->nullable();
            $table->string('alamat')->nullable();
            $table->string('no_telepon')->nullable();
            $table->string('pendidikan_terakhir')->nullable();
            $table->timestamps();
        });


        Schema::create('tahun_ajaran', function (Blueprint $table) {
            $table->id();
            $table->string('tahun');
            $table->enum('semester', ['Ganjil', 'Genap']);
            $table->boolean('aktif')->default(false);
            $table->timestamps();
        });

        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas');
            $table->foreignId('wali_kelas_id')->constrained('guru')->onDelete('cascade');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('santri', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nis')->unique();
            $table->string('kamar');
            $table->string('jenis_kelamin');
            $table->string('alamat');
            $table->string('telp');
            $table->string('foto')->nullable();
            $table->date('tanggal_lahir');
            $table->timestamps();
        });

        Schema::create('santri_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('mapel', function (Blueprint $table) {
            $table->id();
            $table->string('nama_mapel');
            $table->foreignId('guru_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('mapel_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mapel_id')->constrained('mapel')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_kelas_id')->constrained('santri_kelas')->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alfa']);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('hafalan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_kelas_id')->constrained('santri_kelas')->onDelete('cascade');
            $table->string('nama_hafalan');
            $table->integer('mulai');
            $table->integer('selesai');
            $table->integer('total');
            $table->date('tanggal_setor');
            $table->foreignId('penilai_id')->constrained('users')->onDelete('cascade');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('nilai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_kelas_id')->constrained('santri_kelas')->onDelete('cascade');
            $table->foreignId('mapel_id')->constrained('mapel')->onDelete('cascade');
            $table->integer('nilai');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('pelanggaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_kelas_id')->constrained('santri_kelas')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('jenis_pelanggaran');
            $table->text('keterangan')->nullable();
            $table->integer('poin');
            $table->foreignId('guru_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('wali_santri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('santri_id')->constrained('santri')->onDelete('cascade');
            $table->string('hubungan');
            $table->timestamps();
        });

        Schema::create('nilai_santri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_kelas_id')->constrained('santri_kelas')->onDelete('cascade');
            $table->foreignId('mapel_id')->constrained('mapel')->onDelete('cascade');
            $table->float('presensi')->default(0);
            $table->integer('nilai_uts')->nullable();
            $table->integer('nilai_uas')->nullable();
            $table->integer('hafalan')->nullable();
            $table->integer('peringkat')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wali_santri');
        Schema::dropIfExists('pelanggaran');
        Schema::dropIfExists('nilai');
        Schema::dropIfExists('hafalan');
        Schema::dropIfExists('absensi');
        Schema::dropIfExists('mapel_kelas');
        Schema::dropIfExists('mapel');
        Schema::dropIfExists('santri_kelas');
        Schema::dropIfExists('santri');
        Schema::dropIfExists('kelas');
        Schema::dropIfExists('tahun_ajaran');
        Schema::dropIfExists('users');
        Schema::dropIfExists('nilai_santri');
        Schema::dropIfExists('guru');
        Schema::dropIfExists('kepala_pondok');
        
    }
}
