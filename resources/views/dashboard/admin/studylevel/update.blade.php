@extends('dashboard.layout.main')
@section('content')
    <div class="w-100 h-100">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-12 col-lg-10">
                <form action="" method="post" class="mt-3 mb-5">
                    @csrf
                    <h2 class="text-center">Kemas Kini Tahap Pengajian</h2>
                    @if(session()->has('studyLevelUpdateSuccess'))
                        <div class="alert alert-success">{{ session('studyLevelUpdateSuccess') }}</div>
                    @endif
                    @error('notExisted')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <div class="mb-3">
                        <p class="fs-5">Kod Tahap Pengajian: <span>{{ strtoupper($studyLevel->code) }}</span></p>
                        <input type="hidden" name="study_level_code" value="{{ $studyLevel->code }}">
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="study_level_name" id="study_level_name" class="form-control" placeholder="study_level_name" value="@php if(old('study_level_name') !== null){echo old('study_level_name');}elseif(isset($studyLevel->name)){echo ucwords($studyLevel->name);}else{echo NULL;} @endphp">
                        <label for="study_level_name" class="form-label">Nama Tahap Pengajian</label>
                        @error('study_level_name')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="total_semester" id="total_semester" class="form-control" placeholder="total_semester" value="@php if(old('total_semester') !== null){echo old('total_semester');}elseif(isset($studyLevel->total_semester)){echo ucwords($studyLevel->total_semester);}else{echo NULL;} @endphp">
                        <label for="total_semester" class="form-label">Jumlah Semester</label>
                        @error('total_semester')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100 hvr-shrink" name="info">Kemas Kini</button>
                </form>
            </div>
        </div>
    </div>
@endsection