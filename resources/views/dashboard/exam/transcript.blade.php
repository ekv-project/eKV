@extends('dashboard.layout.main')
@section('content')
    <div class="container-fluid mt-6 w-100 h-100 d-flex flex-column align-items-center">
        <div class="row rounded-3 shadow-lg mt-5 w-100">
            <div class="col-6 my-3 text-start">
                <a href="{{ route('transcript.student', [$studentDetails['matrixNumber']]) }}" class="btn btn-primary"><i class="bi bi-arrow-return-left"></i>Senarai Transkrip</a>
            </div>
            <div class="col-6 my-3 text-end">
            </div>
        </div>
        <div class="row rounded-3 shadow-lg mt-2 mb-2 w-100 d-flex flex-column align-items-center justify-content-center">
            <div class="row text-center">
                <h2 class="my-4">Transkrip Penilaian Semester</h2>
            </div>
            <div class="row">
                <div class="col-6">
                    <p><strong>Nama: </strong>{{ ucwords($studentDetails['name']) }}</p>
                    <p><strong>No. Kad Pengenalan: </strong>{{ $studentDetails['identificationNumber'] }}</p>
                    <p><strong>Angka Giliran: </strong>{{ $studentDetails['matrixNumber'] }}</p>
                </div>
                <div class="col-6">
                    <p><strong>Peringkat Pengajian: </strong>{{ ucwords($studyLevelName) }}</p>
                    <p><strong>Program: </strong>{{ ucwords($studentProgram) }}</p>
                    <p><strong>Semester: </strong>{{ $semester }}</p>
                </div>
            </div>
            @if($courseGrades->count() > 0)
                <div class="row table-responsive my-3">
                    <table class="table table-hover table-bordered border-secondary text-center">
                        <thead class="table-dark">
                            <tr>
                                <th class="col-2">KOD KURSUS</th>
                                <th class="col">NAMA KURSUS</th>
                                <th class="col-2">JAM KREDIT</th>
                                <th class="col-2">NILAI GRED</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($courseGrades as $courseGrade)
                                <tr>
                                    <td>{{ strtoupper($courseGrade->code) }}</td>
                                    <td>{{ strtoupper($courseGrade->name) }}</td>
                                    <td>{{ $courseGrade->credit_hour }}</td>
                                    <td>{{ $courseGrade->grade_pointer }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table> 
                </div>
            @else
                <div class="row my-5 ">
                    <p class="fw-bold fst-italic fs-6">Tiada rekod kursus dijumpai.</p>
                </div>
            @endif
            <div class="row table-responsive my-3">
                <table class="table table-hover table-bordered border-secondary text-center">
                    <thead class="table-dark">
                        <th>KOMPONEN</th>
                        <th>JUMLAH NILAI KREDIT</th>
                        <th>PURATA NILAI GRED</th>
                    </thead>
                    <tbody>
                        <tr>
                            <th class="fw-bold text-start">PNG SEMESTER SEMASA (PNGS)</th>
                            <td>{{ $semesterGrade->total_credit_gpa }}</td>
                            <td>{{ $semesterGrade->gpa }}</td>
                        </tr>
                        <tr>
                            <th class="fw-bold text-start">PNG KUMULATIF KESELURUHAN (PNGKK)</th>
                            <td>{{ $semesterGrade->total_credit_cgpa }}</td>
                            <td>{{ $semesterGrade->cgpa }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection