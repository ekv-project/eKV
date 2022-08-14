@extends('dashboard.layout.main')
@section('content')
    <div class="container-fluid mt-1 w-100 h-100 d-flex flex-column align-items-center">
        <div class="row rounded-3 shadow-lg mt-5 w-100 bg-light">
            <div class="col-6 my-3 text-start">
                <a href="{{ route('dashboard') }}" class="btn btn-primary"><i class="bi bi-arrow-return-left"></i>Dashboard</a>
            </div>
            <div class="col-6 my-3 text-end">
            </div>
        </div>
        <div class="row rounded-3 shadow-lg mt-2 mb-5 w-100 d-flex flex-column justify-content-center align-items-center bg-light">
            <h1 class="text-center fs-2 my-2">Pengumuman</h1>
            <div class="row my-3">
                <span class="fw-bold"> Tajuk: <span class="fw-normal">{{ strtoupper($announcementPost->title) }}</span></span>
            </div>
            <div class="row my-3">
                <span class="fw-bold">Tarikh dan Waktu: <x-buk-carbon :date="$announcementPost->created_at" format="d/m/Y, h:i A" class="fw-normal"/></span>
            </div>
            <div class="row my-3">
                <span class="fw-bold">Penulis: <span class="fw-normal">{{ strtoupper($announcementPost->username) }}</span></span>
            </div>
            <div class="row my-3">
                <p class="fw-bold">Kandungan:</p>
                <div class="border border-primary border-1">
                    <div class="my-2">@parsedown($announcementPost->content)</div>
                </div>
            </div>
        </div>
    </div>
@endsection
