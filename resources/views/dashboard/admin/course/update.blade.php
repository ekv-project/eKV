@extends('dashboard.layout.main')
@section('content')
<div class="container-fluid d-flex flex-column align-items-center justify-content-center m-0 p-0">
    <div class="row w-100 d-flex flex-column align-items-center justify-content-center">
        <div class="col-5">
            <form action="" method="post" class="mt-3 mb-5">
                @csrf
                <h2 class="text-center">Kemas Kini Kursus</h2>
                @if(session()->has('courseUpdateSuccess'))
                    <div class="alert alert-success">{{ session('courseUpdateSuccess') }}</div>
                @endif
                @error('notExisted')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <div class="mb-3">
                    <p class="fs-5">Kod Kursus: <span>{{ strtoupper($course->code) }}</span></p>
                    <input type="hidden" name="course_code" value="{{ $course->code }}">
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="course_name" id="course_name" class="form-control" placeholder="course_name" value="@php if(old('course_name') !== null){echo old('course_name');}elseif(isset($course->name)){echo ucwords($course->name);}else{echo NULL;} @endphp">
                    <label for="course_name" class="form-label">Nama Kursus</label>
                    @error('course_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary w-100 hvr-shrink" name="info">Kemas Kini</button>
            </form>
        </div>
    </div>
</div>
@endsection