@extends('dashboard.layout.main')
@section('content')
    <div class="container-fluid mt-1 w-100 h-100 d-flex flex-column align-items-center">
        <div class="row rounded-3 shadow-lg mt-5 w-100 d-flex flex-column align-items-center justify-content-center">
            <h1 class="fs-2 text-center my-3">Navigasi</h1>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 mt-4 mb-5">
                <div class="col my-3 text-center align-middle">
                    <a href="{{ route('transcript') }}" class="btn btn-primary btn-lg hvr-shrink">
                        <i class="bi bi-file-text"></i>
                        <span>Transkrip</span>
                    </a>
                </div>
                <div class="col my-3 text-center align-middle">
                    <a href="{{ route('classroom') }}" class="btn btn-primary btn-lg hvr-shrink">
                        <i class="bi bi-book"></i>
                        <span>Kelas</span>
                    </a>
                </div>
                <div class="col my-3 text-center align-middle">
                    <a href="" class="btn btn-primary btn-lg hvr-shrink">
                        <i class="bi bi-person-check"></i>
                        <span>Kehadiran</span>
                    </a>
                </div>
            </div>
        </div>
        {{-- Hide for now until the feature is implemented --}}
        @if(1 == 0)
            <div class="row rounded-3 shadow-lg mt-5 w-100 mx-1">
                <h1 class="fs-2 text-center">Pengumuman</h1>
            </div>
        @endif
    </div>
@endsection