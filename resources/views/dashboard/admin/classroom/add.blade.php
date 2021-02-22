@extends('dashboard.layout.admin')
@section('content')
    <div class="w-100 h-100 mt-3">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-12 col-lg-10">
                <form action="" method="post" class="mt-3 mb-5">
                    @csrf
                    <h2 class="text-left">Tambah Kelas</h2>
                    @if(session()->has('classroomAddSuccess'))
                        <div class="alert alert-success">
                            <p>{{ session('classroomAddSuccess') }}</p>
                            <p><span class="fw-bold">ID Kelas: </span><a href="{{ route('classroom.view', [session('classroomID')]) }}" target="_blank" class="text-decoration-none text-dark fst-italic hvr-underline-reveal">{{ session('classroomID') }}</a></p>
                        </div>
                    @endif
                    @error('programNotExisted')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    @error('studyLevelNotExisted')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <div class="form-floating mb-3">
                        <input type="text" name="name" id="name" class="form-control" placeholder="name" value="@php if(old('name') !== null){echo old('name');}else{echo NULL;} @endphp">
                        <label for="name" class="form-label">Nama Kelas</label>
                        @error('name')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="programs_code" id="programs_code" class="form-control" placeholder="programs_code" value="@php if(old('programs_code') !== null){echo old('programs_code');}else{echo NULL;} @endphp">
                        <label for="programs_code" class="form-label">Kod Program</label>
                        @error('programs_code')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="admission_year" id="admission_year" class="form-control" placeholder="admission_year" value="@php if(old('admission_year') !== null){echo old('admission_year');}else{echo NULL;} @endphp">
                        <label for="admission_year" class="form-label">Tahun Kemasukan</label>
                        <div class="form-text">
                            Format: XXXX
                        </div>
                        @error('admission_year')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="study_year" id="study_year" class="form-control" placeholder="study_year" value="@php if(old('study_year') !== null){echo old('study_year');}else{echo NULL;} @endphp">
                        <label for="study_year" class="form-label">Tahun Pengajian Terkini</label>
                        <div class="form-text">
                            Format: XXXX
                        </div>
                        @error('study_year')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="study_levels_code" id="study_levels_code" class="form-control" placeholder="study_levels_code" value="@php if(old('study_levels_code') !== null){echo old('study_levels_code');}else{echo NULL;} @endphp">
                        <label for="study_levels_code" class="form-label">Kod Tahap Pengajian</label>
                        @error('study_levels_code')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100 hvr-shrink" name="info">Tambah</button>
                </form>
            </div>
        </div>
    </div>
@endsection