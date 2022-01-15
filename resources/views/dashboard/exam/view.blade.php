@extends('dashboard.layout.main')
@section('content')
    <div class="container-fluid mt-1 w-100 h-100 d-flex flex-column align-items-center">
        <div class="row rounded-3 shadow-lg mt-5 w-100 bg-light">
            <div class="col-6 my-3 text-start">
                <a href="{{ route('profile.user', ['username' => $studentID]) }}" class="btn btn-primary"><i class="bi bi-arrow-return-left"></i>Profil</a>
            </div>
            <div class="col-6 my-3 text-end">
                {{-- Only coordinator and admin can view these buttons --}}
                @if(Gate::allows('authAdmin') || Gate::allows('authCoordinator', $studentID))
                    <a href="{{ route('transcript.add', ['studentID' => $studentID]) }}" class="btn btn-primary"><i class="bi bi-plus"></i>Tambah</a>
                @endif
            </div>
        </div>
        <div class="row rounded-3 shadow-lg mt-2 mb-2 w-100 bg-light">
            <h1 class="text-center mt-2">Senarai Transkrip Semester</h1>
            @if(session()->has('transcriptDeleteSuccess'))
                <div class="alert alert-success">{{ session('transcriptDeleteSuccess') }}</div>
            @endif
            @if(count($semesterGrades) > 0)
                <div class="table-responsive my-3">
                    <table class="table table-hover table-bordered border-secondary text-center">
                        <thead class="table-dark">
                            <tr>
                                <th class="col-1">NO</th>
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
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($semesterGrades as $semesterGrade)
                                <tr>
                                    <td>
                                        @php
                                            echo $i;
                                        @endphp
                                    </td>
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
                                        <!-- Delete Static Backdrop Confirmation -->
                                        @php
                                            $deleteFormData = [array("nameAttr" => "studentID", "valueAttr" => $studentID),
                                                               array("nameAttr" => "studyLevel", "valueAttr" => $semesterGrade->study_levels_code),
                                                               array("nameAttr" => "semester", "valueAttr" => $semesterGrade->semester)];
                                        @endphp
                                        <x-delete-confirmation name="transkrip" :formData="$deleteFormData" :increment="$i"/>
                                        <x-delete-confirmation-button :increment="$i"/>
                                    </td>
                                    @endif
                                </tr>
                                @php
                                    $i = $i + 1;
                                @endphp
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
