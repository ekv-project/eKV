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
        <div class="row rounded-3 shadow-lg my-3 w-100 d-flex flex-column align-items-center justify-content-center">
            <div class="row">
                <h1 class="fs-2 text-center my-3">Pengumuman</h1>
            </div>
            @if($announcementPosts->count() > 0)
                <div class="row row-cols-1 row-cols-lg-2 g-2 mb-3">
                    @foreach ($announcementPosts as $post)
                        <div class="col">
                            <div class="border border-1 border-primary rounded-1">
                                <div class="m-3">
                                    <div class="row"><p class="fs-5">{{ strtoupper($post->title) }}</p></div>
                                    <div class="row"><span class="fw-bold">Tarikh dan Waktu: </span><x-buk-carbon :date="$post->created_at" format="d/m/Y, h:i A"/></div>
                                    <div class="row"><span class="fw-bold">Penulis: </span><span>{{ strtoupper($post->fullname) }}</span></div>
                                    <div class="row mt-3"><span><a href="{{ route('announcement.view', ['id' => $post->id]) }}" class="btn btn-outline-primary btn-sm col hvr-shrink"><i class="bi bi-arrow-right-circle"></i> Baca Lanjut</a></span></div>
                                </div>
                            </div>
                        </div> 
                    @endforeach
                </div>
                <div class="d-flex align-items-center justify-content-center w-100">
                    {{ $announcementPosts->links() }}
                </div>
            @else
                <div class="row">
                    <p class="fst-italic fw-bold text-center my-4">Tiada pengumuman diterbitkan!</p>
                </div>
            @endif
        </div>
    </div>
@endsection