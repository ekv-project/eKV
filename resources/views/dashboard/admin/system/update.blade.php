@extends('dashboard.layout.main')
@section('content')
<div class="row d-flex justify-content-center align-items-center">
    <div class="col-5">
        <form action="" method="post" class="mt-3 mb-5">
            @csrf
            <h2>Kemas Kini Tetapan Sistem</h2>
            @if(session()->has('systemUpdateSuccess'))
                <div class="alert alert-success">{{ session('systemUpdateSuccess') }}</div>
            @endif
            <div class="form-floating mb-3">
                <input type="text" name="institute_name" id="institute_name" class="form-control" placeholder="institute_name" value="{{ old('institute_name') }}">
                <label for="institute_name" class="form-label">Nama Institut</label>
                @error('institute_name')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">    
                <label for="institute_address" class="form-label">Alamat Institut</label>
                <textarea class="form-control" id="institute_address" name="institute_address" rows="2" value="{{ old('institute_address') }}"></textarea>
                @error('institute_address')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-floating mb-3">
                <input type="text" name="institute_email_address" id="institute_email_address" class="form-control" placeholder="institute_email_address" value="{{ old('institute_email_address') }}">
                <label for="institute_email_address" class="form-label">Alamat Emel Institut</label>
                @error('institute_email_address')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-floating mb-3">
                <input type="text" name="institute_phone_number" id="institute_phone_number" class="form-control" placeholder="institute_phone_number" value="{{ old('institute_phone_number') }}">
                <label for="institute_phone_number" class="form-label">Nombor Telefon Institut</label>
                @error('institute_phone_number')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-floating mb-3">
                <input type="text" name="institute_fax" id="institute_fax" class="form-control" placeholder="institute_fax" value="{{ old('institute_fax') }}">
                <label for="institute_fax" class="form-label">Fax Institut</label>
                @error('institute_fax')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary w-100 hvr-shrink">Kemas Kini</button>
        </form>
    </div>
</div>
@endsection