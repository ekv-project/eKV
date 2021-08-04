@extends('dashboard.layout.main')
@section('content')
    <div class="container-fluid mt-1 w-100 h-100 d-flex flex-column align-items-center">
        <div class="row rounded-3 shadow-lg mt-5 w-100">
            <div class="col-6 my-3 text-start">
                <a href="{{ route('dashboard') }}" class="btn btn-primary"><i class="bi bi-arrow-return-left"></i>Dashboard</a>
            </div>
            <div class="col-6 my-3 text-end">
                {{-- Only the profile owner, coordinator and admin can view --}}
                {{-- @if(Gate::allows('authAdmin') || Gate::allows('authCoordinator', $username) || Gate::allows('authUser', $username))
                    <a href="{{ route('transcript.student', ['studentID' => $username]) }}" class="btn btn-primary"><i class="bi bi-eye"></i>Transkrip Peperiksaan</a>
                    <a href="{{ route('profile.download', ['username' => $username]) }}" class="btn btn-primary"><i class="bi bi-download"></i>Muat Turun</a>
                    <a href="{{ route('profile.update', ['username' => $username]) }}" class="btn btn-primary"><i class="bi bi-pencil-square"></i>Kemas Kini</a>
                @endif --}}
            </div>
        </div>
        <div class="row rounded-3 shadow-lg mt-2 mb-5 w-100 d-flex flex-column justify-content-center align-items-center">
            <h1 class="text-center fs-2 my-2">Pengumuman</h1>
            <div class="row my-3">
                <span class="fw-bold"> Tajuk: <span class="fw-normal">{{ strtoupper($announcementPost->title) }}</span></span>
            </div>
            <div class="row my-3">
                <span class="fw-bold">Tarikh dan Waktu: <x-buk-carbon :date="$announcementPost->created_at" format="d F Y, h:i A" class="fw-normal"/></span>
            </div> 
            <div class="row my-3">
                <span class="fw-bold">Penulis: <span class="fw-normal">{{ strtoupper($announcementPost->fullname) }}</span></span>
            </div>
            <div class="row my-3">
                <p class="fw-bold">Kandugan:</p>
                <div class="border border-primary border-1">
                    <div class="my-2">@parsedown($announcementPost->content)</div>
                </div>
            </div>
        </div>
    </div>
@endsection
