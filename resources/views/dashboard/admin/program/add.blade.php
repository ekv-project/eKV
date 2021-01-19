@extends('dashboard.layout.main')
@section('content')
    <div class="w-100 h-100">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-12 col-lg-10">
                <form action="" method="post" class="mt-3 mb-5">
                    @csrf
                    <h2>Tambah Program</h2>
                    @if(session()->has('programAddSuccess'))
                        <div class="alert alert-success">{{ session('programAddSuccess') }}</div>
                    @endif
                    @error('existed')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <div class="form-floating mb-3">
                        <input type="text" name="program_code" id="program_code" class="form-control" placeholder="program_code" value="@php if(old('program_code') !== null){echo old('program_code');}else{echo NULL;} @endphp">
                        <label for="program_code" class="form-label">Kod Program</label>
                        @error('program_code')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="program_name" id="program_name" class="form-control" placeholder="program_name" value="@php if(old('program_name') !== null){echo old('program_name');}else{echo NULL;} @endphp">
                        <label for="program_name" class="form-label">Nama Program</label>
                        @error('program_name')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100 hvr-shrink" name="info">Tambah</button>
                </form>
            </div>
        </div>
    </div>    
@endsection