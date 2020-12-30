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
                    <input type="text" name="programs_code" id="programs_code" class="form-control" placeholder="XXXX" value="{{ old('programs_code') }}">
                    <label for="programs_code" class="form-label">Kod Program</label>
                    @error('programs_code')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    @error('noProgram')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="admission_year" id="admission_year" class="form-control" placeholder="XXXX" value="{{ old('admission_year') }}">
                    <label for="admission_year" class="form-label">Tahun Kemasukan</label>
                    @error('admission_year')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="study_levels_code" id="study_levels_code" class="form-control" placeholder="XXXX" value="{{ old('study_levels_code') }}">
                    <label for="study_levels_code" class="form-label">Kod Peringkat Pengajian</label>
                    @error('study_levels_code')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    @error('noStudyLevel')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="study_year" id="study_year" class="form-control" placeholder="XXXX" value="{{ old('study_year') }}">
                    <label for="study_year" class="form-label">Tahun Pengajian Semasa</label>
                    @error('study_year')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary w-100">Kemas Kini</button>
            </form>
        </div>
    </div>
@endsection