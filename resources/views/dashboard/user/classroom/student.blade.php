@extends('dashboard.layout.main')
@section('content')
<div class="container col-8">
    <form action="" method="post" class="mt-5">
        @csrf
        <div class="row">
            <h1>Tambah Pelajar Ke Dalam Kelas</h1>
        </div>
        <div class="row">
            <label for="studentID" class="form-label">ID Pelajar</label>
        </div>
        <div class="row">
            <div class="col-10">
                {{-- Both input and ul element is used for live search --}}
                <input type="text" name="studentID" class="form-control" id="searchInput" data-type="student">
                <ul class="list-group hover" id="searchResult"></ul>   
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
            </div>
            <div class="col-2">
                <button class="btn btn-primary" name="add">Tambah</button>
            </div>
        </div>
    </form>
    <table class="table table-hover table-bordered border-secondary text-center mt-3">
        <thead>
            <tr>
                @if(session()->has('successRemove'))
                    <div class="alert alert-success">{{ session('successRemove') }}</div>
                @endif
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
                        <button type="submit" class="btn btn-danger" name="remove">X</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
      </table>
</div>
@endsection