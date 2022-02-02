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
    <meta name="api-token" content="{{ csrf_token() }}">
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
<body class="d-flex flex-column">
    <div id="background-image"></div>
    {{-- Hamburger Menu Button --}}
    <div class="border-end border-5 border-light d-flex d-lg-none d-md-flex position-fixed top-0 start-0 hamburger-menu m-3" style="z-index: 100">
        <div class="hamburger-layer"></div>
        <div class="hamburger-layer"></div>
        <div class="hamburger-layer"></div>
    </div>
    <header class="container-fluid m-0 row d-flex align-items-center bg-primary" style="z-index: 80">
        {{-- Desktop Navbar --}}
        <div class="container-fluid d-flex align-items-center">
            <div class="container-fluid row d-none d-lg-flex d-sm-none justify-content-around">
                <div class="col-md-3 d-flex justify-content-center align-content-center"><a href="{{ route('dashboard') }}" class="btn button-transparent fs-5 text-light fw-normal">Dashboard</a></div>
                <div class="col-md-3 d-flex justify-content-center align-content-center"><a href="{{ route('transcript') }}" class="btn button-transparent fs-5 text-light fw-normal">Transkrip</a></div>
                <div class="col-md-3 d-flex justify-content-center align-content-center"><a href="{{ route('classroom') }}" class="btn button-transparent fs-5 text-light fw-normal">Kelas</a></div>
                @can('authAdmin')
                    <div class="col-md-3 d-flex justify-content-center align-content-center"><a href="{{ route('admin') }}" class="btn button-transparent fs-5 text-light fw-normal">Pentadbir</a></div>
                @endcan
            </div>
        </div>
        <div class="col-md-7 m-0 d-flex align-items-center justify-content-center">
            <div class="w-100 d-flex align-items-center justify-content-start offset-1">
                @if(Storage::disk('local')->exists('public/img/system/logo-300.png'))
                    <a href="{{ route('dashboard') }}" class="ms-3">
                        <img style="width: 2.5em; height: 2.5em; margin: 1em;" src="{{ asset('storage/img/system/logo-300.png') }}" alt="Insitite Logo">
                    </a>
                @elseif(Storage::disk('local')->exists('public/img/system/logo-def-300.png'))
                    <a href="{{ route('dashboard') }}" class="ms-3">
                        <img style="width: 2.5em; height: 2.5em; margin: 0.5em;" src="{{ asset('storage/img/system/logo-def-300.png') }}" alt="Insitite Logo">
                    </a>
                @endif
                @isset($settings)
                    @empty($settings['institute_name'])
                        <a class="text-light text-center fw-bold fs-5 text-decoration-none" href="{{ route('dashboard') }}">Kolej Vokasional Malaysia</a>
                    @else
                        <a class="text-light text-center fw-bold fs-5 text-decoration-none" href="{{ route('dashboard') }}">{{ ucwords($settings['institute_name']) }}</a>
                    @endempty
                @else
                    <a class="text-light text-center fw-bold fs-5 text-decoration-none" href="{{ route('dashboard') }}">Kolej Vokasional Malaysia</a>
                @endisset
            </div>
        </div>
        <div class="col-md-2 m-0 invisible"></div>
        <div class="col-md-3 m-0 d-flex align-items-center justify-content-between">
          <div class="offset-sm-1 p-4 text-nowrap">
            @if(Storage::disk('local')->exists('public/img/profile/'. Auth::user()->username . '.png'))
                <a href="{{ route('profile') }}" class="">
                    <img style="width: 3em; height: 3em;" src="{{ asset('storage/img/profile/'. Auth::user()->username . '.png') }}" alt="User Profile Picture" class="img-fluid rounded-circle hvr-shrink" style="height: 3em">
                </a>
            @elseif(Storage::disk('local')->exists('public/img/profile/default/def-300.png'))
                <a href="{{ route('profile') }}" class="">
                    <img style="width: 3em; height: 3em; background-color: white;" src="{{ asset('storage/img/profile/default/def-300.png') }}" alt="Default Profile Picture" class="img-fluid rounded-circle hvr-grow hvr-shrink" style="height: 3em">
                </a>
            @endif
            <a href="{{ route('profile') }}" class="offset-sm-1 text-light fw-bold text-decoration-none hvr-grow">{{ strtoupper(Auth::user()->username) }}</a>
          </div>
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button type="submit" class="btn btn-secondary hvr-shrink"><i class="bi bi-box-arrow-in-left"></i>Log Keluar</button>
            </form>
        </div>
    </header>
    {{-- Mobile Navbar --}}
    <div class="d-flex flex-column d-lg-none invisible position-fixed top-0 start-0 bg-primary vh-100 w-100 m-0 justify-content-around align-items-center overflow-auto hamburger-menu-list" style="z-index: 80">
        <div class="col-md-3 d-flex justify-content-center align-content-center"><a href="{{ route('dashboard') }}" class="btn button-transparent fs-5 text-light fw-normal">Dashboard</a></div>
        <div class="col-md-3 d-flex justify-content-center align-content-center"><a href="{{ route('transcript') }}" class="btn button-transparent fs-5 text-light fw-normal">Transkrip</a></div>
        <div class="col-md-3 d-flex justify-content-center align-content-center"><a href="{{ route('classroom') }}" class="btn button-transparent fs-5 text-light fw-normal">Kelas</a></div>
        @can('authAdmin')
            <div class="col-md-3 d-flex justify-content-center align-content-center"><a href="{{ route('admin') }}" class="btn button-transparent fs-5 text-light fw-normal">Pentadbir</a></div>
        @endcan
    </div>
    {{-- mt-5 mt-sm-6 mt-md-4 mt-lg-5 --}}
    <div class="mt-1 mx-0 w-100 min-vh-100">
        @yield('content')
    </div>
    <footer class="footer mt-auto py-3 bg-dark">
        <!-- Modal -->
        <div class="modal fade" id="licenseModal" tabindex="-1" aria-labelledby="licenseModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="licenseModalLabel">Lesen dan Kredit</h5>
                        <i class="bi bi-x btn fs-2" data-bs-dismiss="modal" aria-label="Close"></i>
                    </div>
                    <div class="modal-body">
                        <p>Berikut merupakan senarai lesen-lesen yang berkait dengan projek ini.</p>
                        <p>Kekalkan semua lesen jika masih mahu meneruskan penggunaan projek ini.</p>
                        <div class="row border-top border-5">
                            <h6 class="fw-bold">Projek</h6>
                            <div class="row">
                                <div class="col-6">
                                    eKV
                                </div>
                                <div class="col-6">
                                    <p>Oleh: eKV Contributors</p>
                                    <p>Lesen: GPLv3</p>
                                </div>
                            </div>
                        </div>
                        <div class="row border-top border-5">
                            <h6 class="fw-bold">Library / Framework</h6>
                            <div class="row border-bottom border-3">
                                <div class="col-6">
                                    Laravel
                                </div>
                                <div class="col-6">
                                    <p>Oleh: Taylor Otwell</p>
                                    <p>Laman Sesawang: <a href="https://laravel.com/">https://laravel.com</a></p>
                                    <p>Lesen: <a href="https://opensource.org/licenses/MIT">MIT</a></p>
                                </div>
                            </div>
                            <div class="row border-bottom border-3">
                                <div class="col-6">
                                    Bootstrap
                                </div>
                                <div class="col-6">
                                    <p>Oleh: Twitter, Inc & The Bootstrap Authors</p>
                                    <p>Laman Sesawang: <a href="https://getbootstrap.com/">https://getbootstrap.com/</a></p>
                                    <p>Lesen: <a href="https://github.com/twbs/bootstrap/blob/main/LICENSE">MIT</a></p>
                                </div>
                            </div>
                            <div class="row border-bottom border-3">
                                <div class="col-6">
                                    Bootstrap Icons
                                </div>
                                <div class="col-6">
                                    <p>Oleh: The Bootstrap Authors</p>
                                    <p>Laman Sesawang: <a href="https://icons.getbootstrap.com/">https://icons.getbootstrap.com/</a></p>
                                    <p>Lesen: <a href="https://github.com/twbs/icons/blob/main/LICENSE.md">MIT</a></p>
                                </div>
                            </div>
                            <div class="row border-bottom border-3">
                                <div class="col-6">
                                    Invervention
                                </div>
                                <div class="col-6">
                                    <p>Oleh: Oliver Vogel</p>
                                    <p>Laman Sesawang: <a href="http://image.intervention.io">http://image.intervention.io</a></p>
                                    <p>Lesen: <a href="http://image.intervention.io/legal/license">MIT</a></p>
                                </div>
                            </div>
                            <div class="row border-bottom border-3">
                                <div class="col-6">
                                    Hover.css
                                </div>
                                <div class="col-6">
                                    <p>Oleh: Ian Lunn</p>
                                    <p>Laman Sesawang: <a href="http://ianlunn.github.io/Hover/">http://ianlunn.github.io/Hover/</a></p>
                                    <p>Lesen: <a href="https://store.ianlunn.co.uk/licenses/personal/">MIT</a></p>
                                </div>
                            </div>
                            <div class="row border-bottom border-3">
                                <div class="col-6">
                                    PhpSpreadsheet
                                </div>
                                <div class="col-6">
                                    <p>Oleh: PhpSpreadsheet Authors</p>
                                    <p>Laman Sesawang: <a href="https://github.com/PHPOffice/PhpSpreadsheet">https://github.com/PHPOffice/PhpSpreadsheet</a></p>
                                    <p>Lesen: <a href="https://github.com/PHPOffice/PhpSpreadsheet/blob/master/LICENSE">MIT</a></p>
                                </div>
                            </div>
                            <div class="row border-bottom border-3">
                                <div class="col-6">
                                    Laravel TCPDF
                                </div>
                                <div class="col-6">
                                    <p>Oleh: elibyy </p>
                                    <p>Laman Sesawang: <a href="https://github.com/elibyy/tcpdf-laravel">https://github.com/elibyy/tcpdf-laravel</a></p>
                                    <p>Lesen: <a href="https://github.com/elibyy/tcpdf-laravel/blob/master/LICENSE">MIT</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-center">
            <div class="mx-2"><a href="#" class="text-light text-center" data-bs-toggle="modal" data-bs-target="#licenseModal">Lesen dan Kredit</a></div>
            <div class="mx-2"><a href="https://ekv.readthedocs.io" class="text-light text-center" target="_blank">Dokumentasi</a></div>
            <div class="mx-2"><a href="https://github.com/hadiirfan/eKV" class="text-light text-center" target="_blank">Kod Sumber</a></div>
        </div>
        <x-copyright-notice colorScheme="light" />
    </footer>
    <script type="module" src="{{ asset('js/app.js') }}"></script>
    @bukScripts
</body>
</html>
