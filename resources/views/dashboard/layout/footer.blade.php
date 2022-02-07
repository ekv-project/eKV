<footer class="footer mt-5 py-3 bg-dark">
    {{-- Modal --}}
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
{{-- First time login modal --}}
@if(session()->has('firstTimeLogin'))
    @if(session()->get('firstTimeLogin') == 'yes')
        <div class="modal modal-dialog-centered text-dark fade" id="first-time-login" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="first-time-login-label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="first-time-login-label">Selamat Datang Ke Sistem eKV</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Oleh kerana ini pertama kali anda log masuk, anda disarankan untuk menukar kata laluan dan mengemaskini profil pengguna anda.</p>
                        <p>Halaman Kemas Kini Profil Pengguna: <a href="{{ route('profile.update', ['username' => Auth::user()->username]) }}" class="text-dark" target="_blank">Klik Sini</a></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Faham!</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif

<script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script type="module" src="{{ asset('js/app.js') }}"></script>
@bukScripts(true)

{{-- Show first time login modal --}}
<script>
    $(document).ready(function(){
        $("#first-time-login").modal('show');
        console.log('test');
    });
</script>

