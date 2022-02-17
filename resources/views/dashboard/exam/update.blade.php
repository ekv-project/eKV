@extends('dashboard.layout.main')
@section('content')
    <div class="container-fluid mt-1 w-100 h-100 d-flex flex-column align-items-center">
        <div class="row rounded-3 shadow-lg mt-5 w-100 bg-light">
            <div class="col-6 my-3 text-start">
                <a href="{{ route('transcript.student', [$studentDetails['matrixNumber']]) }}" class="btn btn-primary"><i class="bi bi-arrow-return-left"></i>Senarai Transkrip</a>
            </div>
            <div class="col-6 my-3 text-end">
            </div>
        </div>
        <div class="row rounded-3 shadow-lg mt-2 mb-5 w-100 bg-light">
            <h1 class="fs-2 text-center my-3">Kemas Kini Transkrip Semester</h1>
            <div class="d-flex flex-column align-items-center justify-content-center">
                @if(session()->has('transcriptSuccess'))
                    <div class="alert alert-success w-75">{{ session('transcriptSuccess') }}</div>
                @endif
                @error('noCourseInserted')
                    <div class="alert alert-danger w-75">{{ $message }}</div>
                @enderror
            </div>
            <form action="" method="post" class="mt-3">
                @csrf
                <div class="row mt-3">
                    <div class="col">
                        <p class="fw-bold">Nama Pelajar: <span class="fw-normal">{{ strtoupper($studentDetails['name']) }}</span></p>
                    </div>
                    <div class="col">
                        <p class="fw-bold">No Kad Pengenalan: <span class="fw-normal">{{ $studentDetails['nric'] }}</span></p>
                    </div>
                    <div class="col">
                        <p class="fw-bold">ID Pelajar: <span class="fw-normal">{{ strtoupper($studentDetails['matrixNumber']) }}</span></p>
                    </div>
                </div>
                <div class="row mt-2 mb-2">
                    <div class="col">
                        <p class="fw-bold">Tahap Pengajian: <span class="fw-normal">{{ ucwords($studyLevel->name) }}</span></p>
                        <input type="hidden" name="studyLevel" value="{{ $studyLevel->code }}">
                        </div>
                    <div class="col">
                        <p class="fw-bold">Semester: <span class="fw-normal">{{ $semester }}</span></p>
                        <input type="hidden" name="semester" value="{{ $semester }}">
                    </div>
                    <div class="col"></div>
                </div>
                <div class="row">
                    <h1 class="fw-bold fs-6">Purata Nilai Gred</h1>
                    <div class="col">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="gpa" name="gpa" placeholder="gpa" value="@php if(old('gpa') !== null){echo old('gpa');}elseif(isset($semesterGrade->gpa)){echo ucwords($semesterGrade->gpa);}else{echo NULL;} @endphp" required>
                            <label for="gpa">Purata Nilai Gred Semasa</label>
                        </div>
                        @error('gpa')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="total_credit_gpa" name="total_credit_gpa" placeholder="total_credit_gpa" value="@php if(old('total_credit_gpa') !== null){echo old('total_credit_gpa');}elseif(isset($semesterGrade->total_credit_gpa)){echo ucwords($semesterGrade->total_credit_gpa);}else{echo NULL;} @endphp" required>
                            <label for="total_credit_gpa">Jumlah Jam Kredit</label>
                        </div>
                        @error('total_credit_gpa')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="cgpa" name="cgpa" placeholder="cgpa" value="@php if(old('cgpa') !== null){echo old('cgpa');}elseif(isset($semesterGrade->cgpa)){echo ucwords($semesterGrade->cgpa);}else{echo NULL;} @endphp" required>
                            <label for="cgpa">Purata Nilai Gred Kumulatif</label>
                        </div>
                        @error('cgpa')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="total_credit_cgpa" name="total_credit_cgpa" placeholder="total_credit_cgpa" value="@php if(old('total_credit_cgpa') !== null){echo old('total_credit_cgpa');}elseif(isset($semesterGrade->total_credit_cgpa)){echo ucwords($semesterGrade->total_credit_cgpa);}else{echo NULL;} @endphp" required>
                            <label for="total_credit_cgpa">Jumlah Jam Kredit</label>
                        </div>
                        @error('total_credit_cgpa')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row d-flex flex-column justify-content-center align-items-center">
                    <div class="row mb-1">
                        <div class="col d-flex justify-content-around align-items-center">
                            <h1 class="fw-bold fs-6">Gred Kursus</h1>
                            <button type="button" id="addField" data-field-type="courseGrade" class="btn btn-primary hvr-shrink">Tambah</button>
                        </div>
                        <div class="col"></div>
                    </div>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3" id="courseGrade">
                        @foreach ($courseGrades as $courseGrade)
                        <div>
                            <div class="col border border-dark mt-1 d-flex flex-column justify-content-center align-items-center">
                                <div class="form-floating m-2">
                                    <input type="text" class="form-control" id="coursesCode" name="coursesCode[]" placeholder="coursesCode" value="{{ strtoupper($courseGrade->courses_code) }} "required>
                                    <label for="coursesCode">Kod Kursus</label>
                                </div>
                                <div class="form-floating m-2">
                                    <input type="text" class="form-control" id="gradePointer" name="gradePointer[]" placeholder="gradePointer" value="{{ $courseGrade->grade_pointer }}" required>
                                    <label for="gradePointer">Nilai Gred</label>
                                </div>
                                <button type="button" id="removeField" data-remove-field-type="courseGrade" data-remove-field-id="courseGrade" class="btn btn-danger m-1 fs-hvr-shrink">Keluarkan</button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="row d-flex flex-column align-items-center justify-content-center">
                    <button type="submit" class="btn btn-primary my-6 w-25 hvr-shrink">Kemas Kini Transkrip</button>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('js/addRemoveField.js') }}"></script>
@endsection
