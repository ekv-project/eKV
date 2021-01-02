@extends('dashboard.layout.main')
@section('content')
    <div class="container-fluid d-flex align-items-center justify-content-center m-0 p-0">
        <div class="d-flex align-items-center">
            <form action="" method="post">
                @csrf
                <h2>Kemas Kini Maklumat Kelas</h2>
                @if(session()->has('classroomUpdateSuccess'))
                    <div class="alert alert-success">{{ session('classroomUpdateSuccess') }}</div>
                @endif
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
                    @error('admission_year')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="study_levels_code" id="study_levels_code" class="form-control" placeholder="code" value="@php if(old('study_levels_code') !== null){echo old('study_levels_code');}elseif(isset($classroomData['study_levels_code'])){echo $classroomData['study_levels_code'];}else{echo NULL;} @endphp">
                    <label for="study_levels_code" class="form-label">Kod Peringkat Pengajian</label>
                    @error('study_levels_code')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    @error('noStudyLevel')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="study_year" id="study_year" class="form-control" placeholder="year" value="@php if(old('study_year') !== null){echo old('study_year');}elseif(isset($classroomData['study_year'])){echo $classroomData['study_year'];}else{echo NULL;} @endphp">
                    <label for="study_year" class="form-label">Tahun Pengajian Semasa</label>
                    @error('study_year')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary w-100 hvr-shrink">Kemas Kini</button>
            </form>
        </div>
    </div>
@endsection