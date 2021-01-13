@extends('dashboard.layout.main')
@section('content')
    <div class="container-fluid d-flex align-items-center m-0 flex-column">
        <div class="row mt-3">
            <h1 class="text-center">Senarai Transkrip Semester</h1>
            @if(session()->has('transcriptDeleteSuccess'))
                <div class="alert alert-success">{{ session('transcriptDeleteSuccess') }}</div>
            @endif
            @if(count($semesterGrades) > 0)
                <table class="table table-hover table-bordered border-secondary text-center">  
                    <thead>
                        <tr>
                            <th class="col-3">Tahap Pengajian</th>
                            <th class="col-2">Semester</th>
                            <th class="col-2"></th>
                            @if(Gate::allows('authAdmin') || Gate::allows('authCoordinator', $studentID))
                                <th class="col-2"></th>
                                <th class="col-2"></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($semesterGrades as $semesterGrade)
                        <tr>
                            <td>{{ strtoupper($semesterGrade->study_levels_code) }}</td>
                            <td>{{ $semesterGrade->semester }}</td>
                            <td><a href="{{ route('transcript', ['studentID' => $studentID, 'studyLevel' => $semesterGrade->study_levels_code, 'semester' => $semesterGrade->semester]) }}" class="btn btn-primary hvr-shrink">Lihat</a></td>
                            @if(Gate::allows('authAdmin') || Gate::allows('authCoordinator', $studentID))
                            <td>
                                <a href="{{ route('transcript.update', ['studentID' => $studentID, 'studyLevel' => $semesterGrade->study_levels_code, 'semester' => $semesterGrade->semester]) }}" class="btn btn-primary hvr-shrink">Kemas Kini</a>
                            </td>
                            <td>
                                <form action="" method="post">
                                    @csrf
                                    <input type="hidden" name="studentID" value="{{ $studentID }}">
                                    <input type="hidden" name="studyLevel" value="{{ $semesterGrade->study_levels_code }}">
                                    <input type="hidden" name="semester" value="{{ $semesterGrade->semester }}">
                                    <button class="btn btn-danger hvr-shrink">Buang</button>
                                </form>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table> 
            @else
                <p class="fst-italic fw-bold text-center mt-3">Tiada transkrip dijumpai untuk pelajar ini!</p>
                @if(Gate::allows('authAdmin') || Gate::allows('authCoordinator', $studentID))
                    <a href="{{ route('transcript.add', ['studentID' => $studentID]) }}" class="btn btn-primary hvr-shrink">Tambah</a>
                @endif 
            @endif
        </div>
    </div>
@endsection