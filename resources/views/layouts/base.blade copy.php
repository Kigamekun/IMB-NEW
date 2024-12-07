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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">



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
                            <div class="d-sm-none d-lg-inline-block">Hi, {{ Auth::user()->name_user }}</div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
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
            <div class="main-sidebar " style="overflow-y:scroll !important">
                <aside id="sidebar-wrapper">
                    <div class="sidebar-brand">
                        <a href="index.html">IMB</a>
                    </div>
                    <div class="sidebar-brand sidebar-brand-sm">
                        <a href="index.html">St</a>
                    </div>
                    {{--

                    <ul class="sidebar-menu">
                        <li class="nav-item"><a class="nav-link" href="#">Overview</a></li>
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="#submenu1" data-toggle="collapse" data-target="#submenu1">Reports</a>
                            <div class="collapse" id="submenu1" aria-expanded="false">
                                <ul class="flex-column pl-2 nav">
                                    <li class="nav-item"><a class="nav-link py-0" href="#">Orders</a></li>
                                    <li class="nav-item">
                                        <a class="nav-link collapsed py-1" href="#submenu1sub1" data-toggle="collapse" data-target="#submenu1sub1">Customers</a>
                                        <div class="collapse" id="submenu1sub1" aria-expanded="false">
                                            <ul class="flex-column nav pl-4">
                                                <li class="nav-item">
                                                    <a class="nav-link p-1" href="#">
                                                        <i class="fa fa-fw fa-clock-o"></i> Daily
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link p-1" href="#">
                                                        <i class="fa fa-fw fa-dashboard"></i> Dashboard
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link p-1" href="#">
                                                        <i class="fa fa-fw fa-bar-chart"></i> Charts
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link p-1" href="#">
                                                        <i class="fa fa-fw fa-compass"></i> Areas
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="#">Analytics</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Export</a></li>
                    </ul> --}}



                    <style>
                        .nav-item {
                            font-size: 12px !important;
                        }
                    </style>






                    <ul class="sidebar-menu" style="overflow: auto !important">
                        <!-- Header -->
                        <li class="menu-header">Menu SIMPOL</li>

                        <!-- MASTER Menu -->
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="#masterMenu" data-toggle="collapse"
                                data-target="#masterMenu" aria-expanded="false">
                                <div class="d-flex align-items-center"
                                    style="justify-content: space-between; width: 100%;">
                                    <i class="fa fa-folder" style="flex: 1;"></i>
                                    <div style="flex: 8; align-items: center; display: flex;">
                                        <span>MASTER</span>
                                    </div>
                                    <span class="pull-right-container"
                                        style="flex: 1; display: flex; justify-content: flex-end;">
                                        <i class="fa fa-angle-right pull-right" style="margin: 0 !important;"></i>
                                    </span>
                                </div>
                            </a>
                            <div class="collapse" id="masterMenu">
                                <ul class="flex-column pl-2 nav">
                                    <!-- WILAYAH Submenu -->
                                    <li class="nav-item">
                                        <a class="nav-link collapsed" href="#wilayahMenu" data-toggle="collapse"
                                            data-target="#wilayahMenu" aria-expanded="false">
                                            <i class="fa fa-square-o"></i> WILAYAH
                                            <span class="pull-right-container">
                                                <i class="fa fa-angle-right pull-right"></i>
                                            </span>
                                        </a>
                                        <div class="collapse" id="wilayahMenu">
                                            <ul class="flex-column pl-2 nav">
                                                <!-- Provinsi -->
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#"
                                                        onclick="redirectAndRun('APP1011', 'Provinsi', 'menu.php?cform=md_prov');">
                                                        <i class="fa fa-square-o"></i> Provinsi
                                                    </a>
                                                </li>
                                                <!-- Kabupaten/Kota -->
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#"
                                                        onclick="redirectAndRun('APP1012', 'Kabupaten/Kota', 'menu.php?cform=md_kab');">
                                                        <i class="fa fa-square-o"></i> Kabupaten/Kota
                                                    </a>
                                                </li>
                                                <!-- Kecamatan -->
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#"
                                                        onclick="redirectAndRun('APP1013', 'Kecamatan', 'menu.php?cform=md_kec');">
                                                        <i class="fa fa-square-o"></i> Kecamatan
                                                    </a>
                                                </li>
                                                <!-- Desa/Kelurahan -->
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#"
                                                        onclick="redirectAndRun('APP1014', 'Desa/Kelurahan', 'menu.php?cform=md_kel');">
                                                        <i class="fa fa-square-o"></i> Desa/Kelurahan
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <!-- BANGUNAN Submenu -->
                                    <li class="nav-item">
                                        <a class="nav-link collapsed" href="#bangunanMenu" data-toggle="collapse"
                                            data-target="#bangunanMenu" aria-expanded="false">
                                            <i class="fa fa-square-o"></i> BANGUNAN
                                            <span class="pull-right-container">
                                                <i class="fa fa-angle-right pull-right"></i>
                                            </span>
                                        </a>
                                        <div class="collapse" id="bangunanMenu">
                                            <ul class="flex-column pl-2 nav">
                                                <!-- Kode Bangunan -->
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#"
                                                        onclick="redirectAndRun('APP1021', 'Kode Bangunan', 'menu.php?cform=md_kodebang');">
                                                        <i class="fa fa-square-o"></i> Kode Bangunan
                                                    </a>
                                                </li>
                                                <!-- Fungsi Bangunan -->
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#"
                                                        onclick="redirectAndRun('APP1022', 'Fungsi Bangunan', 'menu.php?cform=md_fungsibang');">
                                                        <i class="fa fa-square-o"></i> Fungsi Bangunan
                                                    </a>
                                                </li>
                                                <!-- Jenis Kegiatan -->
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#"
                                                        onclick="redirectAndRun('APP1023', 'Jenis Kegiatan', 'menu.php?cform=md_jeniskeg');"
                                                        >
                                                        <i class="fa fa-square-o"></i> Jenis Kegiatan
                                                    </a>
                                                </li>
                                                <!-- Bangunan Penunjang -->
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#"
                                                        onclick="redirectAndRun('APP1024', 'Bangunan Penunjang', 'menu.php?cform=md_bangpenunjang');">
                                                        <i class="fa fa-square-o"></i> Bangunan Penunjang
                                                    </a>
                                                </li>
                                                <!-- Kelompok Bangunan -->
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#"
                                                        onclick="redirectAndRun('APP1025', 'Kelompok Bangunan', 'menu.php?cform=md_bangkelompok');">
                                                        <i class="fa fa-square-o"></i> Kelompok Bangunan
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <!-- DOKUMEN Submenu -->
                                    <li class="nav-item">
                                        <a class="nav-link collapsed" href="#dokumenMenu" data-toggle="collapse"
                                            data-target="#dokumenMenu" aria-expanded="false">
                                            <i class="fa fa-square-o"></i> DOKUMEN
                                            <span class="pull-right-container">
                                                <i class="fa fa-angle-right pull-right"></i>
                                            </span>
                                        </a>
                                        <div class="collapse" id="dokumenMenu">
                                            <ul class="flex-column pl-2 nav">
                                                <!-- Kendali Dokumen -->
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#"
                                                        onclick="redirectAndRun('APP1031', 'Kendali Dokumen', 'menu.php?cform=md_kendalidoc');"
                                                        >
                                                        <i class="fa fa-square-o"></i> Kendali Dokumen
                                                    </a>
                                                </li>
                                                <!-- Periksa Dokumen -->
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#"
                                                        onclick="redirectAndRun('APP1032', 'Periksa Dokumen', 'menu.php?cform=md_periksadoc');">
                                                        <i class="fa fa-square-o"></i> Periksa Dokumen
                                                    </a>
                                                </li>
                                                <!-- Posisi Dokumen -->
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#"
                                                        onclick="redirectAndRun('APP1033', 'Posisi Dokumen', 'menu.php?cform=md_posisidoc');"
                                                        >
                                                        <i class="fa fa-square-o"></i> Posisi Dokumen
                                                    </a>
                                                </li>
                                                <!-- Surat Tanah -->
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#"
                                                        onclick="redirectAndRun('APP1034', 'Surat Tanah', 'menu.php?cform=md_tanahdoc');">
                                                        <i class="fa fa-square-o"></i> Surat Tanah
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <!-- PEGAWAI Submenu -->
                                    <li class="nav-item">
                                        <a class="nav-link collapsed" href="#pegawaiMenu" data-toggle="collapse"
                                            data-target="#pegawaiMenu" aria-expanded="false">
                                            <i class="fa fa-square-o"></i> PEGAWAI
                                            <span class="pull-right-container">
                                                <i class="fa fa-angle-right pull-right"></i>
                                            </span>
                                        </a>
                                        <div class="collapse" id="pegawaiMenu">
                                            <ul class="flex-column pl-2 nav">
                                                <!-- Instansi -->
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#"
                                                        onclick="redirectAndRun('APP1041', 'Instansi', 'menu.php?cform=md_instansi');">
                                                        <i class="fa fa-square-o"></i> Instansi
                                                    </a>
                                                </li>
                                                <!-- Bidang -->
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#"
                                                        onclick="redirectAndRun('APP1042', 'Bidang', 'menu.php?cform=md_bidang');">
                                                        <i class="fa fa-square-o"></i> Bidang
                                                    </a>
                                                </li>
                                                <!-- Seksi -->
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#"
                                                        onclick="redirectAndRun('APP1043', 'Seksi', 'menu.php?cform=md_bidangsub');">
                                                        <i class="fa fa-square-o"></i> Seksi
                                                    </a>
                                                </li>
                                                <!-- Golongan -->
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#"
                                                        onclick="redirectAndRun('APP1044', 'Golongan', 'menu.php?cform=md_golongan');">
                                                        <i class="fa fa-square-o"></i> Golongan
                                                    </a>
                                                </li>
                                                <!-- Jabatan -->
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#"
                                                        onclick="redirectAndRun('APP1045', 'Jabatan', 'menu.php?cform=md_jabatan');">
                                                        <i class="fa fa-square-o"></i> Jabatan
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <!-- FORMULA Submenu -->
                                    <li class="nav-item">
                                        <a class="nav-link collapsed" href="#formulaMenu" data-toggle="collapse"
                                            data-target="#formulaMenu" aria-expanded="false">
                                            <i class="fa fa-square-o"></i> FORMULA
                                            <span class="pull-right-container">
                                                <i class="fa fa-angle-right pull-right"></i>
                                            </span>
                                        </a>
                                        <div class="collapse" id="formulaMenu">
                                            <ul class="flex-column pl-2 nav">
                                                <!-- Parameter -->
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#"
                                                        onclick="redirectAndRun('APP1051', 'Parameter', 'menu.php?cform=md_izinparam');">
                                                        <i class="fa fa-square-o"></i> Parameter
                                                    </a>
                                                </li>
                                                <!-- Index Parameter -->
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#"
                                                        onclick="redirectAndRun('APP1052', 'Index Parameter', 'menu.php?cform=md_izinparamidx');">
                                                        <i class="fa fa-square-o"></i> Index Parameter
                                                    </a>
                                                </li>
                                                <!-- Objek Bangunan -->
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#"
                                                        onclick="redirectAndRun('APP1053', 'Objek Bangunan', 'menu.php?cform=md_bangobjek');">
                                                        <i class="fa fa-square-o"></i> Objek Bangunan
                                                    </a>
                                                </li>
                                                <!-- Harga Satuan Bangunan -->
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#"
                                                        onclick="redirectAndRun('APP1054', 'Harga Satuan Bangunan', 'menu.php?cform=md_bangharga');">
                                                        <i class="fa fa-square-o"></i> Harga Satuan Bangunan
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- LOKET & REGISTER Menu -->
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="#loketMenu" data-toggle="collapse"
                                data-target="#loketMenu" aria-expanded="false">
                                <div class="d-flex align-items-center"
                                    style="justify-content: space-between; width: 100%;">
                                    <i class="fa fa-user" style="flex: 1;"></i>
                                    <div style="flex: 8; align-items: center; display: flex;">
                                        <span>LOKET & REGISTER</span>
                                    </div>
                                    <span class="pull-right-container"
                                        style="flex: 1; display: flex; justify-content: flex-end;">
                                        <i class="fa fa-angle-right pull-right" style="margin: 0 !important;"></i>
                                    </span>
                                </div>
                            </a>
                            <div class="collapse" id="loketMenu">
                                <ul class="flex-column pl-2 nav">
                                    <!-- LOKET Submenu -->
                                    <!-- (Kode submenu LOKET tetap sama seperti sebelumnya) -->
                                </ul>
                            </div>
                        </li>

                        <!-- SURVEY & KAJIAN Menu -->
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="#surveyMenu" data-toggle="collapse"
                                data-target="#surveyMenu" aria-expanded="false">
                                <div class="d-flex align-items-center"
                                    style="justify-content: space-between; width: 100%;">
                                    <i class="fa fa-camera" style="flex: 1;"></i>
                                    <div style="flex: 8; align-items: center; display: flex;">
                                        <span>SURVEY & KAJIAN</span>
                                    </div>
                                    <span class="pull-right-container"
                                        style="flex: 1; display: flex; justify-content: flex-end;">
                                        <i class="fa fa-angle-right pull-right" style="margin: 0 !important;"></i>
                                    </span>
                                </div>
                            </a>
                            <div class="collapse" id="surveyMenu">
                                <ul class="flex-column pl-2 nav">
                                    <!-- Cetak Blanko Survey -->
                                    <li class="nav-item">
                                        <a class="nav-link" href="#"
                                            onclick="redirectAndRun('APP4001', 'Cetak Blanko Survey', 'menu.php?cform=pdrt_printblanko');">
                                            <i class="fa fa-square-o"></i> Cetak Blanko Survey
                                        </a>
                                    </li>
                                    <!-- Upload Foto Survey -->
                                    <li class="nav-item">
                                        <a class="nav-link" href="#"
                                            onclick="redirectAndRun('APP4004', 'Upload Foto Survey', 'menu.php?cform=pdrt_uploadsurvey');">
                                            <i class="fa fa-square-o"></i> Upload Foto Survey
                                        </a>
                                    </li>
                                    <!-- Hapus Foto Survey -->
                                    <li class="nav-item">
                                        <a class="nav-link" href="#"
                                            onclick="redirectAndRun('APP4005', 'Hapus Foto Survey', 'menu.php?cform=pdrt_deletesurvey');">
                                            <i class="fa fa-square-o"></i> Hapus Foto Survey
                                        </a>
                                    </li>
                                    <!-- Proses Kajian -->
                                    <li class="nav-item">
                                        <a class="nav-link" href="#"
                                            onclick="redirectAndRun('APP4003', 'Proses Kajian', 'menu.php?cform=pdrt_kajian');">
                                            <i class="fa fa-square-o"></i> Proses Kajian
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- PERHITUNGAN & SK Menu -->
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="#perhitunganMenu" data-toggle="collapse"
                                data-target="#perhitunganMenu" aria-expanded="false">
                                <div class="d-flex align-items-center"
                                    style="justify-content: space-between; width: 100%;">
                                    <i class="fa fa-calculator" style="flex: 1;"></i>
                                    <div style="flex: 8; align-items: center; display: flex;">
                                        <span>PERHITUNGAN & SK</span>
                                    </div>
                                    <span class="pull-right-container"
                                        style="flex: 1; display: flex; justify-content: flex-end;">
                                        <i class="fa fa-angle-right pull-right" style="margin: 0 !important;"></i>
                                    </span>
                                </div>
                            </a>
                            <div class="collapse" id="perhitunganMenu">
                                <ul class="flex-column pl-2 nav">
                                    <!-- Perhitungan PDRT -->
                                    <li class="nav-item">
                                        <a class="nav-link" href="#"
                                            onclick="redirectAndRun('APP5001', 'Perhitungan PDRT', 'menu.php?cform=pdrt_hitung');">
                                            <i class="fa fa-square-o"></i> Perhitungan PDRT
                                        </a>
                                    </li>
                                    <!-- Pencetakan SK -->
                                    <li class="nav-item">
                                        <a class="nav-link" href="#"
                                            onclick="redirectAndRun('APP5002', 'Pencetakan SK', 'menu.php?cform=pdrt_printsk');">
                                            <i class="fa fa-square-o"></i> Pencetakan SK
                                        </a>
                                    </li>
                                    <!-- Perhitungan Denda -->
                                    <li class="nav-item">
                                        <a class="nav-link" href="#"
                                            onclick="redirectAndRun('APP5003', 'Perhitungan Denda', 'menu.php?cform=pdrt_denda');">
                                            <i class="fa fa-square-o"></i> Perhitungan Denda
                                        </a>
                                    </li>
                                    <!-- Pencetakan STS -->
                                    <li class="nav-item">
                                        <a class="nav-link" href="#"
                                            onclick="redirectAndRun('APP5004', 'Pencetakan STS', 'menu.php?cform=pdrt_printsts');">
                                            <i class="fa fa-square-o"></i> Pencetakan STS
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- KENDALI BERKAS Menu -->
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="#kendaliMenu" data-toggle="collapse"
                                data-target="#kendaliMenu" aria-expanded="false">
                                <div class="d-flex align-items-center"
                                    style="justify-content: space-between; width: 100%;">
                                    <i class="fa fa-calendar-check-o" style="flex: 1;"></i>
                                    <div style="flex: 8; align-items: center; display: flex;">
                                        <span>KENDALI BERKAS</span>
                                    </div>
                                    <span class="pull-right-container"
                                        style="flex: 1; display: flex; justify-content: flex-end;">
                                        <i class="fa fa-angle-right pull-right" style="margin: 0 !important;"></i>
                                    </span>
                                </div>
                            </a>
                            <div class="collapse" id="kendaliMenu">
                                <ul class="flex-column pl-2 nav">
                                    <!-- Data Status Berkas -->
                                    <li class="nav-item">
                                        <a class="nav-link" href="#"
                                            onclick="redirectAndRun('APP7001', 'Data Status Berkas', 'menu.php?cform=pdrt_datastate');">
                                            <i class="fa fa-square-o"></i> Data Status Berkas
                                        </a>
                                    </li>
                                    <!-- Update Status -->
                                    <li class="nav-item">
                                        <a class="nav-link" href="#"
                                            onclick="redirectAndRun('APP7002', 'Update Status', 'menu.php?cform=pdrt_updatestate');">
                                            <i class="fa fa-square-o"></i> Update Status
                                        </a>
                                    </li>
                                    <!-- Update Dokumen -->
                                    <li class="nav-item">
                                        <a class="nav-link" href="#"
                                            onclick="redirectAndRun('APP7003', 'Update Dokumen', 'menu.php?cform=pdrt_updatedoc');">
                                            <i class="fa fa-square-o"></i> Update Dokumen
                                        </a>
                                    </li>
                                    <!-- SK Kehilangan -->
                                    <li class="nav-item">
                                        <a class="nav-link" href="#"
                                            onclick="redirectAndRun('APP7005', 'SK Kehilangan', 'menu.php?cform=pdrt_skhilang');">
                                            <i class="fa fa-square-o"></i> SK Kehilangan
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- LAPORAN Menu -->
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="#laporanMenu" data-toggle="collapse"
                                data-target="#laporanMenu" aria-expanded="false">
                                <div class="d-flex align-items-center"
                                    style="justify-content: space-between; width: 100%;">
                                    <i class="fa fa-pie-chart" style="flex: 1;"></i>
                                    <div style="flex: 8; align-items: center; display: flex;">
                                        <span>LAPORAN</span>
                                    </div>
                                    <span class="pull-right-container"
                                        style="flex: 1; display: flex; justify-content: flex-end;">
                                        <i class="fa fa-angle-right pull-right" style="margin: 0 !important;"></i>
                                    </span>
                                </div>
                            </a>
                            <div class="collapse" id="laporanMenu">
                                <ul class="flex-column pl-2 nav">
                                    <!-- Register Per Periode -->
                                    <li class="nav-item">
                                        <a class="nav-link" href="#"
                                            onclick="redirectAndRun('APP8001', 'Register Per Periode', 'menu.php?cform=pdrt_reportdatper');">
                                            <i class="fa fa-square-o"></i> Register Per Periode
                                        </a>
                                    </li>
                                    <!-- Register Per Bulan -->
                                    <li class="nav-item">
                                        <a class="nav-link" href="#"
                                            onclick="redirectAndRun('APP8002', 'Register Per Bulan', 'menu.php?cform=pdrt_reportdatmonth');">
                                            <i class="fa fa-square-o"></i> Register Per Bulan
                                        </a>
                                    </li>
                                    <!-- Rekap Register -->
                                    <li class="nav-item">
                                        <a class="nav-link" href="#"
                                            onclick="redirectAndRun('APP8003', 'Rekap Register', 'menu.php?cform=pdrt_reportsum');">
                                            <i class="fa fa-square-o"></i> Rekap Register
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- ADMIN Menu -->
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="#adminMenu" data-toggle="collapse"
                                data-target="#adminMenu" aria-expanded="false">
                                <div class="d-flex align-items-center"
                                    style="justify-content: space-between; width: 100%;">
                                    <i class="fa fa-gear" style="flex: 1;"></i>
                                    <div style="flex: 8; align-items: center; display: flex;">
                                        <span>ADMIN</span>
                                    </div>
                                    <span class="pull-right-container"
                                        style="flex: 1; display: flex; justify-content: flex-end;">
                                        <i class="fa fa-angle-right pull-right" style="margin: 0 !important;"></i>
                                    </span>
                                </div>
                            </a>
                            <div class="collapse" id="adminMenu">
                                <ul class="flex-column pl-2 nav">
                                    <!-- SISTEM Submenu -->
                                    <li class="nav-item">
                                        <a class="nav-link collapsed" href="#sistemMenu" data-toggle="collapse"
                                            data-target="#sistemMenu" aria-expanded="false">
                                            <i class="fa fa-square-o"></i> SISTEM
                                            <span class="pull-right-container">
                                                <i class="fa fa-angle-right pull-right"></i>
                                            </span>
                                        </a>
                                        <div class="collapse" id="sistemMenu">
                                            <ul class="flex-column pl-2 nav">
                                                <!-- Pengaturan Aplikasi -->
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#"
                                                        onclick="redirectAndRun('APP9011', 'Pengaturan Aplikasi', 'menu.php?cform=UTI_CONFIG');">
                                                        <i class="fa fa-square-o"></i> Pengaturan Aplikasi
                                                    </a>
                                                </li>
                                                <!-- Menu Aplikasi -->
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#"
                                                        onclick="redirectAndRun('APP9012', 'Menu Aplikasi', 'menu.php?cform=UTI_MENU');">
                                                        <i class="fa fa-square-o"></i> Menu Aplikasi
                                                    </a>
                                                </li>
                                                <!-- Level Pengguna -->
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#"
                                                        onclick="redirectAndRun('APP9013', 'Level Pengguna', 'menu.php?cform=UTI_USRLEVEL');">
                                                        <i class="fa fa-square-o"></i> Level Pengguna
                                                    </a>
                                                </li>
                                                <!-- KONFIGURASI -->
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#"
                                                        onclick="redirectAndRun('APP9014', 'KONFIGURASI', 'menu.php?cform=UTI_XCOF');">
                                                        <i class="fa fa-square-o"></i> KONFIGURASI
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <!-- PENGGUNA Submenu -->
                                    <li class="nav-item">
                                        <a class="nav-link collapsed" href="#penggunaMenu" data-toggle="collapse"
                                            data-target="#penggunaMenu" aria-expanded="false">
                                            <i class="fa fa-square-o"></i> PENGGUNA
                                            <span class="pull-right-container">
                                                <i class="fa fa-angle-right pull-right"></i>
                                            </span>
                                        </a>
                                        <div class="collapse" id="penggunaMenu">
                                            <ul class="flex-column pl-2 nav">
                                                <!-- Data Pengguna -->
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#"
                                                        onclick="redirectAndRun('APP9021', 'Data Pengguna', 'menu.php?cform=UTI_USER');">
                                                        <i class="fa fa-square-o"></i> Data Pengguna
                                                    </a>
                                                </li>
                                                <!-- Status Login -->
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#"
                                                        onclick="redirectAndRun('APP9022', 'Status Login', 'menu.php?cform=UTI_LOGST');">
                                                        <i class="fa fa-square-o"></i> Status Login
                                                    </a>
                                                </li>
                                                <!-- Update Password -->
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#"
                                                        onclick="redirectAndRun('APP9023', 'Update Password', 'menu.php?cform=UTI_LOGUPD');">
                                                        <i class="fa fa-square-o"></i> Update Password
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="menu-header">Menu IMB</li>

                        <!-- IMB Menu -->
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="#imbMenu" data-toggle="collapse"
                                data-target="#imbMenu" aria-expanded="false">
                                <div class="d-flex align-items-center"
                                    style="justify-content: space-between; width: 100%;">
                                    <i class="fas fa-fire" style="flex: 1;"></i>
                                    <div style="flex: 8; align-items: center; display: flex;">
                                        <span>IMB</span>
                                    </div>
                                    <span class="pull-right-container"
                                        style="flex: 1; display: flex; justify-content: flex-end;">
                                        <i class="fa fa-angle-right pull-right" style="margin: 0 !important;"></i>
                                    </span>
                                </div>
                            </a>
                            <div class="collapse" id="imbMenu">
                                <ul class="flex-column pl-2 nav">
                                    <!-- IMB Induk Perum -->
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('IMBIndukPerum.index') }}">
                                            <i class="fas fa-fire"></i> IMB Induk Perum
                                        </a>
                                    </li>
                                    <!-- IMB Pecahan -->
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('IMBPecahan.index') }}">
                                            <i class="fas fa-fire"></i> IMB Pecahan
                                        </a>
                                    </li>
                                    <!-- IMB Perluasan -->
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('IMBPerluasan.index') }}">
                                            <i class="fas fa-fire"></i> IMB Perluasan
                                        </a>
                                    </li>
                                    <!-- IMB Induk Non Perum -->
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('IMBIndukNonPerum.index') }}">
                                            <i class="fas fa-fire"></i> IMB Induk Non Perum
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>


                        <!-- Data Tidak Lengkap Menu -->
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="#dataTidakLengkapMenu" data-toggle="collapse"
                                data-target="#dataTidakLengkapMenu" aria-expanded="false">
                                <div class="d-flex align-items-center"
                                    style="justify-content: space-between; width: 100%;">
                                    <i class="fas fa-fire" style="flex: 1;"></i>
                                    <div style="flex: 8; align-items: center; display: flex;">
                                        <span>Data Tidak Lengkap</span>
                                    </div>
                                    <span class="pull-right-container"
                                        style="flex: 1; display: flex; justify-content: flex-end;">
                                        <i class="fa fa-angle-right pull-right" style="margin: 0 !important;"></i>
                                    </span>
                                </div>
                            </a>
                            <div class="collapse" id="dataTidakLengkapMenu">
                                <ul class="flex-column pl-2 nav">
                                    <!-- IMB Pecahan -->
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('DataIMBTidakLengkap.pecahan') }}">
                                            <i class="fas fa-fire"></i> IMB Pecahan
                                        </a>
                                    </li>
                                    <!-- IMB Perluasan -->
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('DataIMBTidakLengkap.perluasan') }}">
                                            <i class="fas fa-fire"></i> IMB Perluasan
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Sinkronisasi Lokasi Menu -->
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="#sinkronisasiLokasiMenu" data-toggle="collapse"
                                data-target="#sinkronisasiLokasiMenu" aria-expanded="false">
                                <div class="d-flex align-items-center"
                                    style="justify-content: space-between; width: 100%;">
                                    <i class="fas fa-fire" style="flex: 1;"></i>
                                    <div style="flex: 8; align-items: center; display: flex;">
                                        <span>Sinkronisasi Lokasi</span>
                                    </div>
                                    <span class="pull-right-container"
                                        style="flex: 1; display: flex; justify-content: flex-end;">
                                        <i class="fa fa-angle-right pull-right" style="margin: 0 !important;"></i>
                                    </span>
                                </div>
                            </a>
                            <div class="collapse" id="sinkronisasiLokasiMenu">
                                <ul class="flex-column pl-2 nav">
                                    <!-- Induk -->
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('SinkronisasiLokasiIMB.index') }}">
                                            <i class="fas fa-fire"></i> Induk
                                        </a>
                                    </li>
                                    <!-- Pecahan -->
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('SinkronisasiLokasiIMB.index') }}?type=pecahan">
                                            <i class="fas fa-fire"></i> Pecahan
                                        </a>
                                    </li>
                                    <!-- Perluasan -->
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('SinkronisasiLokasiIMB.index') }}?type=perluasan">
                                            <i class="fas fa-fire"></i> Perluasan
                                        </a>
                                    </li>
                                    <!-- Non Perum -->
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('SinkronisasiLokasiIMB.index') }}?type=non_perum">
                                            <i class="fas fa-fire"></i> Non Perum
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Surat Keterangan IMBG -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('surat.index') }}">
                                <i class="fas fa-fire"></i> Surat Keterangan IMBG
                            </a>
                        </li>


                        <!-- Jenis Non Perum -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('jenis-non-perum.index') }}">
                                <i class="fas fa-fire"></i> Jenis Non Perum
                            </a>
                        </li>

                        <!-- Tujuan Surat -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('tujuan-surat.index') }}">
                                <i class="fas fa-fire"></i> Tujuan Surat
                            </a>
                        </li>
                    </ul>







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
    {{-- <script src="{{ url('assets/js/scripts.js') }}"></script> --}}
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

    <script>
        $(document).ready(function() {
            $('.nav-link[data-toggle="collapse"]').on('click', function() {
                $(this).find('.fa-angle-right').toggleClass('fa-rotate-270');
            });
        });
    </script>


    <script>
        function redirectAndRun(tabId, title, url) {
            const baseUrl = 'http://103.51.103.105/simpol/app.php';
            const params = new URLSearchParams({
                tabId: tabId,
                title: title,
                url: url
            });
            window.location.href = `${baseUrl}?${params.toString()}`;
        }
    </script>

    @yield('scripts')
</body>

</html>
