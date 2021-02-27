@extends('dashboard.layout.main')
@section('content')
    <div class="container-fluid mt-1 w-100 h-100 d-flex flex-column align-items-center">
        <div class="row rounded-3 shadow-lg mt-5 w-100">
            <div class="col-6 my-3 text-start">
                <a href="{{ route('dashboard') }}" class="btn btn-primary"><i class="bi bi-arrow-return-left"></i>Dashboard</a>
            </div>
            <div class="col-6 my-3 text-end">
                {{-- Only coordinator and admin can view these buttons --}}
                @if(Gate::allows('authAdmin') || Gate::allows('authCoordinator', $studentID))
                    <a href="{{ route('transcript.add', ['studentID' => $studentID]) }}" class="btn btn-primary"><i class="bi bi-plus"></i>Tambah</a>
                @endif
            </div>
        </div>
        <div class="row rounded-3 shadow-lg mt-2 mb-2 w-100">
            <h1 class="text-center mt-2">Senarai Transkrip Semester</h1>
            @if(session()->has('transcriptDeleteSuccess'))
                <div class="alert alert-success">{{ session('transcriptDeleteSuccess') }}</div>
            @endif
            @if(count($semesterGrades) > 0)
                <div class="table-responsive my-3">
                    <table class="table table-hover table-bordered border-secondary text-center">  
                        <thead class="table-dark">
                            <tr>
                                <th class="col-3">Tahap Pengajian</th>
                                <th class="col-2">Semester</th>
                                <th class="col-2">Lihat</th>
                                <th class="col-2">Muat Turun</th>
                                @if(Gate::allows('authAdmin') || Gate::allows('authCoordinator', $studentID))
                                    <th class="col-2">Kemas Kini</th>
                                    <th class="col-2">Buang</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($semesterGrades as $semesterGrade)
                            <tr>
                                <td>{{ strtoupper($semesterGrade->study_levels_code) }}</td>
                                <td>{{ $semesterGrade->semester }}</td>
                                <td><a href="{{ route('transcript.view', ['studentID' => $studentID, 'studyLevel' => $semesterGrade->study_levels_code, 'semester' => $semesterGrade->semester]) }}" class="btn btn-primary hvr-shrink"><i class="bi bi-eye"></i></a></td>
                                <td>
                                    <a href="{{ route('transcript.download', ['studentID' => $studentID, 'studyLevel' => $semesterGrade->study_levels_code, 'semester' => $semesterGrade->semester]) }}" class="btn btn-primary hvr-shrink"><i class="bi bi-download"></i></a>
                                </td>
                                @if(Gate::allows('authAdmin') || Gate::allows('authCoordinator', $studentID))
                                <td>
                                    <a href="{{ route('transcript.update', ['studentID' => $studentID, 'studyLevel' => $semesterGrade->study_levels_code, 'semester' => $semesterGrade->semester]) }}" class="btn btn-primary hvr-shrink"><i class="bi bi-pencil-square"></i></a>
                                </td>
                                <td>
                                    <form action="" method="post">
                                        @csrf
                                        <input type="hidden" name="studentID" value="{{ $studentID }}">
                                        <input type="hidden" name="studyLevel" value="{{ $semesterGrade->study_levels_code }}">
                                        <input type="hidden" name="semester" value="{{ $semesterGrade->semester }}">
                                        <button class="btn btn-danger hvr-shrink"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table> 
                </div>
            @else
                <p class="fst-italic fw-bold text-center my-4">Tiada transkrip dijumpai untuk pelajar ini!</p> 
            @endif
        </div>
    </div>
@endsection