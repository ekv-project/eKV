@extends('dashboard.layout.main')
@section('content')
    <div class="container-fluid mt-1 w-100 h-100 d-flex flex-column align-items-center">
        <div class="row rounded-3 shadow-lg mt-5 w-100">
            <div class="col-6 my-3 text-start">
                <a href="{{ route('dashboard') }}" class="btn btn-primary"><i class="bi bi-arrow-return-left"></i>Dashboard</a>
            </div>
            <div class="col-6 my-3 text-end">
                <a href="{{ route('transcript.template') }}" class="btn btn-primary"><i class="bi bi-download"></i>Muat Turun Templat</a>
            </div>
        </div>
        <div class="row rounded-3 shadow-lg mt-2 mb-2 w-100">
            <h2 class="text-center my-3">Tambah Transkrip Semester Pukal</h2>
            <form action="" method="post" enctype="multipart/form-data" class="mt-4 mb-2">
                @if(session()->has('logoSuccess'))
                    <div class="alert alert-success">{{ session('logoSuccess') }}</div>
                @endif
                <p class="mt-3">
                    Borang ini digunakan untuk menambah

                </p>
                <input type="file" name="excel-upload" id="excel-upload" class="form-control mb-3">
                <div class="form-text mb-2 text-left">Logo Mestilah Bernisbah 1:1 <br> Resolusi Lebih Daripada 300x300 <span class="fst-italic">Pixel</span></div>
                @error('institute-logo')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                @error('unsupportedType')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <button type="submit" class="btn btn-primary w-100 hvr-shrink" name="logo">Kemas Kini</button>
            </form>
        </div>
    </div>
    <script src="{{ asset('js/addRemoveField.js') }}"></script>
@endsection