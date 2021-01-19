@extends('dashboard.layout.main')
@section('content')
    <div class="container-fluid d-flex align-items-center m-0 flex-column">
        <div class="col-10 mt-3 mb-3">
            <h2>Tambah Transkrip Semester</h2>
            @if(session()->has('transcriptSuccess'))
                <div class="alert alert-success">{{ session('transcriptSuccess') }}</div>
            @endif
            @error('noCourseInserted')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <form action="" method="post">
                @csrf
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
                            <label for="total_credit_gpa">Jumlah Jam Kredit</label>
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
                    <div class="row row-cols-3" id="courseGrade"></div>
                </div>
                <div class="row">
                    <button type="submit" class="btn btn-primary mt-2 mb-2">Tambah Transkrip</button>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('js/addRemoveField.js') }}"></script>
@endsection