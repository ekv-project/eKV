@extends('dashboard.layout.main')
@section('content')
    <div class="container-fluid mt-1 w-100 h-100 d-flex flex-column align-items-center">
        <div class="row rounded-3 shadow-lg mt-5 w-100 bg-light">
            <div class="col-6 my-3 text-start">
                <a href="{{ route('classroom.view', [$classroomData['id']]) }}" class="btn btn-primary"><i class="bi bi-arrow-return-left"></i>Kelas</a>
            </div>
            <div class="col-6 my-3">
            </div>
        </div>
        <div class="row rounded-3 shadow-lg mt-2 mb-6 w-100 bg-light">
            <div class="row w-100">
                <h2 class="text-center mt-md-4">Kemas Kini Senarai Pelajar</h2>
                <h2 class="text-left mt-md-4 fs-5 mb-4 fw-bold">ID Kelas: <span class="fw-normal">{{ $classroomData['id'] }}</span></h2>
            </div>
            <div class="row w-100">
                <form action="" method="post">
                    @csrf
                    <label for="studentID" class="form-label">ID Pelajar</label>
                    <div class="row w-100">
                        {{-- Both input and ul element is used for live search --}}
                        <div class="col-11">
                            <input type="text" name="studentID" class="form-control" id="searchInput" data-type="student">
                            <ul class="list-group hover" id="searchResult"></ul>   
                        </div>
                        <div class="col-1">
                            <button class="btn btn-primary" name="add"><i class="bi bi-plus"></i></button>
                        </div>
                    </div>
                    @if(session()->has('successAdd'))
                        <div class="alert alert-success w-75">{{ session('successAdd') }}</div>
                    @endif
                    @error('existed')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    @error('notStudent')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    @error('noUser')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </form>
            </div>
            <div class="row w-100">
                @if(session()->has('successRemove'))
                    <div class="w-100 d-flex">
                        <div class="alert alert-success w-75 mt-3 align-self-center">{{ session('successRemove') }}</div>
                    </div>
                @endif
                @if($students->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered border-secondary text-center mt-3">
                            <thead class="table-dark">
                                <tr>
                                    <th class="col-1">NO</th>
                                    <th class="col-3">ID PELAJAR</th>
                                    <th class="col-7">NAMA PELAJAR</th>
                                    <th class="col-1">BUANG</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($students as $student)
                                <tr>
                                    <td>
                                        @php
                                            echo $i;
                                            $i = $i + 1;
                                        @endphp
                                    </td>
                                    <td>{{ strtoupper($student->username) }}</td>
                                    <td>{{ strtoupper($student->fullname) }} </td>
                                    <td>
                                        <form action="" method="post" class="d-flex justify-content-center">
                                            @csrf
                                            <input type="hidden" name="username" value="{{ $student->username }}">
                                            <button type="submit" class="btn btn-danger" name="remove"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="my-4">
                        <p class="text-center mt-1 fs-5">Tiada rekod pelajar.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection