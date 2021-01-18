@extends('dashboard.layout.main')
@section('content')
<div class="container-fluid d-flex flex-column align-items-center justify-content-center m-0 p-0">
    <div class="row w-100 d-flex flex-column align-items-center justify-content-center">
        <div class="col-12 col-lg-10">
            <form action="" method="post" class="mt-3 mb-5">
                @csrf
                <h2 class="text-center">Kemas Kini Program</h2>
                @if(session()->has('programUpdateSuccess'))
                    <div class="alert alert-success">{{ session('programUpdateSuccess') }}</div>
                @endif
                @error('notExisted')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <div class="mb-3">
                    <p class="fs-5">Kod Program: <span>{{ strtoupper($program->code) }}</span></p>
                    <input type="hidden" name="program_code" value="{{ $program->code }}">
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="program_name" id="program_name" class="form-control" placeholder="program_name" value="@php if(old('program_name') !== null){echo old('program_name');}elseif(isset($program->name)){echo ucwords($program->name);}else{echo NULL;} @endphp">
                    <label for="program_name" class="form-label">Nama Kursus</label>
                    @error('program_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary w-100 hvr-shrink" name="info">Kemas Kini</button>
            </form>
        </div>
    </div>
</div>
@endsection