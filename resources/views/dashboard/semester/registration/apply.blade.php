@extends('dashboard.layout.main')
@section('content')
    <div class="container-fluid mt-1 w-100 h-100 d-flex flex-column align-items-center">
        <div class="row rounded-3 shadow-lg mt-5 w-100 bg-light">
            <div class="col-6 my-3 text-start">
                <a href="{{ route('semester.registration.view', ['username' => Auth::user()->username]) }}" class="btn btn-primary"><i class="bi bi-arrow-return-left"></i> Kembali</a>
            </div>
            <div class="col-6 my-3 text-end">
            </div>
        </div>
        @php
            if(!empty($settings)){
                if(!empty($settings['institute_name'])){
                    $instituteName = ucwords($settings['institute_name']);
                }else{
                    $instituteName = "Kolej Vokasional Malaysia";
                }
            }else{
                $instituteName = "Kolej Vokasional Malaysia";
            }
        @endphp
        <div class="row d-flex justify-content-center rounded-3 shadow-lg mt-2 mb-2 w-100 bg-light">
            <div class="row w-100 mt-3">
                @if(session()->has('semesterRegistrationSuccess'))
                    <div class="alert alert-success">{{ session('semesterRegistrationSuccess') }}</div>
                @endif
                <div class="col-12">
                    <h1 class="fs-6 fw-bold">A: MAKLUMAT PERMOHONAN</h1>
                    <p>NAMA KOLEJ: {{ strtoupper($instituteName) }}</p>
                    <p>JABATAN: {{ strtoupper($program->department_name) }}</p>
                    <p>PROGRAM: {{ strtoupper($program->name) }}</p>
                    @php
                        switch ($semester) {
                            case '1':
                                $studyYear = '1';
                                break;
                            case '2':
                                $studyYear = '1';
                                break;
                            case '3':
                                $studyYear = '2';
                                break;
                            case '4':
                                $studyYear = '2';
                                break;
                            default:
                                $studyYear = '';
                                break;
                        }
                    @endphp
                    <p>TAHUN PENGAJIAN: {{ $studyYear }}</p>
                    <p>SEMESTER: {{ $semester }}</p>
                    <p>SESI/TAHUN: {{ $semesterSession->session }}/{{ $semesterSession->year }}</p>
                    <p>NAMA PELAJAR: {{ strtoupper(Auth::user()->fullname) }}</p>
                    <p>NO K/P: {{ $userProfile->identification_number }}</p>
                    <p>ANGKA GILIRAN: {{ strtoupper(Auth::user()->username) }}</p>
                </div>
                <div class="col-12">
                    <h1 class="fs-6 fw-bold">B: MAKLUMAT KURSUS DIPOHON</h1>
                    <div class="table-responsive my-3">
                        <table class="table table-hover table-bordered border-secondary text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th class="col-1">KOD KURSUS</th>
                                    <th class="col-3">NAMA KURSUS</th>
                                    <th class="col-2">KATEGORI KURSUS</th>
                                    <th class="col-2">BIL KREDIT</th>
                                    <th class="col-2">JUMLAH JAM</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($courseSetCourses as $course)
                                    <tr>
                                        <td>{{ strtoupper($course->code) }}</td>
                                        <td>{{ strtoupper($course->name) }}</td>
                                        <td>
                                            @switch($course->category)
                                                @case(1)
                                                    PENGAJIAN UMUM
                                                    @break
                                                @case(2)
                                                    TERAS
                                                    @break
                                                @case(3)
                                                    PENGKHUSUSAN
                                                    @break
                                                @case(4)
                                                    ELEKTIF
                                                    @break
                                                @case(5)
                                                    ON-THE-JOB TRAINING
                                                    @break
                                                @default
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>{{ $course->credit_hour }}</td>
                                        <td>{{ $course->total_hour }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @switch($registrationStatus)
                    @case(1)
                        <div class="row w-100 my-3">
                            <div class="col-2">
                                <a href="{{ route('semester.registration.view.pdf', ['username' => Auth::user()->username, 'id' => $semesterSession->id]) }}" class="btn btn-primary hvr-shrink"><i class="bi bi-download"> Muat Turun</i></a>
                            </div>
                        </div>
                        @break
                    @case(0)
                        <form action="" method="post" class="my-3">
                            @csrf
                            <input type="hidden" name="username" value="{{ Auth::user()->username }}">
                            <input type="hidden" name="semester_session" value="{{ $semesterSession->id }}">
                            <button class="btn btn-primary hvr-shrink" type="submit"><i class="bi bi-pen"></i> Mohon</button>
                        </form>
                        @break
                    @default
                        @break
                @endswitch
            </div>
        </div>
    </div>
@endsection
