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
        <div class="row rounded-3 shadow-lg mt-2 mb-2 w-100 d-flex align-items-center justify-content-center bg-light">
            <h2 class="text-center my-3">Tambah Transkrip Semester</h2>
            @if(session()->has('transcriptSuccess'))
                <div class="alert alert-success">{{ session('transcriptSuccess') }}</div>
            @endif
            @if(session()->has('transcriptErr'))
                @if(count(session('transcriptErr')) > 0)
                    <div class="my-3 alert alert-danger">
                        <p class="fw-bold">Ralat ({{ count(session('transcriptErr')) }}):</p>
                        <button class="btn btn-outline-danger" type="button" data-bs-toggle="collapse" data-bs-target="#errorCollapse" aria-expanded="false" aria-controls="errorCollapse"><i class="bi bi-arrows-expand"></i> Senarai Ralat</button>
                        <div class="collapse mt-3" id="errorCollapse">
                            <div class="card card-body">
                                @for ($i = 0; $i < count(session('transcriptErr')); $i++)
                                    <p class="mt-1">{{ $i + 1 . ": " }}{{ session('transcriptErr')[$i] }}</p>
                                @endfor
                            </div>
                        </div>
                    </div>
                @endif
            @endif
            <form action="" method="post">
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
                <div class="row">
                    <div class="col">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="studyLevel" name="studyLevel" aria-label="Tahap Pengajian">
                                @foreach ($studyLevels as $studyLevel)
                                    <option value="{{ $studyLevel->code }}">{{ ucwords($studyLevel->name) }}</option>
                                @endforeach
                            </select>
                            <label for="studyLevel">Tahap Pengajian</label>
                        </div>
                        @error('studyLevel')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="semester" name="semester" aria-label="Semester">
                                @php
                                    for ($i=1; $i <= $maxSemester; $i++) {
                                        echo '<option value="' . $i . '">' . $i . '</option>';
                                    }
                                @endphp
                            </select>
                            <label for="semester">Semester</label>
                        </div>
                        @error('semester')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <h1 class="fw-bold fs-6">Purata Nilai Gred</h1>
                    <div class="col">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="gpa" name="gpa" placeholder="gpa" value="{{ old('gpa') }}" required>
                            <label for="gpa">Purata Nilai Gred Semasa</label>
                        </div>
                        @error('gpa')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="total_credit_gpa" name="total_credit_gpa" placeholder="total_credit_gpa" value="{{ old('total_credit_gpa') }}" required>
                            <label for="total_credit_gpa">Jumlah Jam Kredit Semasa</label>
                        </div>
                        @error('total_credit_gpa')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="cgpa" name="cgpa" placeholder="cgpa" value="{{ old('cgpa') }}" required>
                            <label for="cgpa">Purata Nilai Gred Kumulatif</label>
                        </div>
                        @error('cgpa')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="total_credit_cgpa" name="total_credit_cgpa" placeholder="total_credit_cgpa" value="{{ old('total_credit_cgpa') }}" required>
                            <label for="total_credit_cgpa">Jumlah Jam Kredit Kumulatif</label>
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
                        @if(!empty(old('coursesCode')))
                                @for ($i = 0; $i < count(old('coursesCode')); $i++)
                                <div>
                                    <div class="col border border-dark mt-1 d-flex flex-column justify-content-center align-items-center">
                                        <div class="form-floating m-2">
                                            <input type="text" class="form-control" id="coursesCode" name="coursesCode[]" placeholder="coursesCode" value="{{ old('coursesCode')[$i] }}" required>
                                            <label for="coursesCode">Kod Kursus</label>
                                        </div>
                                        <div class="form-floating m-2">
                                            <input type="text" class="form-control" id="gradePointer" name="gradePointer[]" placeholder="gradePointer" value="{{ old('gradePointer')[$i] }}" required>
                                            <label for="gradePointer">Nilai Gred</label>
                                        </div>
                                        <button type="button" id="removeField" data-remove-field-type="courseGrade" data-remove-field-id="courseGrade" class="btn btn-danger m-1 fs-hvr-shrink">Keluarkan</button>
                                    </div>
                                </div>
                                @endfor
                        @endif
                    </div>
                </div>
                <div class="row d-flex flex-column align-items-center justify-content-center">
                    <button type="submit" class="btn btn-primary my-6 hvr-shrink w-25">Tambah Transkrip</button>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('js/addRemoveField.js') }}"></script>
@endsection
