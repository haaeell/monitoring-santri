@extends('layouts.dashboard')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">Welcome {{ Auth::user()->name }}</h3>
            </div>
            <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
                    <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                        <button class="btn btn-sm btn-light bg-white ">
                            <i class="mdi mdi-calendar"></i> Today ({{ date('d F Y') }})
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistik -->
<div class="row">
    <div class="col-md-12 grid-margin transparent">
        <div class="row">
            <div class="col-md-4 mb-4 stretch-card transparent">
                <div class="card card-tale">
                    <div class="card-body">
                        <p class="mb-4">Total Santri</p>
                        <p class="fs-30 mb-2">{{ $santriCount }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4 stretch-card transparent">
                <div class="card card-dark-blue">
                    <div class="card-body">
                        <p class="mb-4">Total Kelas</p>
                        <p class="fs-30 mb-2">{{ $kelasCount }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4 stretch-card transparent">
                <div class="card card-light-blue">
                    <div class="card-body">
                        <p class="mb-4">Total Guru</p>
                        <p class="fs-30 mb-2">{{ $guruCount  }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Galeri Foto Pondok Pesantren -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="text-center mb-3">Galeri Pondok Pesantren</h4>
                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <img src="{{ asset('IMG_8014.JPG') }}" class="img-fluid rounded" alt="Pondok 1">
                        </div>
                        <div class="swiper-slide">
                            <img src="{{ asset('IMG_7669123467.jpg') }}" class="img-fluid rounded" alt="Pondok 2">
                        </div>
                        <div class="swiper-slide">
                            <img src="{{ asset('nisfu.jpg') }}" class="img-fluid rounded" alt="Pondok 3">
                        </div>
                    </div>
                    <!-- Paginasi jika ingin ada navigasi -->
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
<!-- SwiperJS JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var swiper = new Swiper(".mySwiper", {
            slidesPerView: 1,
            spaceBetween: 10,
            loop: false, // Tidak ada loop
            autoplay: false, // Tidak otomatis berpindah
            pagination: {
                el: ".swiper-pagination",
                clickable: true, // Bisa diklik untuk navigasi antar slide
            },
        });
    });
</script>
@endsection
