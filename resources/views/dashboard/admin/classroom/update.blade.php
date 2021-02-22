@extends('dashboard.layout.admin')
@section('content')
    <div class="w-100 h-100 mt-3">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-12 col-lg-10">
                <form action="" method="post" class="mt-3 mb-5">
                    @csrf
                    <h2 class="text-center">Kemas Kini Kelas</h2>
                    @if(session()->has('classroomUpdateSuccess'))
                        <div class="alert alert-success">{{ session('classroomUpdateSuccess') }}</div>
                    @endif
                    @error('notExisted')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <div class="mb-3">
                        <p class="fs-5">ID Kelas: <span>{{ $classroom->id }}</span></p>
                        <input type="hidden" name="id" value="{{ $classroom->id }}">
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="name" id="name" class="form-control" placeholder="name" value="@php if(old('name') !== null){echo old('name');}elseif(isset($classroom->name)){echo $classroom->name;}else{echo NULL;} @endphp">
                        <label for="name" class="form-label">Nama Kelas</label>
                        @error('name')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="programs_code" id="programs_code" class="form-control" placeholder="programs_code" value="@php if(old('programs_code') !== null){echo old('programs_code');}elseif(isset($classroom->programs_code)){echo $classroom->programs_code;}else{echo NULL;} @endphp">
                        <label for="programs_code" class="form-label">Kod Program</label>
                        @error('programs_code')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="admission_year" id="admission_year" class="form-control" placeholder="admission_year" value="@php if(old('admission_year') !== null){echo old('admission_year');}elseif(isset($classroom->admission_year)){echo $classroom->admission_year;}else{echo NULL;} @endphp">
                        <label for="admission_year" class="form-label">Tahun Kemasukan</label>
                        <div class="form-text">
                            Format: XXXX
                        </div>
                        @error('admission_year')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="study_year" id="study_year" class="form-control" placeholder="study_year" value="@php if(old('study_year') !== null){echo old('study_year');}elseif(isset($classroom->study_year)){echo $classroom->study_year;}else{echo NULL;} @endphp">
                        <label for="study_year" class="form-label">Tahun Pengajian Terkini</label>
                        <div class="form-text">
                            Format: XXXX
                        </div>
                        @error('study_year')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="study_levels_code" id="study_levels_code" class="form-control" placeholder="study_levels_code" value="@php if(old('study_levels_code') !== null){echo old('study_levels_code');}elseif(isset($classroom->study_levels_code)){echo $classroom->study_levels_code;}else{echo NULL;} @endphp">
                        <label for="study_levels_code" class="form-label">Kod Tahap Pengajian</label>
                        @error('study_levels_code')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100 hvr-shrink" name="classroom_update">Kemas Kini</button>
                </form>
                <h2 class="text-center">Koordinator</h2>
                    @if(session()->has('successRemove'))
                        <div class="alert alert-success">{{ session('successRemove') }}</div>
                    @endif
                    @if(session()->has('successAdd'))
                        <div class="alert alert-success">{{ session('successAdd') }}</div>
                    @endif
                @if($classroomCoordinator != NULL)
                    <div class="table-responsive my-4">
                        <table class="table table-hover table-striped table-bordered border-secondary text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th class="col-3 align-middle fst-normal">Username</th>
                                    <th class="col-5 align-middle fst-normal">Nama Penuh</th>
                                    <th class="col-3 align-middle fst-normal">Alamat E-mel</th>
                                    <th class="col-1 align-middle fst-normal">Buang</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ strtoupper($classroomCoordinator->username) }}</td>
                                    <td>{{ strtoupper($classroomCoordinator->fullname) }}</td>
                                    <td>{{ strtoupper($classroomCoordinator->email) }}</td>
                                    <td>
                                        <form action="" method="post" class="d-flex justify-content-center">
                                            @csrf
                                            <input type="hidden" name="coordinator_username" value="{{ $classroomCoordinator->username }}">
                                            <button type="submit" class="btn btn-danger hvr-shrink" name="remove_coordinator"><i class="bi bi-x-square"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <form action="" method="post" class="mt-3 mb-5">
                        @csrf
                        <h4>Tambah</h4>
                        @error('noUser')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        @error('notALecturer')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <div class="form-floating mb-3">
                            <input type="text" name="coordinator_username" id="coordinator_username" class="form-control" placeholder="coordinator_username" value="@php if(old('coordinator_username') !== null){echo old('coordinator_username');}else{echo NULL;} @endphp">
                            <label for="coordinator_username" class="form-label">Username</label>
                            @error('coordinator_username')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100 hvr-shrink" name="add_coordinator">Tambah</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection