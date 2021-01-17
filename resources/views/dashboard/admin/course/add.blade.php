@extends('dashboard.layout.main')
@section('content')
<div class="container-fluid d-flex flex-column align-items-center justify-content-center m-0 p-0">
    <div class="row w-100 d-flex flex-column align-items-center justify-content-center">
        <div class="col-5">
            <form action="" method="post" class="mt-3 mb-5">
                @csrf
                <h2>Tambah Kursus</h2>
                @if(session()->has('courseAddSuccess'))
                    <div class="alert alert-success">{{ session('courseAddSuccess') }}</div>
                @endif
                @error('existed')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <div class="form-floating mb-3">
                    <input type="text" name="course_code" id="course_code" class="form-control" placeholder="course_code" value="@php if(old('course_code') !== null){echo old('course_code');}elseif(isset($settings['course_code'])){echo ucwords($settings['course_code']);}else{echo NULL;} @endphp">
                    <label for="course_code" class="form-label">Kod Kursus</label>
                    @error('course_code')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="course_name" id="course_name" class="form-control" placeholder="course_name" value="@php if(old('course_name') !== null){echo old('course_name');}elseif(isset($settings['course_name'])){echo ucwords($settings['course_name']);}else{echo NULL;} @endphp">
                    <label for="course_name" class="form-label">Nama Kursus</label>
                    @error('course_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary w-100 hvr-shrink" name="info">Tambah</button>
            </form>
        </div>
    </div>
</div>
@endsection