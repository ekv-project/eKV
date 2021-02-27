@extends('dashboard.layout.main')
@section('content')
    <div class="container-fluid mt-1 w-100 h-100 d-flex flex-column align-items-center">
        <div class="row rounded-3 shadow-lg mt-5 w-100">
            <div class="col-6 my-3 text-start">
                <a href="{{ route('classroom.view', [$classroomData['id']]) }}" class="btn btn-primary"><i class="bi bi-arrow-return-left"></i>Kelas</a>
            </div>
            <div class="col-6 my-3">
            </div>
        </div>
        <div class="row rounded-3 shadow-lg mt-2 mb-2 d-flex flex-column align-items-center justify-content-center w-100">
            <div class="row my-3 d-flex flex-column align-items-center justify-content-center w-100">
                <form action="" method="post">
                    @csrf
                    <h2 class="text-center">KEMAS KINI MAKLUMAT KELAS</h2>
                    <h2 class="text-left mt-md-4 fs-5 mb-4 fw-bold">ID Kelas: <span class="fw-normal">{{ $classroomData['id'] }}</span></h2>
                    @if(session()->has('classroomUpdateSuccess'))
                        <div class="alert alert-success">{{ session('classroomUpdateSuccess') }}</div>
                    @endif
                    <div class="form-floating mb-3">
                        <input type="text" name="name" id="name" class="form-control" placeholder="name" value="@php if(old('name') !== null){echo old('name');}elseif(isset($classroomData['name'])){echo $classroomData['name'];}else{echo NULL;} @endphp">
                        <label for="name" class="form-label">Nama Kelas</label>
                        @error('name')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="programs_code" id="programs_code" class="form-control" placeholder="program" value="@php if(old('programs_code') !== null){echo old('programs_code');}elseif(isset($classroomData['programs_code'])){echo $classroomData['programs_code'];}else{echo NULL;} @endphp">
                        <label for="programs_code" class="form-label">Kod Program</label>
                        @error('programs_code')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        @error('noProgram')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="admission_year" id="admission_year" class="form-control" placeholder="year" value="@php if(old('admission_year') !== null){echo old('admission_year');}elseif(isset($classroomData['admission_year'])){echo $classroomData['admission_year'];}else{echo NULL;} @endphp">
                        <label for="admission_year" class="form-label">Tahun Kemasukan</label>
                        <div class="form-text">
                            Format: XXXX
                        </div>
                        @error('admission_year')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="study_year" id="study_year" class="form-control" placeholder="year" value="@php if(old('study_year') !== null){echo old('study_year');}elseif(isset($classroomData['study_year'])){echo $classroomData['study_year'];}else{echo NULL;} @endphp">
                        <label for="study_year" class="form-label">Tahun Pengajian Terkini</label>
                        <div class="form-text">
                            Format: XXXX
                        </div>
                        @error('study_year')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="study_levels_code" id="study_levels_code" class="form-control" placeholder="code" value="@php if(old('study_levels_code') !== null){echo old('study_levels_code');}elseif(isset($classroomData['study_levels_code'])){echo $classroomData['study_levels_code'];}else{echo NULL;} @endphp">
                        <label for="study_levels_code" class="form-label">Kod Tahap Pengajian</label>
                        @error('study_levels_code')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        @error('noStudyLevel')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100 hvr-shrink">Kemas Kini</button>
                </form>
            </div>
        </div>
    </div>
@endsection