@extends('dashboard.layout.main')
@section('content')
        <div class="container w-100 h-100 mt-7 mt-md-6">
            <div class="row w-100">
                <h2 class="text-center mt-md-4">Tambah Pelajar Ke Dalam Kelas</h2>
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
                            <div class="alert alert-success">{{ session('successAdd') }}</div>
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
                @if($students->count() > 0)
                    <table class="table table-hover table-bordered border-secondary text-center mt-3">
                        <thead class="table-dark">
                            <tr>
                                <tr>
                                <th class="col-2">ID Pelajar</th>
                                <th class="col-3">Nama Pelajar</th>
                                <th class="col-1">Buang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $student)
                            <tr>
                                <td>{{ $student->user->username }}</td>
                                <td>{{ ucwords($student->user->fullname) }} </td>
                                <td>
                                    <form action="" method="post" class="d-flex justify-content-center">
                                        @csrf
                                        <input type="hidden" name="username" value="{{ $student->user->username }}">
                                        <button type="submit" class="btn btn-danger" name="remove"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="my-4">
                        @if(session()->has('successRemove'))
                            <div class="alert alert-success">{{ session('successRemove') }}</div>
                        @endif
                        <p class="text-center mt-1 fs-3">Tiada rekod pelajar.</p>
                    </div>
                @endif
            </div>
        </div>
@endsection