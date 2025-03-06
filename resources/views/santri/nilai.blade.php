@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <!-- Informasi Santri -->
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info p-3 text-white text-center">
                    <h4 class="mb-0"><i class="bi bi-person-circle"></i> Informasi Santri</h4>
                </div>
                <div class="card-body">
                    <p><strong>Nama:</strong> Ahmad Fauzan</p>
                    <p><strong>Kelas:</strong> 7A</p>
                    <p><strong>Tahun Ajaran:</strong> 2024/2025</p>
                </div>
            </div>
        </div>

        <!-- Mata Pelajaran yang Diambil -->
        <div class="col-md-12 mt-3">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary p-3 text-white text-center">
                    <h4 class="mb-0"><i class="bi bi-book"></i> Mata Pelajaran</h4>
                </div>
                <div class="card-body">
                    <ul>
                        <li>Al-Qur'an</li>
                        <li>Hadits</li>
                        <li>Fiqih</li>
                        <li>Akidah Akhlak</li>
                        <li>Bahasa Arab</li>
                        <li>Sejarah Islam</li>
                        <li>Tafsir</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Hafalan Santri -->
        <div class="col-md-6 mt-3">
            <div class="card shadow-sm">
                <div class="card-header bg-primary p-3 text-white text-center">
                    <h4 class="mb-0"><i class="bi bi-journal-text"></i> Hafalan Santri</h4>
                </div>
                <div class="card-body">
                    <p><strong>Juz yang sudah dihafal:</strong> 10 dari 30</p>
                    <p><strong>Surat terakhir:</strong> Al-Kahfi</p>
                    <p><strong>Status Hafalan:</strong> Lancar</p>
                </div>
            </div>
        </div>

        <!-- Absensi Santri -->
        <div class="col-md-6 mt-3">
            <div class="card shadow-sm">
                <div class="card-header bg-warning p-3 text-white text-center">
                    <h4 class="mb-0"><i class="bi bi-calendar-check"></i> Absensi Santri</h4>
                </div>
                <div class="card-body">
                    <p><strong>Hadir:</strong> 80%</p>
                    <p><strong>Izin:</strong> 10%</p>
                    <p><strong>Alpha:</strong> 10%</p>
                </div>
            </div>
        </div>

        <!-- Nilai Akademik -->
        <div class="col-md-12 mt-3">
            <div class="card shadow-sm">
                <div class="card-header bg-success p-3 text-white text-center">
                    <h4 class="mb-0"><i class="bi bi-bar-chart-line"></i> Nilai Akademik</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Mata Pelajaran</th>
                                <th>UTS</th>
                                <th>UAS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Al-Qur'an</td>
                                <td>85</td>
                                <td>90</td>
                            </tr>
                            <tr>
                                <td>Hadits</td>
                                <td>80</td>
                                <td>85</td>
                            </tr>
                            <tr>
                                <td>Fiqih</td>
                                <td>78</td>
                                <td>82</td>
                            </tr>
                            <tr>
                                <td>Akidah Akhlak</td>
                                <td>85</td>
                                <td>88</td>
                            </tr>
                            <tr>
                                <td>Bahasa Arab</td>
                                <td>80</td>
                                <td>83</td>
                            </tr>
                            <tr>
                                <td>Sejarah Islam</td>
                                <td>75</td>
                                <td>78</td>
                            </tr>
                            <tr>
                                <td>Tafsir</td>
                                <td>88</td>
                                <td>92</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Peringkat Santri -->
        <div class="col-md-12 mt-3">
            <div class="card shadow-sm">
                <div class="card-header bg-danger p-3 text-white text-center">
                    <h4 class="mb-0"><i class="bi bi-trophy"></i> Peringkat Santri</h4>
                </div>
                <div class="card-body text-center">
                    <h5>Peringkat: 3 dari 30</h5>
                </div>
            </div>
        </div>
    </div>
@endsection
