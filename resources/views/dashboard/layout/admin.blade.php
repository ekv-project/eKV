<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @php
        if(!empty($settings)){
            if(!empty($settings['institute_name'])){
                $instituteName = ucwords($settings['institute_name']);
            }else{
                $instituteName = "Kolej Vokasional Malaysia";
            }
        }else{
            $instituteName = "Kolej Vokasional Malaysia";
        }
    @endphp
    <meta name="description" content="{{ 'Sistem maklumat pelajar - ' . $instituteName . '.'}}" >
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstraps-icons/font/bootstrap-icons.css') }}">
    @if(Storage::disk('local')->exists('public/img/system/logo-300.png'))
        <link rel="shortcut icon" href="{{ asset('storage/img/system/logo-300.png') }}" type="image/png">
    @elseif(Storage::disk('local')->exists('public/img/system/logo-def-300.png'))
        <link rel="shortcut icon" href="{{ asset('storage/img/system/logo-def-300.png') }}" type="image/png">
    @endif
    <title>{{ $page }} - {{ env('APP_NAME') }} | {{ $instituteName }}</title>
    @bukStyles
</head>
<body>
    <div id="background-image"></div>
    <div class="container-fluid row min-vh-100 w-100 d-flex flex-column align-items-center m-0 p-0">
        <div class="col-11 col-lg-10 mt-3">
            <a href="{{ route('dashboard') }}" class="btn btn-primary"><i class="bi bi-arrow-return-left"></i> Kembali Ke Dashboard</a>
        </div>
        <div class="col-11 col-lg-10 rounded-3 shadow-lg mt-3 mb-0 ml-0 mr-0 bg-primary">
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
                <div class="container-fluid">
                    <a class="navbar-brand" href="{{ route('admin') }}">Pusat Pentadbir</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Kursus
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="{{ route('admin.course') }}">Senarai</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.course.add') }}">Tambah</a></li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Program
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="{{ route('admin.program') }}">Senarai</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.program.add') }}">Tambah</a></li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Tahap Pengajian
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="{{ route('admin.studylevel') }}">Senarai</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.studylevel.add') }}">Tambah</a></li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Pengguna
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="{{ route('admin.user') }}">Senarai</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.user.add') }}">Tambah</a></li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Kelas
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="{{ route('admin.classroom') }}">Senarai</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.classroom.add') }}">Tambah</a></li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Institut
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="{{ route('admin.institute') }}">Lihat</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.institute.update') }}">Kemas Kini</a></li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Pengumuman
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="{{ route('admin.announcement') }}">Senarai</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.announcement.add') }}">Tambah</a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.statistic') }}">Statistik</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
        <div class="col-11 col-lg-10 rounded-3 shadow-lg mt-2 mb-2 bg-light min-vh-100">
            @yield('content')
        </div>
        <div class="col-11 col-lg-10 rounded-3 shadow-lg mt-2 mb-3 bg-primary">
            <x-copyright-notice colorScheme="light" />
        </div>
    </div>
    <script type="module" src="{{ asset('js/app.js') }}"></script>
    @bukScripts
</body>
</html>
