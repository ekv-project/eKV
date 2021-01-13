@extends('dashboard.layout.main')
@section('content')
    <div class="container-fluid d-flex align-items-center m-0 flex-column">
        <div class="col-10 m-3">
            <div class="row m-0 text-center">
                <h2>Transkrip Penilaian Semester</h2>
            </div>
            <div class="row m-0">
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
                <div class="row m-0">
                    <table class="table table-hover table-bordered border-secondary text-center">
                        <thead>
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
                <div class="row m-0">
                    <p class="fw-bold fst-italic fs-6">Tiada rekod kursus dijumpai.</p>
                </div>
            @endif
            <div class="row m-0">
                <table class="table table-hover table-bordered border-secondary text-center">
                    <thead>
                        <th>KOMPONEN</th>
                        <th>JUMLAH NILAI KREDIT</th>
                        <th>PURATA NILAI GRED</th>
                    </thead>
                    <tbody>
                        <tr>
                            <th class="fst-italic fw-normal">PNG SEMESTER SEMASA (PNGS)</th>
                            <td>{{ $semesterGrade->total_credit_gpa }}</td>
                            <td>{{ $semesterGrade->gpa }}</td>
                        </tr>
                        <tr>
                            <th class="fst-italic fw-normal">PNG KUMULATIF KESELURUHAN</th>
                            <td>{{ $semesterGrade->total_credit_cgpa }}</td>
                            <td>{{ $semesterGrade->cgpa }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection