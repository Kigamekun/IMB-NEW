<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SIMPOL</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ url('assets-adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('assets-adminlte/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ url('assets-adminlte/bower_components/Ionicons/css/ionicons.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('assets-adminlte/dist/css/AdminLTE.min.css') }}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ url('assets-adminlte/dist/css/skins/_all-skins.min.css') }}">
    <!-- Morris chart -->
    <link rel="stylesheet" href="{{ url('assets-adminlte/bower_components/morris.js/morris.css') }}">
    <!-- jvectormap -->
    <link rel="stylesheet" href="{{ url('assets-adminlte/bower_components/jvectormap/jquery-jvectormap.css') }}">
    <!-- Date Picker -->
    <link rel="stylesheet"
        href="{{ url('assets-adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet"
        href="{{ url('assets-adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    @yield('styles')
    <!-- Google Font -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition skin-blue sidebar-mini">

    <style>
        .dt-search {
            margin-bottom: 10px;
        }
        .menu-text {
          text-wrap: wrap;
        }
    </style>
    <div class="wrapper">

        <header class="main-header">
            <!-- Logo -->
            <a href="index2.html" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-lg"><b>SIMPOL</b><small> v.02</small></span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- Messages: style can be found in dropdown.less-->
                        {{-- <li class="dropdown messages-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-envelope-o"></i>
                                <span class="label label-success">4</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">You have 4 messages</li>
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <ul class="menu">
                                        <li><!-- start message -->
                                            <a href="#">
                                                <div class="pull-left">
                                                    <img src="dist/img/user2-160x160.jpg" class="img-circle"
                                                        alt="User Image">
                                                </div>
                                                <h4>
                                                    Support Team
                                                    <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                                </h4>
                                                <p>Why not buy a new awesome theme?</p>
                                            </a>
                                        </li>
                                        <!-- end message -->
                                        <li>
                                            <a href="#">
                                                <div class="pull-left">
                                                    <img src="dist/img/user3-128x128.jpg" class="img-circle"
                                                        alt="User Image">
                                                </div>
                                                <h4>
                                                    AdminLTE Design Team
                                                    <small><i class="fa fa-clock-o"></i> 2 hours</small>
                                                </h4>
                                                <p>Why not buy a new awesome theme?</p>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <div class="pull-left">
                                                    <img src="dist/img/user4-128x128.jpg" class="img-circle"
                                                        alt="User Image">
                                                </div>
                                                <h4>
                                                    Developers
                                                    <small><i class="fa fa-clock-o"></i> Today</small>
                                                </h4>
                                                <p>Why not buy a new awesome theme?</p>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <div class="pull-left">
                                                    <img src="dist/img/user3-128x128.jpg" class="img-circle"
                                                        alt="User Image">
                                                </div>
                                                <h4>
                                                    Sales Department
                                                    <small><i class="fa fa-clock-o"></i> Yesterday</small>
                                                </h4>
                                                <p>Why not buy a new awesome theme?</p>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <div class="pull-left">
                                                    <img src="dist/img/user4-128x128.jpg" class="img-circle"
                                                        alt="User Image">
                                                </div>
                                                <h4>
                                                    Reviewers
                                                    <small><i class="fa fa-clock-o"></i> 2 days</small>
                                                </h4>
                                                <p>Why not buy a new awesome theme?</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="footer"><a href="#">See All Messages</a></li>
                            </ul>
                        </li>
                        <!-- Notifications: style can be found in dropdown.less -->
                        <li class="dropdown notifications-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-bell-o"></i>
                                <span class="label label-warning">10</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">You have 10 notifications</li>
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <ul class="menu">
                                        <li>
                                            <a href="#">
                                                <i class="fa fa-users text-aqua"></i> 5 new members joined today
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="fa fa-warning text-yellow"></i> Very long description here
                                                that may not fit into the
                                                page and may cause design problems
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="fa fa-users text-red"></i> 5 new members joined
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="fa fa-shopping-cart text-green"></i> 25 sales made
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="fa fa-user text-red"></i> You changed your username
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="footer"><a href="#">View all</a></li>
                            </ul>
                        </li>
                        <!-- Tasks: style can be found in dropdown.less -->
                        <li class="dropdown tasks-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-flag-o"></i>
                                <span class="label label-danger">9</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">You have 9 tasks</li>
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <ul class="menu">
                                        <li><!-- Task item -->
                                            <a href="#">
                                                <h3>
                                                    Design some buttons
                                                    <small class="pull-right">20%</small>
                                                </h3>
                                                <div class="progress xs">
                                                    <div class="progress-bar progress-bar-aqua" style="width: 20%"
                                                        role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                        <span class="sr-only">20% Complete</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <!-- end task item -->
                                        <li><!-- Task item -->
                                            <a href="#">
                                                <h3>
                                                    Create a nice theme
                                                    <small class="pull-right">40%</small>
                                                </h3>
                                                <div class="progress xs">
                                                    <div class="progress-bar progress-bar-green" style="width: 40%"
                                                        role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                        <span class="sr-only">40% Complete</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <!-- end task item -->
                                        <li><!-- Task item -->
                                            <a href="#">
                                                <h3>
                                                    Some task I need to do
                                                    <small class="pull-right">60%</small>
                                                </h3>
                                                <div class="progress xs">
                                                    <div class="progress-bar progress-bar-red" style="width: 60%"
                                                        role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                        <span class="sr-only">60% Complete</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <!-- end task item -->
                                        <li><!-- Task item -->
                                            <a href="#">
                                                <h3>
                                                    Make beautiful transitions
                                                    <small class="pull-right">80%</small>
                                                </h3>
                                                <div class="progress xs">
                                                    <div class="progress-bar progress-bar-yellow" style="width: 80%"
                                                        role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                        <span class="sr-only">80% Complete</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <!-- end task item -->
                                    </ul>
                                </li>
                                <li class="footer">
                                    <a href="#">View all tasks</a>
                                </li>
                            </ul>
                        </li> --}}
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <span class="hidden-xs">{{Auth::user()->name_user}}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">

                                    <p>
                                        {{Auth::user()->name_user}}
                                        <small>DKPP</small>
                                    </p>
                                </li>
                                <!-- Menu Body -->
                                {{-- <li class="user-body">
                                    <div class="row">
                                        <div class="col-xs-4 text-center">
                                            <a href="#">Followers</a>
                                        </div>
                                        <div class="col-xs-4 text-center">
                                            <a href="#">Sales</a>
                                        </div>
                                        <div class="col-xs-4 text-center">
                                            <a href="#">Friends</a>
                                        </div>
                                    </div>
                                    <!-- /.row -->
                                </li> --}}
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="http://103.51.103.105/simpol/app.php?appmd=CUSER" class="btn btn-default btn-flat">Update Profile</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="#" class="btn btn-default btn-flat">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <!-- Control Sidebar Toggle Button -->
                        <li>
                            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                {{-- <div class="user-panel">
                    <div class="pull-left image">
                        <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p>{{Auth::user()->name_user}}</p>
                        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                    </div>
                </div> --}}

                <!-- /.search form -->
                <!-- sidebar menu: : style can be found in sidebar.less -->


                @php

                    $today = Carbon\Carbon::now();

// Format tanggal sesuai keinginan
$formattedDate = $today->isoFormat("dddd, DD MMMM YYYY");

                @endphp
                <ul id="sidebarMenu" class="sidebar-menu" data-widget="tree">
                    <li class="header">{{$formattedDate}}</li>
                    <li >
                      <a
                        href="http://103.51.103.105/simpol/app.php"
                        style="
                              padding: 12px 5px 12px 15px;
                                display: block;
                              "
                        class="nav-link"
                      >
                        <i class="fa fa-pie-chart"></i>
                        <span style="margin-left:-0px" class="title menu-text">
                          KEMBALI KE SIMPOL
                        </span>
                      </a>
                    </li>
                    <li class="treeview " data-level="0">
                      <a class="nav-link">
                        <i class="fa fa-folder"></i>
                        <span class="title menu-text">MASTER</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                      </a>

                      {{-- WILAYAH  --}}
                      <ul class="treeview-menu" style="display: none;">
                        <li class="treeview " data-level="1">
                          <a style="padding-left:20px" class="nav-link">
                            <i class="fa fa-square-o"></i>
                            <span class="title menu-text">WILAYAH</span>
                            <span class="pull-right-container">
                              <i class="fa fa-angle-left pull-right"></i>
                            </span>
                          </a>
                          <ul class="treeview-menu" style="display: none;">
                            <li class="treeview " data-level="2">
                              <a
                                style="padding-left:40px"
                                class="nav-link"
                                onclick="redirectAndRun('APP1011', 'Provinsi', 'menu.php?cform=md_prov', 'relative');"
                              >
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">Provinsi</span>
                                <span></span>
                              </a>
                            </li>
                            <li class="treeview " data-level="2">
                              <a
                                style="padding-left:40px"
                                class="nav-link"
                                onclick="redirectAndRun('APP1012', 'Kabupaten/Kota', 'menu.php?cform=md_kab', 'relative');"
                              >
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">Kabupaten/Kota</span>
                                <span></span>
                              </a>
                            </li>
                            <li class="treeview " data-level="2">
                              <a
                                style="padding-left:40px"
                                class="nav-link"
                                onclick="redirectAndRun('APP1013', 'Kecamatan', 'menu.php?cform=md_kec', 'relative');"
                              >
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">Kecamatan</span>
                                <span></span>
                              </a>
                            </li>
                            <li class="treeview " data-level="2">
                              <a
                                style="padding-left:40px"
                                class="nav-link"
                                onclick="redirectAndRun('APP1014', 'Desa/Kelurahan', 'menu.php?cform=md_kel', 'relative');"
                              >
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">Desa/Kelurahan</span>
                                <span></span>
                              </a>
                            </li>
                          </ul>
                        </li>

                        {{-- Bangunan Sub menu --}}
                        <li class="treeview " data-level="1">
                          <a style="padding-left:20px" class="nav-link">
                            <i class="fa fa-square-o"></i>
                            <span class="title menu-text">BANGUNAN</span>
                            <span class="pull-right-container">
                              <i class="fa fa-angle-left pull-right"></i>
                            </span>
                          </a>
                          <ul class="treeview-menu" style="display: none;">
                            {{-- Kode Bangunan --}}
                            <li class="treeview " data-level="2">
                              <a
                                style="padding-left:40px"
                                class="nav-link"
                                onclick="redirectAndRun('APP1021', 'Kode Bangunan', 'menu.php?cform=md_kodebang', 'relative');"
                              >
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">Kode Bangunan</span>
                                <span></span>
                              </a>
                            </li>
                            <li class="treeview " data-level="2">
                              <a
                                style="padding-left:40px"
                                class="nav-link"
                                onclick="redirectAndRun('APP1022', 'Fungsi Bangunan', 'menu.php?cform=md_fungsibang', 'relative');"
                              >
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">Fungsi Bangunan</span>
                                <span></span>
                              </a>
                            </li>
                            <li class="treeview " data-level="2">
                              <a
                                style="padding-left:40px"
                                class="nav-link"
                                onclick="redirectAndRun('APP1023', 'Jenis Kegiatan', 'menu.php?cform=md_jeniskeg', 'relative');"
                              >
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">Jenis Kegiatan</span>
                                <span></span>
                              </a>
                            </li>
                            <li class="treeview " data-level="2">
                              <a
                                style="padding-left:40px"
                                class="nav-link"
                                onclick="redirectAndRun('APP1024', 'Bangunan Penunjang', 'menu.php?cform=md_bangpenunjang', 'relative');">
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">Bangunan Penunjang</span>
                                <span></span>
                              </a>
                            </li>
                            <li class="treeview " data-level="2">
                              <a
                                style="padding-left:40px"
                                class="nav-link"
                                onclick="redirectAndRun('APP1025', 'Kelompok Bangunan', 'menu.php?cform=md_bangkelompok', 'relative');"
                              >
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">Kelompok Bangunan</span>
                                <span></span>
                              </a>
                            </li>
                          </ul>
                        </li>
                        {{-- DOKUMEN SUB TAB --}}
                        <li class="treeview " data-level="1">
                          <a style="padding-left:20px" class="nav-link">
                            <i class="fa fa-square-o"></i>
                            <span class="title menu-text">DOKUMEN</span>
                            <span class="pull-right-container">
                              <i class="fa fa-angle-left pull-right"></i>
                            </span>
                          </a>
                          <ul class="treeview-menu" style="display: none;">
                            <li class="treeview " data-level="2">
                              <a
                                style="padding-left:40px"
                                class="nav-link"
                                onclick="redirectAndRun('APP1031', 'Kendali Dokumen', 'menu.php?cform=md_kendalidoc', 'relative');"
                              >
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">Kendali Dokumen</span>
                                <span></span>
                              </a>
                            </li>
                            <li class="treeview " data-level="2">
                              <a
                                style="padding-left:40px"
                                class="nav-link"
                                onclick="redirectAndRun('APP1032', 'Periksa Dokumen', 'menu.php?cform=md_periksadoc', 'relative');"
                              >
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">Periksa Dokumen</span>
                                <span></span>
                              </a>
                            </li>
                            <li class="treeview " data-level="2">
                              <a
                                style="padding-left:40px"
                                class="nav-link"
                                onclick="redirectAndRun('APP1033', 'Posisi Dokumen', 'menu.php?cform=md_posisidoc', 'relative');"
                              >
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">Posisi Dokumen</span>
                                <span></span>
                              </a>
                            </li>
                            <li class="treeview " data-level="2">
                              <a
                                style="padding-left:40px"
                                class="nav-link"
                                onclick="redirectAndRun('APP1034', 'Surat Tanah', 'menu.php?cform=md_tanahdoc', 'relative');"
                              >
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">Surat Tanah</span>
                                <span></span>
                              </a>
                            </li>
                          </ul>
                        </li>

                        {{-- PEGAWAI TAB --}}
                        <li class="treeview " data-level="1">
                          <a style="padding-left:20px" class="nav-link">
                            <i class="fa fa-square-o"></i>
                            <span class="title menu-text">PEGAWAI</span>
                            <span class="pull-right-container">
                              <i class="fa fa-angle-left pull-right"></i>
                            </span>
                          </a>
                          <ul class="treeview-menu" style="display: none;">
                            <li class="treeview " data-level="2">
                              <a
                                style="padding-left:40px"
                                class="nav-link"
                                onclick="redirectAndRun('APP1041', 'Instansi', 'menu.php?cform=md_instansi', 'relative');"
                              >
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">Instansi</span>
                                <span></span>
                              </a>
                            </li>
                            <li class="treeview " data-level="2">
                              <a
                                style="padding-left:40px"
                                class="nav-link"
                                onclick="redirectAndRun('APP1042', 'Bidang', 'menu.php?cform=md_bidang', 'relative');"
                              >
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">Bidang</span>
                                <span></span>
                              </a>
                            </li>
                            <li class="treeview " data-level="2">
                              <a
                                style="padding-left:40px"
                                class="nav-link"
                                onclick="redirectAndRun('APP1043', 'Seksi', 'menu.php?cform=md_bidangsub', 'relative');"
                              >
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">Seksi</span>
                                <span></span>
                              </a>
                            </li>
                            <li class="treeview " data-level="2">
                              <a
                                style="padding-left:40px"
                                class="nav-link"
                                onclick="redirectAndRun('APP1044', 'Golongan', 'menu.php?cform=md_golongan', 'relative');"
                              >
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">Golongan</span>
                                <span></span>
                              </a>
                            </li>
                            <li class="treeview " data-level="2">
                              <a
                                style="padding-left:40px"
                                class="nav-link"
                                onclick="redirectAndRun('APP1045', 'Jabatan', 'menu.php?cform=md_jabatan', 'relative');"
                              >
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">Jabatan</span>
                                <span></span>
                              </a>
                            </li>
                          </ul>
                        </li>

                        {{-- FORMULA --}}
                        <li class="treeview " data-level="1">
                          <a style="padding-left:20px" class="nav-link">
                            <i class="fa fa-square-o"></i>
                            <span class="title menu-text">FORMULA</span>
                            <span class="pull-right-container">
                              <i class="fa fa-angle-left pull-right"></i>
                            </span>
                          </a>
                          <ul class="treeview-menu" style="display: none;">
                            <li class="treeview " data-level="2">
                              <a
                                style="padding-left:40px"
                                class="nav-link"
                                onclick="redirectAndRun('APP1051', 'Parameter', 'menu.php?cform=md_izinparam', 'relative');"
                              >
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">Parameter</span>
                                <span></span>
                              </a>
                            </li>
                            <li class="treeview " data-level="2">
                              <a
                                style="padding-left:40px"
                                class="nav-link"
                                onclick="redirectAndRun('APP1052', 'Index Parameter', 'menu.php?cform=md_izinparamidx', 'relative');"
                              >
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">Index Parameter</span>
                                <span></span>
                              </a>
                            </li>
                            <li class="treeview " data-level="2">
                              <a
                                style="padding-left:40px"
                                class="nav-link"
                                onclick="redirectAndRun('APP1053', 'Objek Bangunan', 'menu.php?cform=md_bangobjek', 'relative');"
                              >
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">Objek Bangunan</span>
                                <span></span>
                              </a>
                            </li>
                            <li class="treeview " data-level="2">
                              <a
                                style="padding-left:40px"
                                class="nav-link"
                                onclick="redirectAndRun('APP1054', 'Harga Satuan Bangunan', 'menu.php?cform=md_bangharga','relative');"
                              >
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">Harga Satuan Bangunan</span>
                                <span></span>
                              </a>
                            </li>
                          </ul>
                        </li>

                        <li class="treeview " data-level="1">
                            <a style="padding-left:20px" class="nav-link">
                              <i class="fa fa-square-o"></i>
                              <span class="title menu-text">IMB</span>
                              <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                              </span>
                            </a>
                            <ul class="treeview-menu" style="display: none;">
                              <li class=" " data-level="2">
                                <a
                                  style="padding-left:40px"
                                  class="nav-link" href="{{ route('tujuan-surat.index') }}"
                                >
                                  <i class="fa fa-square-o"></i>
                                  Tujuan Surat
                                </a>
                              </li>
                              <li >
                                <a
                                  style="padding-left:40px"
                                  class="nav-link"
                                  href="{{ route('jenis-non-perum.index') }}"
                                >
                                  <i class="fa fa-square-o"></i>
                                  <span class="title menu-text">Jenis Non Perum</span>
                                  <span></span>
                                </a>
                              </li>

                            </ul>
                          </li>


                      </ul>
                    </li>

                    {{-- REGISTER --}}
                    <li class="treeview " data-level="0">
                      <a class="nav-link">
                        <i class="fa fa-user"></i>
                        <span class="title menu-text">LOKET &amp; REGISTER</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                      </a>
                      <ul class="treeview-menu" style="display: none;">
                        <li class="treeview " data-level="1">
                          <a style="padding-left:20px" class="nav-link">
                            <i class="fa fa-square-o"></i>
                            <span class="title menu-text">LOKET</span>
                            <span class="pull-right-container">
                              <i class="fa fa-angle-left pull-right"></i>
                            </span>
                          </a>
                          <ul class="treeview-menu" style="display: none;">
                            <li class="treeview " data-level="2">
                              <a
                                style="padding-left:40px"
                                class="nav-link"
                                onclick="redirectAndRun('APP1055', 'Status Permohonan', 'menu.php?cform=request_state','relative');"
                              >
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">Status Permohonan</span>
                                <span></span>
                              </a>
                            </li>
                            <li class="treeview " data-level="2">
                              <a
                                style="padding-left:40px"
                                class="nav-link"
                                onclick="redirectAndRun('APP1056', 'Import Permohonan', 'menu.php?cform=lok_request','relative');"
                              >
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">Import Permohonan</span>
                                <span></span>
                              </a>
                            </li>
                            <li class="treeview " data-level="2">
                              <a
                                style="padding-left:40px"
                                class="nav-link"
                                onclick="redirectAndRun('APP1057', 'Data Loket PDRT', 'menu.php?cform=lok_reg','relative');"
                              >
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">Data Loket PDRT</span>
                                <span></span>
                              </a>
                            </li>
                            <li class="treeview " data-level="2">
                              <a
                                style="padding-left:40px"
                                class="nav-link"
                                onclick="redirectAndRun('APP1058', 'Status Loket PDRT', 'menu.php?cform=request_state','relative');"
                              >
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">Status Loket PDRT</span>
                                <span></span>
                              </a>
                            </li>
                          </ul>
                        </li>
                        {{-- REGISTER --}}
                        <li class="treeview " data-level="1">
                          <a style="padding-left:20px" class="nav-link">
                            <i class="fa fa-square-o"></i>
                            <span class="title menu-text">REGISTER</span>
                            <span class="pull-right-container">
                              <i class="fa fa-angle-left pull-right"></i>
                            </span>
                          </a>
                          <ul class="treeview-menu" style="display: none;">
                            <li class="treeview " data-level="2">
                              <a
                                style="padding-left:40px"
                                class="nav-link"
                                onclick="redirectAndRun('APP1059', 'Import Data Loket', 'menu.php?cform=pdrt_lokimport','relative');"
                              >
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">Import Data Loket</span>
                                <span></span>
                              </a>
                            </li>
                            <li class="treeview " data-level="2">
                              <a
                                style="padding-left:40px"
                                class="nav-link"
                                onclick="redirectAndRun('APP1060', 'Data Register PDRT', 'menu.php?cform=pdrt_reg','relative');"
                              >
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">Data Register PDRT</span>
                                <span></span>
                              </a>
                            </li>
                            <li class="treeview " data-level="2">
                              <a
                                style="padding-left:40px"
                                class="nav-link"
                                onclick="redirectAndRun('APP1061', 'Cetak Form PDRT', 'menu.php?cform=pdrt_printform','relative');"
                              >
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">Cetak Form PDRT</span>
                                <span></span>
                              </a>
                            </li>
                          </ul>
                        </li>
                        <li class="treeview " data-level="1">
                          <a
                            style="padding-left:20px"
                            class="nav-link"
                            onclick="redirectAndRun('APP1062', 'Cek Status PDRT', 'menu.php?cform=request_state','relative');"
                          >
                            <i class="fa fa-square-o"></i>
                            <span class="title menu-text">Cek Status PDRT</span>
                            <span></span>
                          </a>
                        </li>
                      </ul>
                    </li>

                    {{-- Kajian --}}
                    <li class="treeview " data-level="0">
                      <a class="nav-link">
                        <i class="fa fa-camera"></i>
                        <span class="title menu-text">SURVEY &amp; KAJIAN</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                      </a>
                      <ul class="treeview-menu" style="display: none;">
                        <li class="treeview " data-level="1">
                          <a
                            style="padding-left:20px"
                            class="nav-link"
                            onclick="redirectAndRun('APP4001', 'Cetak Blanko Survey', 'menu.php?cform=pdrt_printblanko','relative');"
                          >
                            <i class="fa fa-square-o"></i>
                            <span class="title menu-text">Cetak Blanko Survey</span>
                            <span></span>
                          </a>
                        </li>
                        <li class="treeview " data-level="1">
                          <a
                            style="padding-left:20px"
                            class="nav-link"
                            onclick="redirectAndRun('APP4004', 'Upload Foto Survey', 'menu.php?cform=pdrt_uploadsurvey','relative');"
                          >
                            <i class="fa fa-square-o"></i>
                            <span class="title menu-text">Upload Foto Survey</span>
                            <span></span>
                          </a>
                        </li>
                        <li class="treeview " data-level="1">
                          <a
                            style="padding-left:20px"
                            class="nav-link"
                            onclick="redirectAndRun('APP4005', 'Hapus Foto Survey', 'menu.php?cform=pdrt_deletesurvey','relative');"
                          >
                            <i class="fa fa-square-o"></i>
                            <span class="title menu-text">Hapus Foto Survey</span>
                            <span></span>
                          </a>
                        </li>
                        <li class="treeview " data-level="1">
                          <a
                            style="padding-left:20px"
                            class="nav-link"
                            onclick="redirectAndRun('APP4003', 'Proses Kajian', 'menu.php?cform=pdrt_kajian','relative');"
                          >
                            <i class="fa fa-square-o"></i>
                            <span class="title menu-text">Proses Kajian</span>
                            <span></span>
                          </a>
                        </li>
                      </ul>
                    </li>
                    <li class="treeview " data-level="0">
                      <a class="nav-link">
                        <i class="fa fa-calculator"></i>
                        <span class="title menu-text">PERHITUNGAN &amp; SK</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                      </a>
                      <ul class="treeview-menu" style="display: none;">
                        <li class="treeview " data-level="1">
                          <a
                            style="padding-left:20px"
                            class="nav-link"
                            onclick="redirectAndRun('APP5001', 'Perhitungan PDRT', 'menu.php?cform=pdrt_hitung','relative');"
                          >
                            <i class="fa fa-square-o"></i>
                            <span class="title menu-text">Perhitungan PDRT</span>
                            <span></span>
                          </a>
                        </li>
                        <li class="treeview " data-level="1">
                          <a
                            style="padding-left:20px"
                            class="nav-link"
                            onclick="redirectAndRun('APP5002', 'Pencetakan SK', 'menu.php?cform=pdrt_printsk','relative');"
                          >
                            <i class="fa fa-square-o"></i>
                            <span class="title menu-text">Pencetakan SK</span>
                            <span></span>
                          </a>
                        </li>
                        <li class="treeview " data-level="1">
                          <a
                            style="padding-left:20px"
                            class="nav-link"
                            onclick="redirectAndRun('APP5003', 'Perhitungan Denda', 'menu.php?cform=pdrt_denda','relative');"
                          >
                            <i class="fa fa-square-o"></i>
                            <span class="title menu-text">Perhitungan Denda</span>
                            <span></span>
                          </a>
                        </li>
                        <li class="treeview " data-level="1">
                          <a
                            style="padding-left:20px"
                            class="nav-link"
                            onclick="redirectAndRun('APP5004', 'Pencetakan STS', 'menu.php?cform=pdrt_printsts','relative');"
                          >
                            <i class="fa fa-square-o"></i>
                            <span class="title menu-text">Pencetakan STS</span>
                            <span></span>
                          </a>
                        </li>
                      </ul>
                    </li>
                    <li class="treeview " data-level="0">
                      <a class="nav-link">
                        <i class="fa fa-calendar-check-o"></i>
                        <span class="title menu-text">KENDALI BERKAS</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                      </a>
                      <ul class="treeview-menu" style="display: none;">
                        <li class="treeview " data-level="1">
                          <a
                            style="padding-left:20px"
                            class="nav-link"
                            onclick="redirectAndRun('APP7001', 'Data Status Berkas', 'menu.php?cform=pdrt_datastate','relative');"
                          >
                            <i class="fa fa-square-o"></i>
                            <span class="title menu-text">Data Status Berkas</span>
                            <span></span>
                          </a>
                        </li>
                        <li class="treeview " data-level="1">
                          <a
                            style="padding-left:20px"
                            class="nav-link"
                            onclick="redirectAndRun('APP7002', 'Update Status', 'menu.php?cform=pdrt_updatestate','relative');"
                            >
                            <i class="fa fa-square-o"></i>
                            <span class="title menu-text">Update Status</span>
                            <span></span>
                          </a>
                        </li>
                        <li class="treeview " data-level="1">
                          <a
                            style="padding-left:20px"
                            class="nav-link"
                            onclick="redirectAndRun('APP7003', 'Update Dokumen', 'menu.php?cform=pdrt_updatedoc','relative');"
                          >
                            <i class="fa fa-square-o"></i>
                            <span class="title menu-text">Update Dokumen</span>
                            <span></span>
                          </a>
                        </li>
                        <li class="treeview " data-level="1">
                          <a
                            style="padding-left:20px"
                            class="nav-link"
                            onclick="redirectAndRun('APP7005', 'SK Kehilangan', 'menu.php?cform=pdrt_skhilang','relative');"
                          >
                            <i class="fa fa-square-o"></i>
                            <span class="title menu-text">SK Kehilangan</span>
                            <span></span>
                          </a>
                        </li>
                      </ul>
                    </li>
                    <li class="treeview " data-level="0">
                      <a class="nav-link">
                        <i class="fa fa-pie-chart"></i>
                        <span class="title menu-text">LAPORAN</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                      </a>
                      <ul class="treeview-menu" style="display: none;">
                        <li class="treeview " data-level="1">
                          <a
                            style="padding-left:20px"
                            class="nav-link"
                            onclick="redirectAndRun('APP8001', 'Register Per Periode', 'menu.php?cform=pdrt_reportdatper','relative');"
                            <i class="fa fa-square-o"></i>
                            <span class="title menu-text">Register Per Periode</span>
                            <span></span>
                          </a>
                        </li>
                        <li class="treeview " data-level="1">
                          <a
                            style="padding-left:20px"
                            class="nav-link"
                            onclick="redirectAndRun('APP8002', 'Register Per Bulan', 'menu.php?cform=pdrt_reportdatmonth','relative');"
                          >
                            <i class="fa fa-square-o"></i>
                            <span class="title menu-text">Register Per Bulan</span>
                            <span></span>
                          </a>
                        </li>
                        <li class="treeview " data-level="1">
                          <a
                            style="padding-left:20px"
                            class="nav-link"
                            onclick="redirectAndRun('APP8003', 'Rekap Register', 'menu.php?cform=pdrt_reportsum','relative');"
                          >
                            <i class="fa fa-square-o"></i>
                            <span class="title menu-text">Rekap Register</span>
                            <span></span>
                          </a>
                        </li>
                      </ul>
                    </li>
                    <li class="treeview " data-level="0">
                      <a class="nav-link">
                        <i class="fa fa-gear"></i>
                        <span class="title menu-text">ADMIN</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                      </a>
                      <ul class="treeview-menu" style="display: none;">
                        <li class="treeview " data-level="1">
                          <a style="padding-left:20px" class="nav-link">
                            <i class="fa fa-square-o"></i>
                            <span class="title menu-text">SISTEM</span>
                            <span class="pull-right-container">
                              <i class="fa fa-angle-left pull-right"></i>
                            </span>
                          </a>
                          <ul class="treeview-menu" style="display: none;">
                            <li class="treeview " data-level="2">
                              <a
                                style="padding-left:40px"
                                class="nav-link"
                                onclick="redirectAndRun('APP9011', 'Pengaturan Aplikasi', 'menu.php?cform=UTI_CONFIG','relative');"
                              >
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">Pengaturan Aplikasi</span>
                                <span></span>
                              </a>
                            </li>
                            <li class="treeview " data-level="2">
                              <a
                                style="padding-left:40px"
                                class="nav-link"
                                onclick="redirectAndRun('APP9012', 'Menu Aplikasi', 'menu.php?cform=UTI_MENU','relative');"
                              >
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">Menu Aplikasi</span>
                                <span></span>
                              </a>
                            </li>
                            <li class="treeview " data-level="2">
                              <a
                                style="padding-left:40px"
                                class="nav-link"
                                onclick="redirectAndRun('APP9013', 'Level Pengguna', 'menu.php?cform=UTI_USRLEVEL','relative');"
                              >
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">Level Pengguna</span>
                                <span></span>
                              </a>
                            </li>
                            <li class="treeview " data-level="2">
                              <a
                                style="padding-left:40px"
                                class="nav-link"
                                onclick="redirectAndRun('APP9014', 'KONFIGURASI', 'menu.php?cform=UTI_XCOF','relative');"
                              >
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">KONFIGURASI</span>
                                <span></span>
                              </a>
                            </li>
                          </ul>
                        </li>
                        <li class="treeview " data-level="1">
                          <a style="padding-left:20px" class="nav-link">
                            <i class="fa fa-square-o"></i>
                            <span class="title menu-text">PENGGUNA</span>
                            <span class="pull-right-container">
                              <i class="fa fa-angle-left pull-right"></i>
                            </span>
                          </a>
                          <ul class="treeview-menu" style="display: none;">
                            <li class="treeview " data-level="2">
                              <a
                                style="padding-left:40px"
                                class="nav-link"
                                onclick="redirectAndRun('APP9021', 'Data Pengguna', 'menu.php?cform=UTI_USER');"
                              >
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">Data Pengguna</span>
                                <span></span>
                              </a>
                            </li>
                            <li class="treeview " data-level="2">
                              <a
                                style="padding-left:40px"
                                class="nav-link"
                                onclick="redirectAndRun('APP9022', 'Status Login', 'menu.php?cform=UTI_LOGST');"
                              >
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">Status Login</span>
                                <span></span>
                              </a>
                            </li>
                            <li class="treeview " data-level="2">
                              <a
                                style="padding-left:40px"
                                class="nav-link"
                                onclick="redirectAndRun('APP9023', 'Update Password', 'menu.php?cform=UTI_LOGUPD');"
                              >
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">Update Password</span>
                                <span></span>
                              </a>
                            </li>
                          </ul>
                        </li>
                      </ul>
                    </li>
                </ul>






                <ul class="sidebar-menu" data-widget="tree">
                    <li class="header">Menu IMB</li>

                    <li class="treeview " data-level="0">
                      <a class="nav-link">
                        <i class="fa fa-pie-chart"></i>
                        <span class="title menu-text">IMB</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                      </a>
                      <ul class="treeview-menu " style="display: none;">
                        <li class="nav-item">
                            <a class="nav-link" style="padding-left:20px" href="{{ route('IMBIndukPerum.index') }}">
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">IMB Induk Perum</span>
                                <span></span>
                            </a>
                        </li>
                        {{-- <li class="treeview " data-level="1">
                          <a
                            style="padding-left:20px"
                            class="nav-link"
                            href="{{ route('IMBIndukPerum.index') }}"
                          >
                            <i class="fa fa-square-o"></i>
                            <span class="title menu-text">IMB Induk Perum</span>

                          </a>
                        </li> --}}

                        <!-- IMB Pecahan -->
                        <li class="nav-item">
                            <a class="nav-link" style="padding-left:20px"  href="{{ route('IMBPecahan.index') }}">
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">IMB Pecahan</span>
                                <span></span>
                            </a>
                        </li>

                        {{-- <li class="treeview " data-level="1">
                          <a style="padding-left:20px" class="nav-link"  href="{{ route('IMBPecahan.index') }}">
                            <i class="fa fa-square-o"></i>
                            <span class="title menu-text">IMB Pecahan</span>
                            <span></span>
                          </a>
                        </li> --}}

                        <!-- IMB Perluasan -->
                        <li class="nav-item">
                            <a class="nav-link" style="padding-left:20px"  href="{{ route('IMBPerluasan.index') }}">
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">IMB Perluasan</span>
                                <span></span>
                            </a>
                        </li>

                        {{-- <li class="treeview " data-level="1">
                          <a style="padding-left:20px" class="nav-link" href="/imb/IMBPerluasan">
                            <i class="fa fa-square-o"></i>
                            <span class="title menu-text">IMB Perluasan</span>
                            <span></span>
                          </a>
                        </li> --}}

                        <!-- IMB Induk Non Perum -->
                        <li class="nav-item">
                            <a class="nav-link" style="padding-left:20px"  href="{{ route('IMBIndukNonPerum.index') }}">
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">IMB Non Perum</span>
                                <span></span>
                            </a>
                        </li>
                        {{-- <li class="treeview " data-level="1">
                          <a
                            style="padding-left:20px"
                            class="nav-link"
                            href="/imb/IMBIndukNonPerum"
                          >
                            <i class="fa fa-square-o"></i>
                            <span class="title menu-text">IMB Induk Non Perum</span>
                            <span></span>
                          </a>
                        </li> --}}
                      </ul>
                    </li>





                    <li class="treeview " data-level="0">
                        <a class="nav-link">
                          <i class="fa fa-user"></i>
                          <span class="title menu-text">KENDALI DATA IMB</span>
                          <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                          </span>
                        </a>
                        <ul class="treeview-menu" style="display: none;">
                          <li class="treeview " data-level="1">
                            <a style="padding-left:20px" class="nav-link">
                              <i class="fa fa-square-o"></i>
                              <span class="title menu-text">Data Tidak Lengkap</span>
                              <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                              </span>
                            </a>
                            <ul class="treeview-menu " style="display: none;">
                                <!-- IMB Pecahan -->
                                <li class="nav-item">
                                    <a class="nav-link" style="padding-left:20px"  href="{{ route('DataIMBTidakLengkap.pecahan') }}">
                                        <i class="fa fa-square-o"></i>
                                        <span class="title menu-text">IMB Pecahan</span>
                                        <span></span>
                                    </a>
                                </li>
                                <!-- IMB Perluasan -->
                                <li class="nav-item">
                                    <a class="nav-link" style="padding-left:20px"  href="{{ route('DataIMBTidakLengkap.perluasan') }}">
                                        <i class="fa fa-square-o"></i>
                                        <span class="title menu-text">IMB Perluasan</span>
                                        <span></span>
                                    </a>
                                </li>
                              </ul>
                          </li>
                          {{-- REGISTER --}}
                          <li class="treeview " data-level="1">
                            <a style="padding-left:20px" class="nav-link">
                              <i class="fa fa-square-o"></i>
                              <span class="title menu-text">Sinkronisasi Data IMB</span>
                              <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                              </span>
                            </a>
                            <ul class="treeview-menu " style="display: none;">
                                <li class="nav-item">
                                    <a class="nav-link" style="padding-left:20px" href="{{ route('SinkronisasiLokasiIMB.index') }}">
                                        <i class="fa fa-square-o"></i>Induk
                                    </a>
                                </li>
                                <!-- Pecahan -->
                                <li class="nav-item">
                                    <a class="nav-link"
                                    style="padding-left:20px"
                                        href="{{ route('SinkronisasiLokasiIMB.index') }}?type=pecahan">
                                        <i class="fa fa-square-o"></i>Pecahan
                                    </a>
                                </li>
                                <!-- Perluasan -->
                                <li class="nav-item">
                                    <a class="nav-link"
                                    style="padding-left:20px"
                                        href="{{ route('SinkronisasiLokasiIMB.index') }}?type=perluasan">
                                        <i class="fa fa-square-o"></i>Perluasan
                                    </a>
                                </li>
                                <!-- Non Perum -->
                                <li class="nav-item">
                                    <a class="nav-link"
                                    style="padding-left:20px"
                                        href="{{ route('SinkronisasiLokasiIMB.index') }}?type=non_perum">
                                        <i class="fa fa-square-o"></i>Non Perum
                                    </a>
                                </li>
                              </ul>
                          </li>

                        </ul>
                      </li>
{{--
                    <li class="treeview " data-level="0">
                      <a class="nav-link">
                        <i class="fa fa-pie-chart"></i>
                        <span class="title menu-text">Data Tidak Lengkap</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                      </a>

                    </li> --}}


                    <li >
                      <a
                        href="{{ route('surat.index') }}"
                        style="
                              padding: 12px 5px 12px 15px;
                                display: block;
                              "
                        class="nav-link"
                      >
                        <i class="fa fa-pie-chart"></i>
                        <span style="margin-left:-0px" class="title menu-text">
                          SURAT KETERANGAN IMBG
                        </span>
                      </a>
                    </li>


                    <li class="treeview" data-level="0">
                        <a class="nav-link">
                          <i class="fa fa-pie-chart"></i>
                          <span class="title menu-text">REKAP </span>
                          <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                          </span>
                        </a>
                        <ul class="treeview-menu" style="display: none;">
                          <!-- Rekap IMB -->
                          <li class="treeview" data-level="1">
                            <a class="nav-link">
                              <i class="fa fa-square-o"></i>
                              <span class="title menu-text">Rekap IMB</span>
                              <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                              </span>
                              </a>
                            <ul class="treeview-menu " style="display: none;">
                                <!-- IMB Rekap penerbitan  -->
                                <li class="nav-item">
                                    <a class="nav-link" style="padding-left:20px"  href="{{ route('rekap.RekapPenerbitan') }}">
                                        <i class="fa fa-square-o"></i>
                                        <span class="title menu-text">Penerbitan</span>
                                        <span></span>
                                    </a>
                                </li>
                                <!-- IMB Rekap penerbitan detail -->
                                <li class="nav-item">
                                    <a class="nav-link" style="padding-left:20px"  href="{{ route('rekap.RekapPenerbitanDetail') }}">
                                        <i class="fa fa-square-o"></i>
                                        <span class="title menu-text">Penerbitan Detail</span>
                                        <span></span>
                                    </a>
                                </li>
                                <!-- IMB Rekap Unit dan Fungsi -->
                                <li class="nav-item">
                                    <a class="nav-link" style="padding-left:20px"  href="{{ route('rekap.RekapUnitDanFungsi') }}">
                                        <i class="fa fa-square-o"></i>
                                        <span class="title menu-text">Unit dan Fungsi</span>
                                        <span></span>
                                    </a>
                                </li>
                                <!-- IMB Rekap Unit dan Fungsi detail -->
                                <li class="nav-item">
                                    <a class="nav-link" style="padding-left:20px"  href="{{ route('rekap.RekapUnitDanFungsiDetail') }}">
                                        <i class="fa fa-square-o"></i>
                                        <span class="title menu-text">Unit dan Fungsi Detail</span>
                                        <span></span>
                                    </a>
                                </li>
                                <!-- IMB Rekap Lokasi -->
                                <li class="nav-item">
                                    <a class="nav-link" style="padding-left:20px"  href="{{ route('rekap.RekapLokasi') }}">
                                        <i class="fa fa-square-o"></i>
                                        <span class="title menu-text">Lokasi</span>
                                        <span></span>
                                    </a>
                                </li>
                                <!-- IMB Rekap lokasi detail -->
                                <li class="nav-item">
                                    <a class="nav-link" style="padding-left:20px"  href="{{ route('rekap.RekapLokasiDetail') }}">
                                        <i class="fa fa-square-o"></i>
                                        <span class="title menu-text">Lokasi Detail</span>
                                        <span></span>
                                    </a>
                                </li>
                                <!-- IMB Rekap Unit Fungsi dan Lokasi -->
                                <li class="nav-item">
                                    <a class="nav-link" style="padding-left:20px"  href="{{ route('rekap.RekapUnitFungsiDanLokasi') }}">
                                        <i class="fa fa-square-o"></i>
                                        <span class="title menu-text">Unit Fungsi dan Lokasi</span>
                                        <span></span>
                                    </a>
                                </li>
                                <!-- IMB Rekap Unit Fungsi dan Lokasi detail -->
                                <li class="nav-item">
                                    <a class="nav-link" style="padding-left:20px"  href="{{ route('rekap.RekapUnitFungsiDanLokasiDetail') }}">
                                        <i class="fa fa-square-o"></i>
                                        <span class="title menu-text">Unit Fungsi dan Lokasi Detail</span>
                                        <span></span>
                                    </a>
                                </li>
                              </ul>
                          </li>
                          <!-- Rekap IMB Pertahun -->
                          <li class="treeview" data-level="1">
                            <a class="nav-link">
                              <i class="fa fa-square-o"></i>
                              <span class="title menu-text">IMB Pertahun</span>
                              <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                              </span>
                            </a>
                            <ul class="treeview-menu " style="display: none;">
                                <!-- IMB Rekap unit dan fungsi  -->
                                <li class="nav-item">
                                    <a class="nav-link" style="padding-left:20px"  href="{{ route('rekap.RekapUnitDanFungsiPertahun') }}">
                                        <i class="fa fa-square-o"></i>
                                        <span class="title menu-text">Unit dan Fungsi</span>
                                        <span></span>
                                    </a>
                                </li>
                                <!-- IMB Rekap Lokasi  -->
                                <li class="nav-item">
                                    <a class="nav-link" style="padding-left:20px"  href="{{ route('rekap.RekapLokasiPertahun') }}">
                                        <i class="fa fa-square-o"></i>
                                        <span class="title menu-text">Lokasi</span>
                                        <span></span>
                                    </a>
                                </li>
                                <!-- IMB Rekap unit fungsi dan Lokasi  -->
                                <li class="nav-item">
                                    <a class="nav-link" style="padding-left:20px"  href="{{ route('rekap.RekapUnitFungsiDanLokasiPertahun') }}">
                                        <i class="fa fa-square-o"></i>
                                        <span class="title menu-text">Unit Fungsi dan Lokasi</span>
                                        <span></span>
                                    </a>
                                </li>
                            </ul>
                          </li>

                          <!-- Register IMB Pertahun -->
                          <li class="treeview" data-level="1">
                            <a class="nav-link">
                              <i class="fa fa-square-o"></i>
                              <span class="title menu-text">Register IMB Pertahun</span>
                              <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                              </span>
                            </a>
                            <ul class="treeview-menu " style="display: none;">
                              <!-- IMB Rekap  -->
                              <li class="nav-item">
                                  <a class="nav-link" style="padding-left:20px"  href="{{ route('rekap.DetailIMBInduk') }}">
                                      <i class="fa fa-square-o"></i>
                                      <span class="title menu-text">IMB Induk</span>
                                      <span></span>
                                  </a>
                              </li>
                              <!-- IMB Rekap -->
                              {{-- <li class="nav-item">
                                  <a class="nav-link" style="padding-left:20px"  href="{{ route('rekap.DetailIMBIndukList') }}">
                                      <i class="fa fa-square-o"></i>
                                      <span class="title menu-text">IMB Induk List</span>
                                      <span></span>
                                  </a>
                              </li> --}}
                              <!-- IMB Rekap -->
                              <li class="nav-item">
                                  <a class="nav-link" style="padding-left:20px"  href="{{ route('rekap.DetailIMBPecahan') }}">
                                      <i class="fa fa-square-o"></i>
                                      <span class="title menu-text">IMB Pecahan</span>
                                      <span></span>
                                  </a>
                              </li>
                              <!-- IMB Rekap  -->
                              {{-- <li class="nav-item">
                                  <a class="nav-link" style="padding-left:20px"  href="{{ route('rekap.DetailIMBPecahanList') }}">
                                      <i class="fa fa-square-o"></i>
                                      <span class="title menu-text">IMB Pecahan List</span>
                                      <span></span>
                                  </a>
                              </li> --}}
                              <!-- IMB Rekap -->
                              <li class="nav-item">
                                  <a class="nav-link" style="padding-left:20px"  href="{{ route('rekap.DetailIMBPerluasan') }}">
                                      <i class="fa fa-square-o"></i>
                                      <span class="title menu-text">IMB Perluasan</span>
                                      <span></span>
                                  </a>
                              </li>
                              {{-- <!-- IMB Rekap -->
                              <li class="nav-item">
                                  <a class="nav-link" style="padding-left:20px"  href="{{ route('rekap.DetailIMBPerluasanList') }}">
                                      <i class="fa fa-square-o"></i>
                                      <span class="title menu-text">IMB Perluasan List</span>
                                      <span></span>
                                  </a>
                              </li> --}}
                            </ul>
                          </li>

                            <!-- Rekap SK IMBG Pertahun -->
                            {{-- <li class="treeview" data-level="1">
                                <a class="nav-link">
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">SK IMBG</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                                </a>
                                <ul class="treeview-menu " style="display: none;">
                                    <!-- IMB Rekap  -->
                                    <li class="nav-item">
                                        <a class="nav-link" style="padding-left:20px"  href="{{ route('rekap.RekapSKIMBGPerbulan') }}">
                                            <i class="fa fa-square-o"></i>
                                            <span class="title menu-text">Perbulan</span>
                                            <span></span>
                                        </a>
                                    </li>
                                <!-- IMB Rekap   -->
                                    <li class="nav-item">
                                        <a class="nav-link" style="padding-left:20px"  href="{{ route('rekap.RekapSKIMBGPertahun') }}">
                                            <i class="fa fa-square-o"></i>
                                            <span class="title menu-text">Pertahun</span>
                                            <span></span>
                                        </a>
                                    </li>
                                </ul>
                            </li> --}}
                            <!-- Rekap SK IMBG REGISTER Pertahun -->
                            <li class="treeview" data-level="1">
                                <a class="nav-link">
                                <i class="fa fa-square-o"></i>
                                <span class="title menu-text">SK IMBG</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                                </a>
                                <ul class="treeview-menu " style="display: none;">
                                    <!-- IMB Rekap  -->
                                    {{-- <li class="nav-item">
                                        <a class="nav-link" style="padding-left:20px"  href="{{ route('rekap.RekapSKIMBGPerbulan') }}">
                                            <i class="fa fa-square-o"></i>
                                            <span class="title menu-text">Perbulan</span>
                                            <span></span>
                                        </a>
                                    </li>
                                <!-- IMB Rekap   -->
                                    <li class="nav-item">
                                        <a class="nav-link" style="padding-left:20px"  href="{{ route('rekap.RekapSKIMBGPertahun') }}">
                                            <i class="fa fa-square-o"></i>
                                            <span class="title menu-text">Pertahun</span>
                                            <span></span>
                                        </a>
                                    </li> --}}
                                    <!-- IMB Rekap  -->
                                    <li class="nav-item">
                                        <a class="nav-link" style="padding-left:20px"  href="{{ route('rekap.ListSurat10') }}">
                                            <i class="fa fa-square-o"></i>
                                            <span class="title menu-text">Register Perbulan</span>
                                            <span></span>
                                        </a>
                                    </li>
                                <!-- IMB Rekap   -->
                                    <li class="nav-item">
                                        <a class="nav-link" style="padding-left:20px"  href="{{ route('rekap.ListSurat') }}">
                                            <i class="fa fa-square-o"></i>
                                            <span class="title menu-text">Register Pertahun</span>
                                            <span></span>
                                        </a>
                                    </li>
                                    <!-- IMB Rekap   -->
                                    <li class="nav-item">
                                        <a class="nav-link" style="padding-left:20px" href="{{route('rekap.RekapSuratPertahun')}}">
                                            <i class="fa fa-square-o"></i>
                                            <span class="title menu-text">Rekap SK IMBG</span>
                                            <span></span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                      </li>



                     </ul>



                {{-- <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        <li class="active treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="active"><a href="index.html"><i class="fa fa-circle-o"></i> Dashboard v1</a></li>
            <li><a href="index2.html"><i class="fa fa-circle-o"></i> Dashboard v2</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-files-o"></i>
            <span>Layout Options</span>
            <span class="pull-right-container">
              <span class="label label-primary pull-right">4</span>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="pages/layout/top-nav.html"><i class="fa fa-circle-o"></i> Top Navigation</a></li>
            <li><a href="pages/layout/boxed.html"><i class="fa fa-circle-o"></i> Boxed</a></li>
            <li><a href="pages/layout/fixed.html"><i class="fa fa-circle-o"></i> Fixed</a></li>
            <li><a href="pages/layout/collapsed-sidebar.html"><i class="fa fa-circle-o"></i> Collapsed Sidebar</a></li>
          </ul>
        </li>
        <li>
          <a href="pages/widgets.html">
            <i class="fa fa-th"></i> <span>Widgets</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-green">new</small>
            </span>
          </a>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-pie-chart"></i>
            <span>Charts</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="pages/charts/chartjs.html"><i class="fa fa-circle-o"></i> ChartJS</a></li>
            <li><a href="pages/charts/morris.html"><i class="fa fa-circle-o"></i> Morris</a></li>
            <li><a href="pages/charts/flot.html"><i class="fa fa-circle-o"></i> Flot</a></li>
            <li><a href="pages/charts/inline.html"><i class="fa fa-circle-o"></i> Inline charts</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-laptop"></i>
            <span>UI Elements</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="pages/UI/general.html"><i class="fa fa-circle-o"></i> General</a></li>
            <li><a href="pages/UI/icons.html"><i class="fa fa-circle-o"></i> Icons</a></li>
            <li><a href="pages/UI/buttons.html"><i class="fa fa-circle-o"></i> Buttons</a></li>
            <li><a href="pages/UI/sliders.html"><i class="fa fa-circle-o"></i> Sliders</a></li>
            <li><a href="pages/UI/timeline.html"><i class="fa fa-circle-o"></i> Timeline</a></li>
            <li><a href="pages/UI/modals.html"><i class="fa fa-circle-o"></i> Modals</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-edit"></i> <span>Forms</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="pages/forms/general.html"><i class="fa fa-circle-o"></i> General Elements</a></li>
            <li><a href="pages/forms/advanced.html"><i class="fa fa-circle-o"></i> Advanced Elements</a></li>
            <li><a href="pages/forms/editors.html"><i class="fa fa-circle-o"></i> Editors</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-table"></i> <span>Tables</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="pages/tables/simple.html"><i class="fa fa-circle-o"></i> Simple tables</a></li>
            <li><a href="pages/tables/data.html"><i class="fa fa-circle-o"></i> Data tables</a></li>
          </ul>
        </li>
        <li>
          <a href="pages/calendar.html">
            <i class="fa fa-calendar"></i> <span>Calendar</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-red">3</small>
              <small class="label pull-right bg-blue">17</small>
            </span>
          </a>
        </li>
        <li>
          <a href="pages/mailbox/mailbox.html">
            <i class="fa fa-envelope"></i> <span>Mailbox</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-yellow">12</small>
              <small class="label pull-right bg-green">16</small>
              <small class="label pull-right bg-red">5</small>
            </span>
          </a>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-folder"></i> <span>Examples</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="pages/examples/invoice.html"><i class="fa fa-circle-o"></i> Invoice</a></li>
            <li><a href="pages/examples/profile.html"><i class="fa fa-circle-o"></i> Profile</a></li>
            <li><a href="pages/examples/login.html"><i class="fa fa-circle-o"></i> Login</a></li>
            <li><a href="pages/examples/register.html"><i class="fa fa-circle-o"></i> Register</a></li>
            <li><a href="pages/examples/lockscreen.html"><i class="fa fa-circle-o"></i> Lockscreen</a></li>
            <li><a href="pages/examples/404.html"><i class="fa fa-circle-o"></i> 404 Error</a></li>
            <li><a href="pages/examples/500.html"><i class="fa fa-circle-o"></i> 500 Error</a></li>
            <li><a href="pages/examples/blank.html"><i class="fa fa-circle-o"></i> Blank Page</a></li>
            <li><a href="pages/examples/pace.html"><i class="fa fa-circle-o"></i> Pace Page</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-share"></i> <span>Multilevel</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
            <li class="treeview">
              <a href="#"><i class="fa fa-circle-o"></i> Level One
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>
                <li class="treeview">
                  <a href="#"><i class="fa fa-circle-o"></i> Level Two
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                  </ul>
                </li>
              </ul>
            </li>
            <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
          </ul>
        </li>
        <li><a href="https://adminlte.io/docs"><i class="fa fa-book"></i> <span>Documentation</span></a></li>
        <li class="header">LABELS</li>
        <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>Important</span></a></li>
        <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>Warning</span></a></li>
        <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>Information</span></a></li>
      </ul> --}}





            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <!-- Main content -->
            <section class="content" style="padding: 40px">
                <div style="background: white;border-radius:5px;padding:10px;">
                    @yield('content')
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark" style="display: none;">
            <!-- Create the tabs -->
            <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
                <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
                <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <!-- Home tab content -->
                <div class="tab-pane" id="control-sidebar-home-tab">
                    <h3 class="control-sidebar-heading">Recent Activity</h3>
                    <ul class="control-sidebar-menu">
                        <li>
                            <a href="javascript:void(0)">
                                <i class="menu-icon fa fa-birthday-cake bg-red"></i>

                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                                    <p>Will be 23 on April 24th</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <i class="menu-icon fa fa-user bg-yellow"></i>

                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                                    <p>New phone +1(800)555-1234</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                                    <p>nora@example.com</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <i class="menu-icon fa fa-file-code-o bg-green"></i>

                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                                    <p>Execution time 5 seconds</p>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <!-- /.control-sidebar-menu -->

                    <h3 class="control-sidebar-heading">Tasks Progress</h3>
                    <ul class="control-sidebar-menu">
                        <li>
                            <a href="javascript:void(0)">
                                <h4 class="control-sidebar-subheading">
                                    Custom Template Design
                                    <span class="label label-danger pull-right">70%</span>
                                </h4>

                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <h4 class="control-sidebar-subheading">
                                    Update Resume
                                    <span class="label label-success pull-right">95%</span>
                                </h4>

                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-success" style="width: 95%"></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <h4 class="control-sidebar-subheading">
                                    Laravel Integration
                                    <span class="label label-warning pull-right">50%</span>
                                </h4>

                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <h4 class="control-sidebar-subheading">
                                    Back End Framework
                                    <span class="label label-primary pull-right">68%</span>
                                </h4>

                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <!-- /.control-sidebar-menu -->

                </div>
                <!-- /.tab-pane -->
                <!-- Stats tab content -->
                <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
                <!-- /.tab-pane -->
                <!-- Settings tab content -->
                <div class="tab-pane" id="control-sidebar-settings-tab">
                    <form method="post">
                        <h3 class="control-sidebar-heading">General Settings</h3>

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Report panel usage
                                <input type="checkbox" class="pull-right" checked>
                            </label>

                            <p>
                                Some information about this general settings option
                            </p>
                        </div>
                        <!-- /.form-group -->

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Allow mail redirect
                                <input type="checkbox" class="pull-right" checked>
                            </label>

                            <p>
                                Other sets of options are available
                            </p>
                        </div>
                        <!-- /.form-group -->

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Expose author name in posts
                                <input type="checkbox" class="pull-right" checked>
                            </label>

                            <p>
                                Allow the user to show his name in blog posts
                            </p>
                        </div>
                        <!-- /.form-group -->

                        <h3 class="control-sidebar-heading">Chat Settings</h3>

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Show me as online
                                <input type="checkbox" class="pull-right" checked>
                            </label>
                        </div>
                        <!-- /.form-group -->

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Turn off notifications
                                <input type="checkbox" class="pull-right">
                            </label>
                        </div>
                        <!-- /.form-group -->

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Delete chat history
                                <a href="javascript:void(0)" class="text-red pull-right"><i
                                        class="fa fa-trash-o"></i></a>
                            </label>
                        </div>
                        <!-- /.form-group -->
                    </form>
                </div>
                <!-- /.tab-pane -->
            </div>
        </aside>
        <!-- /.control-sidebar -->
        <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>
    </div>
    <!-- ./wrapper -->

    <!-- jQuery 3 -->
    <script src="{{ url('assets-adminlte/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ url('assets-adminlte/bower_components/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button);
    </script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ url('assets-adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- Morris.js charts -->
    <script src="{{ url('assets-adminlte/bower_components/raphael/raphael.min.js') }}"></script>
    <script src="{{ url('assets-adminlte/bower_components/morris.js/morris.min.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ url('assets-adminlte/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js') }}"></script>
    <!-- jvectormap -->
    <script src="{{ url('assets-adminlte/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script src="{{ url('assets-adminlte/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ url('assets-adminlte/bower_components/jquery-knob/dist/jquery.knob.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ url('assets-adminlte/bower_components/moment/min/moment.min.js') }}"></script>
    <script src="{{ url('assets-adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <!-- datepicker -->
    <script src="{{ url('assets-adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}">
    </script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="{{ url('assets-adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
    <!-- Slimscroll -->
    <script src="{{ url('assets-adminlte/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ url('assets-adminlte/bower_components/fastclick/lib/fastclick.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ url('assets-adminlte/dist/js/adminlte.min.js') }}"></script>

    @yield('modal')
    <!-- General JS Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
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


    <script>
        function sendAction(type) {
            alert('ada')
            // Isi input hidden untuk "type"
            const form = document.getElementById('rekapForm');
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'type';
            hiddenInput.value = type;
            form.appendChild(hiddenInput);

            // Submit form
            form.submit();
        }
    </script>

    @yield('scripts')

</body>

</html>
