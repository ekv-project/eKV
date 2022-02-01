@extends('dashboard.layout.admin')
@section('content')
    <div class="w-100 h-100 mt-3">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-12 col-lg-10">
                <form action="" method="post" class="mt-3 mb-5">
                    @csrf
                    <h2>Tambah Set Kursus</h2>
                    @if(session()->has('courseSetAddSuccess'))
                        <div class="alert alert-success">{{ session('courseSetAddSuccess') }}</div>
                    @endif
                    @error('courses_empty')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    @if(session()->has('courseErr'))
                        @if(count(session('courseErr')) > 0)
                            <div class="my-3 alert alert-danger">
                                <p class="fw-bold">Ralat ({{ count(session('courseErr')) }}):</p>
                                <button class="btn btn-outline-danger" type="button" data-bs-toggle="collapse" data-bs-target="#errorCollapse" aria-expanded="false" aria-controls="errorCollapse"><i class="bi bi-arrows-expand"></i> Ralat Kursus</button>
                                <div class="collapse mt-3" id="errorCollapse">
                                    <div class="card card-body">
                                        @for ($i = 0; $i < count(session('courseErr')); $i++)
                                            <p class="mt-1">{{ $i + 1 . ": " }}{{ session('courseErr')[$i] }}</p>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                    @error('existed')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <div class="row w-100">
                        <div class="col-lg-4 col-md-12">
                            <div class="form-floating mb-3">
                                <select name="study_level" id="study_level" class="form-select">
                                    @foreach ($studyLevels as $sl)
                                        <option value="{{ $sl->code }}">{{ strtoupper($sl->name) }}</option>
                                    @endforeach
                                </select>
                                <label for="study_level" class="form-label">Tahap Pengajian</label>
                                @error('study_level')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12">
                            <div class="form-floating mb-3">
                                <select name="semester" id="semester" class="form-select">
                                    @for ($i = 1; $i < $maxSemester + 1; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                                <label for="semester" class="form-label">Semester</label>
                                @error('semester')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12">
                            <div class="form-floating mb-3">
                                <input type="text" name="program" id="program" class="form-control" placeholder="program" value="@php if(old('program') !== null){echo old('program');}else{echo NULL;} @endphp">
                                <label for="program" class="form-label">Kod Program</label>
                                @error('program')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <h2 class="fs-5">Senarai Kursus</h2>
                    <p class="fst-italic">Maksimum 12 kursus.</p>
                    <button class="btn btn-success hvr-shrink" type="button" id="course-set-course-add"><i class="bi bi-plus"></i></button>
                    <button class="btn btn-danger hvr-shrink" type="button" id="course-set-course-remove"><i class="bi bi-dash"></i></button>
                    <div class="mt-3 row w-100" id="course-list">
                        @if(!empty(old('course_code')))
                            @for ($i = 0; $i < count(old('course_code')); $i++)
                                @if(!empty(old('course_code')[$i]))
                                    <div class="col-6 col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input type="text" name="course_code[]" id="course_code[]" class="form-control" placeholder="course_code[]" value="{{ old('course_code')[$i] }}">
                                            <label for="course_code[]" class="form-label">Kod Kursus</label>
                                        </div>
                                    </div>
                                @endif
                            @endfor
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary w-100 hvr-shrink mt-5">Tambah</button>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/addRemoveCourseSet.js') }}"></script>
@endsection
