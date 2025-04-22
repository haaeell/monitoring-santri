<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Monitoring Andalusia Hibbana</title>
    <link rel="stylesheet" href="{{ asset('assets') }}/vendors/feather/feather.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/css/style.css">
    <link rel="shortcut icon" href="{{ asset('assets') }}/images/favicon.png" />

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <div class="container-scroller">
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo me-5 text-dark fs-6" href="index.html"><img src="{{asset('logo.jpg')}}"
                        class="me-2" alt="logo" />Andalusia Hibbana</a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="icon-menu"></span>
                </button>
                <ul class="navbar-nav navbar-nav-right">
                    {{-- <li class="nav-item dropdown">
                        <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#"
                            data-bs-toggle="dropdown">
                            <i class="icon-bell mx-0"></i>
                            <span class="count"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                            aria-labelledby="notificationDropdown">
                            <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-success">
                                        <i class="ti-info-alt mx-0"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <h6 class="preview-subject font-weight-normal">Application Error</h6>
                                    <p class="font-weight-light small-text mb-0 text-muted"> Just now </p>
                                </div>
                            </a>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-warning">
                                        <i class="ti-settings mx-0"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <h6 class="preview-subject font-weight-normal">Settings</h6>
                                    <p class="font-weight-light small-text mb-0 text-muted"> Private message </p>
                                </div>
                            </a>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-info">
                                        <i class="ti-user mx-0"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <h6 class="preview-subject font-weight-normal">New user registration</h6>
                                    <p class="font-weight-light small-text mb-0 text-muted"> 2 days ago </p>
                                </div>
                            </a>
                        </div>
                    </li> --}}
                    <li class="nav-item nav-profile dropdown">
                        <span class="mx-2">{{ Auth::user()->name }}</span>
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"
                            id="profileDropdown">
                            <img src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : 'https://ui-avatars.com/api/?name=' . Auth::user()->nama }}" alt="profile" />
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown"
                            aria-labelledby="profileDropdown">
                            <a href="{{ route('logout') }}" class="dropdown-item"
                                onclick="event.preventDefault();
                                              document.getElementById('logout-form').submit();">
                                <i class="ti-power-off text-primary"></i> Logout </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="container-fluid page-body-wrapper">
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/home">
                            <i class="bi bi-grid menu-icon"></i>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </li>
                    <hr>
                    @if (Auth::user()->role == 'admin' || Auth::user()->role == 'kepala_pondok')
                        <span>MASTER</span>
                        <li class="nav-item">
                            <a class="nav-link" href="/santri">
                                <i class="bi bi-person menu-icon"></i>
                                <span class="menu-title">Santri</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/guru">
                                <i class="bi bi-person-badge menu-icon"></i>
                                <span class="menu-title">Guru</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/kepala_pondok">
                                <i class="bi bi-person-gear menu-icon"></i>
                                <span class="menu-title">Kepala Pondok</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/wali">
                                <i class="bi bi-people menu-icon"></i>
                                <span class="menu-title">Wali Santri</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/kelas">
                                <i class="bi bi-house-door menu-icon"></i>
                                <span class="menu-title">Kelas</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/hafalan">
                                <i class="bi bi-book menu-icon"></i>
                                <span class="menu-title">Hafalan</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/mapel">
                                <i class="bi bi-journal-text menu-icon"></i>
                                <span class="menu-title">Mapel</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/tahun-ajaran">
                                <i class="bi bi-journal-text menu-icon"></i>
                                <span class="menu-title">Tahun Ajaran</span>
                            </a>
                        </li>
                    @endif

                    @if (Auth::user()->role == 'guru')
                        <li class="nav-item">
                            <a class="nav-link" href="/setor">
                                <i class="bi bi-upload menu-icon"></i>
                                <span class="menu-title">Setor Hafalan</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/absensi">
                                <i class="bi bi-bar-chart menu-icon"></i>
                                <span class="menu-title">Absensi dan Nilai</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('rekap.index') }}">
                                <i class="bi bi-book menu-icon"></i>
                                <span class="menu-title">Rekap Hafalan</span>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link" href="/nilai">
                                <i class="bi bi-bar-chart menu-icon"></i>
                                <span class="menu-title">Laporan</span>
                            </a>
                        </li>

                    @endif

                    @if (Auth::user()->role == 'wali_santri')
                        <li class="nav-item">
                            <a class="nav-link" href="/santri-nilai">
                                <i class="bi bi-graph-up menu-icon"></i>
                                <span class="menu-title">Nilai Santri</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>

            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets') }}/vendors/js/vendor.bundle.base.js"></script>
    <script src="{{ asset('assets') }}/vendors/chart.js/chart.umd.js"></script>
    <script src="{{ asset('assets') }}/js/off-canvas.js"></script>
    <script src="{{ asset('assets') }}/js/template.js"></script>
    <script src="{{ asset('assets') }}/js/settings.js"></script>
    <script src="{{ asset('assets') }}/js/todolist.js"></script>
    <script src="{{ asset('assets') }}/js/jquery.cookie.js" type="text/javascript"></script>
    <script src="{{ asset('assets') }}/js/dashboard.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @yield('scripts')
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
            $('.select2').select2();
        });
    </script>
    @if ($errors->any())
        <script>
            let errorMessages = '';
            @foreach ($errors->all() as $error)
                errorMessages += "{{ $error }}\n";
            @endforeach

            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: errorMessages,
            });
        </script>
    @endif
    @if (session('success') || session('error'))
        <script>
            $(document).ready(function() {
                var successMessage = "{{ session('success') }}";
                var errorMessage = "{{ session('error') }}";

                if (successMessage) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: successMessage,
                    });
                }

                if (errorMessage) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage,
                    });
                }
            });
        </script>
    @endif
</body>

</html>
