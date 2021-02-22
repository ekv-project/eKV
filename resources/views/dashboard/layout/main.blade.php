<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @if(isset(Auth::user()->api_token) AND Auth::user()->api_token != NULL)
        <meta name="api-token" content="{{ Auth::user()->api_token }}">
    @endif
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstraps-icons/font/bootstrap-icons.css') }}">
    @if(Storage::disk('local')->exists('public/img/system/logo-16.png'))
        <link rel="shortcut icon" href="{{ asset('public/img/system/logo-16.png') }}" type="image/png">
    @elseif(Storage::disk('local')->exists('public/img/system/logo-def-16.jpg'))
        <link rel="shortcut icon" href="{{ asset('public/img/system/logo-def-16.jpg') }}" type="image/jpeg">
    @endif
    <title>{{ $page }} - {{ env('APP_NAME') }} | 
        @isset($settings)
            @empty($settings['institute_name'])
                Kolej Vokasional Malaysia
            @else
                {{ ucwords($settings['institute_name']) }}
            @endempty        
        @else   
            Kolej Vokasional Malaysia
        @endisset   
    </title>
</head>
<body>
    {{-- Hamburger Menu Button --}}
    <div class="d-flex d-lg-none d-md-flex position-fixed top-0 start-0 hamburger-menu" style="z-index: 100">
        <div class="hamburger-layer"></div>
        <div class="hamburger-layer"></div>
        <div class="hamburger-layer"></div>
    </div>
    <header class="container-fluid m-0 row d-flex align-items-center bg-primary position-fixed top-0 start-0" style="z-index: 80">
        {{-- Desktop Navbar --}}
        <div class="container-fluid d-flex align-items-center">
            <div class="container-fluid d-none d-lg-flex d-sm-none justify-content-around">
                <div class="col-md-3 d-flex justify-content-center align-content-center hvr-shrink"><a href="{{ route('dashboard') }}" class="btn button-transparent fs-5 text-light fw-normal">Dashboard</a></div>
                <div class="col-md-3 d-flex justify-content-center align-content-center hvr-shrink"><a href="{{ route('transcript.student', ['studentID' => Auth::user()->username]) }}" class="btn button-transparent fs-5 text-light fw-normal">Transkrip</a></div>
                <div class="col-md-3 d-flex justify-content-center align-content-center hvr-shrink"><a href="" class="btn button-transparent fs-5 text-light fw-normal">Pilihan Raya</a></div>
                <div class="col-md-3 d-flex justify-content-center align-content-center hvr-shrink"><a href="{{ route('admin') }}" class="btn button-transparent fs-5 text-light fw-normal">Pentadbir</a></div>
            </div>
        </div>
        <div class="col-md-5 m-0 d-flex align-items-center justify-content-center">
            <div class="w-100 d-flex align-items-center">
                @if(Storage::disk('local')->exists('public/img/system/logo-300.png'))
                    <a href="{{ route('dashboard') }}" class="ms-3">
                        <img style="width: 2.5em; height: 2.5em; margin: 1em;" src="{{ asset('public/img/system/logo-300.png') }}" alt="Insitite Logo">
                    </a>
                @elseif(Storage::disk('local')->exists('public/img/system/logo-def-300.jpg'))
                    <a href="{{ route('dashboard') }}" class="ms-3">
                        <img style="width: 2.5em; height: 2.5em; margin: 0.5em;" src="{{ asset('public/img/system/logo-def-300.jpg') }}" alt="Insitite Logo">
                    </a>
                @endif
                @isset($settings)
                    @empty($settings['institute_name'])
                        <a class="text-light text-center fw-bold fs-4 text-decoration-none" href="{{ route('dashboard') }}">Kolej Vokasional Malaysia</a>
                    @else
                        <a class="text-light text-center fw-bold fs-4 text-decoration-none" href="{{ route('dashboard') }}">{{ ucwords($settings['institute_name']) }}</a>
                    @endempty        
                @else
                    <a class="text-light text-center fw-bold fs-4 text-decoration-none" href="{{ route('dashboard') }}">Kolej Vokasional Malaysia</a>
                @endisset  
            </div>
        </div>
        <div class="col-md-4 m-0 invisible"></div>
        <div class="col-md-3 m-0 d-flex align-items-center justify-content-around">
            @if(Storage::disk('local')->exists('public/img/profile/'. Auth::user()->username . '.jpg'))
                <a href="{{ route('profile') }}" class="">
                    <img style="width: 3em; height: 3em;" src="{{ asset('public/img/profile/'. Auth::user()->username . '.jpg') }}" alt="User Profile Picture" class="img-fluid rounded-circle hvr-shrink" style="height: 3em">
                </a>
            @elseif(Storage::disk('local')->exists('public/img/profile/default/def-300.jpg'))
                <a href="{{ route('profile') }}" class="">
                    <img style="width: 3em; height: 3em;" src="{{ asset('public/img/profile/default/def-300.jpg') }}" alt="Default Profile Picture" class="img-fluid rounded-circle hvr-grow hvr-shrink" style="height: 3em">
                </a>
            @endif
            <a href="{{ route('profile') }}" class="text-light fw-bold text-decoration-none hvr-grow">{{ Auth::user()->username }}</a>
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button type="submit" class="btn btn-secondary hvr-shrink"><i class="bi bi-box-arrow-in-left"></i>Log Keluar</button>
            </form>
        </div>
    </header>
    {{-- Mobile Navbar --}}
    <div class="d-flex flex-column d-lg-none invisible position-fixed top-0 start-0 bg-primary h-100 w-100 m-0 justify-content-center align-items-center overflow-auto hamburger-menu-list" style="z-index: 80">
        <div class="d-flex flex-column justify-content-around align-items-center w-100 h-100">
            <div class="col-md-5 d-flex justify-content-center align-content-center hvr-shrink"><a href="{{ route('dashboard') }}" class="btn button-transparent fs-5 text-light fw-normal">Dashboard</a></div>
            <div class="col-md-5 d-flex justify-content-center align-content-center hvr-shrink"><a href="{{ route('transcript.student', ['studentID' => Auth::user()->username]) }}" class="btn button-transparent fs-5 text-light fw-normal">Transkrip</a></div>
            <div class="col-md-5 d-flex justify-content-center align-content-center hvr-shrink"><a href="" class="btn button-transparent fs-5 text-light fw-normal">Pilihan Raya</a></div>
            <div class="col-md-5 d-flex justify-content-center align-content-center hvr-shrink"><a href="{{ route('admin') }}" class="btn button-transparent fs-5 text-light fw-normal">Pentadbir</a></div>
        </div>
    </div>
    <div class="container-fluid mt-6 mt-sm-6 mt-md-4 mt-lg-5 border-top border-5 w-100 min-vh-100">
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
                                    <p>Oleh: Muhammad Hanis Irfan bin Mohd Zaid & Muhammad Firdaus bin Nazri.</p>
                                    <p>Lesen: Belum ditentukan.</p>
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
                                    mPDF
                                </div>
                                <div class="col-6">
                                    <p>Oleh: Ian Back</p>
                                    <p>Laman Sesawang: <a href="https://mpdf.github.io/">https://mpdf.github.io/</a></p>
                                    <p>Lesen: <a href="https://mpdf.github.io/about-mpdf/license.html">GPL 2.0</a></p>
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
            <div class="mx-2"><a href="" class="text-light" data-bs-toggle="modal" data-bs-target="#licenseModal">Lesen dan Kredit</a></div>
            <div class="mx-2"><a href="#" class="text-light">Laman Sesawang</a></div>
            <div class="mx-2"><a href="#" class="text-light">Dokumentasi</a></div>
        </div>
        <p class="text-center text-secondary p-3 text-light">Hak Cipta Terpelihara &copy; Muhammad Hanis Irfan bin Mohd Zaid & Muhammad Firdaus bin Nazri 2020</p>
    </footer>
    <script type="module" src="{{ asset('js/app.js') }}"></script>
</body>
</html>