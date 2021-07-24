@extends('dashboard.layout.admin')
@section('content')
<div class="row w-100 d-flex justify-content-center">
    <div class="col-12 col-lg-11 d-flex flex-column justify-content-center">
        <h1 class="text-center fs-2 my-2">Maklumat Kolej</h1>
        <div class="row d-flex justify-content-center">
            @if(Storage::disk('local')->exists('public/img/system/logo-300.png'))
                <img src="{{ asset('storage/img/system/logo-300.png') }}" alt="College Logo" class="mt-1 mb-1 border-2" style="height: 9em; width: 10em;">
            @elseif(Storage::disk('local')->exists('public/img/system/logo-def-300.png'))
                <img src="{{ asset('storage/img/system/logo-def-300.png') }}" alt="College Logo" class="mt-1 mb-1 border-2" style="height: 9em; width: 10em;">
            @endif 
        </div>
        @if(isset($settings))
            <div class="row row-cols-1 row-cols-lg-2">
                <p class="col fw-bold my-4"><i class="bi bi-person-lines-fill"></i>NAMA: <span class="fw-normal">{{ strtoupper($settings['institute_name']) }}</span></p>
                <p class="col fw-bold my-4"><i class="bi bi-building"></i>ALAMAT: <span class="fw-normal">{{ strtoupper($settings['institute_address']) }}</span></p>
                <p class="col fw-bold my-4"><i class="bi bi-envelope-fill"></i>ALAMAT E-MEL: <span class="fw-normal">{{ strtoupper($settings['institute_email_address']) }}</span></p>
                <p class="col fw-bold my-4"><i class="bi bi-telephone-fill"></i></i>NO. TELEFON: <span class="fw-normal">{{ $settings['institute_phone_number'] }}</span></p>
                <p class="col fw-bold my-4"><i class="bi bi-printer-fill"></i>NO. FAX: <span class="fw-normal">{{ $settings['institute_fax'] }}</span></p>
            </div>
        @else
            <div class="row row-cols-1 row-cols-lg-2">
                <p class="col fw-bold my-4"><i class="bi bi-person-lines-fill"></i>NAMA: <span class="fw-normal">N/A</span></p>
                <p class="col fw-bold my-4"><i class="bi bi-building"></i>ALAMAT: <span class="fw-normal">N/A</span></p>
                <p class="col fw-bold my-4"><i class="bi bi-envelope-fill"></i>ALAMAT E-MEL: <span class="fw-normal">N/A</span></p>
                <p class="col fw-bold my-4"><i class="bi bi-telephone-fill"></i></i>NO. TELEFON: <span class="fw-normal">N/A</span></p>
                <p class="col fw-bold my-4"><i class="bi bi-printer-fill"></i>NO. FAX: <span class="fw-normal">N/A</span></p>
            </div>  
        @endif
    </div>
</div>
@endsection
