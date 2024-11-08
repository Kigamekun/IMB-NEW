<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>IMB DPKPP</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ url('assets/node_modules/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/node_modules/summernote/dist/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ url('assets/node_modules/owl.carousel/dist/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/node_modules/owl.carousel/dist/assets/owl.theme.default.min.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ url('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ url('assets/css/components.css') }}">

    @yield('styles')
</head>

<body>
    @php
        $currentPath = request()->path();
    @endphp
    <div id="app">
        <div class="main-wrapper">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar">
                <form class="form-inline mr-auto">
                    <ul class="navbar-nav mr-3">
                        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i
                                    class="fas fa-bars"></i></a></li>
                        <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i
                                    class="fas fa-search"></i></a></li>
                    </ul>
                </form>
                <ul class="navbar-nav navbar-right">
                    <li class="dropdown"><a href="#" data-toggle="dropdown"
                            class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                            <img alt="image" src="{{ url('assets/img/avatar/avatar-1.png') }}"
                                class="rounded-circle mr-1">
                            <div class="d-sm-none d-lg-inline-block">Hi, {{ Auth::user()->name }}</div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="features-profile.html" class="dropdown-item has-icon">
                                <i class="far fa-user"></i> Profile
                            </a>
                            <a href="features-activities.html" class="dropdown-item has-icon">
                                <i class="fas fa-bolt"></i> Activities
                            </a>
                            <a href="features-settings.html" class="dropdown-item has-icon">
                                <i class="fas fa-cog"></i> Settings
                            </a>
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('logout') }}" method="post">
                                @csrf
                                <button type="submit" class="dropdown-item has-icon text-danger">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </div>
                    </li>
                </ul>
            </nav>
            <div class="main-sidebar sidebar-style-2">
                <aside id="sidebar-wrapper">
                    <div class="sidebar-brand">
                        <a href="index.html">IMB</a>
                    </div>
                    <div class="sidebar-brand sidebar-brand-sm">
                        <a href="index.html">St</a>
                    </div>
                    <ul class="sidebar-menu">
                        <li class="menu-header">Dashboard</li>
                        <li><a class="nav-link {{ Str::contains($currentPath, 'dashboard') ? 'text-primary' : '' }}"
                                href="{{ route('dashboard') }}"><i class="fas fa-fire"></i>
                                <span>Dashboard</span></a></li>
                        <li class="menu-header">Menu IMB</li>
                        <li><a class="nav-link {{ Str::contains($currentPath, 'IMBIndukPerum') ? 'text-primary' : '' }}"
                                href="{{ route('IMBIndukPerum.index') }}"><i class="fas fa-fire"></i>
                                <span>IMB Induk Perum</span></a></li>
                        <li><a class="nav-link {{ Str::contains($currentPath, 'IMBPecahan') ? 'text-primary' : '' }}"
                                href="{{ route('IMBPecahan.index') }}"><i class="fas fa-fire"></i>
                                <span>IMB Pecahan</span></a></li>
                        <li><a class="nav-link {{ Str::contains($currentPath, 'IMBPerluasan') ? 'text-primary' : '' }}"
                                href="{{ route('IMBPerluasan.index') }}"><i class="fas fa-fire"></i>
                                <span>IMB Perluasan</span></a></li>
                        <li><a class="nav-link {{ Str::contains($currentPath, 'IMBIndukNonPerum') ? 'text-primary' : '' }}"
                                href="{{ route('IMBIndukNonPerum.index') }}"><i class="fas fa-fire"></i>
                                <span>IMB Induk Non Perum</span></a></li>
                        <li class="menu-header">Sinkronisasi Data IMB</li>
                        <li class="dropdown {{ Str::contains($currentPath, 'IMBTidakLengkap') ? 'active' : '' }}">
                            <a href="#" class="nav-link has-dropdown"><i class="fas fa-fire"></i><span>Data Tidak
                                    Lengkap</span></a>
                            <ul class="dropdown-menu">
                                <li><a class="nav-link {{ Str::contains($currentPath, 'IMBTidakLengkap/pecahan') ? 'text-primary' : '' }}"
                                        href="{{ route('DataIMBTidakLengkap.pecahan') }}"><i class="fas fa-fire"></i>
                                        <span>IMB Pecahan </span></a></li>
                                <li><a class="nav-link {{ Str::contains($currentPath, 'IMBTidakLengkap/perluasan') ? 'text-primary' : '' }}"
                                        href="{{ route('DataIMBTidakLengkap.perluasan') }}"><i class="fas fa-fire"></i>
                                        <span>IMB Perluasan </span></a></li>
                            </ul>
                        </li>
                        <li class="dropdown {{ Str::contains($currentPath, 'SinkronisasiLokasiIMB') ? 'active' : '' }}">
                            <a href="#" class="nav-link has-dropdown"><i class="fas fa-fire"></i><span>Sinkronisasi Lokasi</span></a>
                            <ul class="dropdown-menu">
                                <li><a class="nav-link {{ request()->query('type') === null ? 'text-primary' : '' }}"
                                        href="{{ route('SinkronisasiLokasiIMB.index') }}"><i class="fas fa-fire"></i>
                                        <span>Induk</span></a></li>
                                <li><a class="nav-link {{ request()->query('type') === 'pecahan' ? 'text-primary' : '' }}"
                                        href="{{ route('SinkronisasiLokasiIMB.index') }}?type=pecahan"><i class="fas fa-fire"></i>
                                        <span>Pecahan</span></a></li>
                                <li><a class="nav-link {{ request()->query('type') === 'perluasan' ? 'text-primary' : '' }}"
                                        href="{{ route('SinkronisasiLokasiIMB.index') }}?type=perluasan"><i class="fas fa-fire"></i>
                                        <span>Perluasan</span></a></li>
                                <li><a class="nav-link {{ request()->query('type') === 'non_perum' ? 'text-primary' : '' }}"
                                        href="{{ route('SinkronisasiLokasiIMB.index') }}?type=non_perum"><i class="fas fa-fire"></i>
                                        <span>Non Perum</span></a></li>
                            </ul>
                        </li>

                        <li><a class="nav-link {{ Str::contains($currentPath, 'surat') ? 'text-primary' : '' }}"
                                href="{{ route('surat.index') }}"><i class="fas fa-fire"></i>
                                <span>Surat Keterangan IMBG</span></a></li>
                </aside>
            </div>
            <div class="main-content">
                <section class="section">
                    @yield('content')
                </section>
            </div>
        </div>
    </div>


    @yield('modal')
    <!-- General JS Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="{{ url('assets/js/stisla.js') }}"></script>

    <!-- JS Libraies -->
    <script src="{{ url('assets/node_modules/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ url('assets/node_modules/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ url('assets/node_modules/owl.carousel/dist/owl.carousel.min.js') }}"></script>
    <script src="{{ url('assets/node_modules/summernote/dist/summernote-bs4.js') }}"></script>
    <script src="{{ url('assets/node_modules/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>

    <!-- Template JS File -->
    <script src="{{ url('assets/js/scripts.js') }}"></script>
    <script src="{{ url('assets/js/custom.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ url('assets/js/page/index.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    @if (!is_null(Session::get('message')))
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: @json(Session::get('status')),
                title: @json(Session::get('status')),
                html: @json(Session::get('message')),
            });
        </script>
    @endif


    @if (!empty($errors->all()))
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });

            var err = @json($errors->all());
            var txt = '';
            Object.keys(err).forEach(element => {
                txt += err[element] + '<br>';
            });
            Toast.fire({
                icon: 'error',
                title: 'Error',
                html: txt
            });
        </script>
    @endif

    <script>
        function confirmDelete(event) {
            event.preventDefault();
            const form = event.target.closest('form');
            if (form !== null) {
                const item = event.target.getAttribute('data-item') || 'item ini';
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: `Apakah Anda yakin ingin menghapus ${item}?`,
                    icon: 'warning',
                    input: 'text',
                    inputPlaceholder: 'Masukkan alasan mengapa dihapus',
                    showCancelButton: true,
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak',
                    preConfirm: (reason) => {
                        if (!reason) {
                            Swal.showValidationMessage('Alasan tidak boleh kosong');
                            return false;
                        }
                        return reason;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const reasonInput = document.createElement('input');
                        reasonInput.type = 'hidden';
                        reasonInput.name = 'deleteReason';
                        reasonInput.value = result.value;
                        form.appendChild(reasonInput);
                        form.submit();
                    }
                });
            } else {
                console.log("Form tidak ditemukan");
            }
        }
    </script>
    @yield('scripts')
</body>

</html>
