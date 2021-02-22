@extends('dashboard.layout.admin')
@section('content')
<div class="row d-flex justify-content-center h-100 w-100">
    <div class="col-12 col-lg-10">
        <form action="" method="post" enctype="multipart/form-data" class="mt-4 mb-2">
            @csrf
            <h2 class="text-left">Kemas Kini Logo Institut</h2>
            @if(session()->has('logoSuccess'))
                <div class="alert alert-success">{{ session('logoSuccess') }}</div>
            @endif
            <input type="file" name="institute-logo" id="institute-logo" class="form-control mb-3">
            <div class="form-text mb-2 text-left">Logo Mestilah Bernisbah 1:1 <br> Resolusi Lebih Daripada 300x300 <span class="fst-italic">Pixel</span></div>
            @error('institute-logo')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @error('unsupportedType')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <button type="submit" class="btn btn-primary w-100 hvr-shrink" name="logo">Kemas Kini</button>
        </form>
    </div>
    <div class="col-12 col-lg-10">
        <form action="" method="post" class="mt-5 mb-5">
            @csrf
            <h2 class="text-left">Kemas Kini Maklumat Institut</h2>
            @if(session()->has('instituteUpdateSuccess'))
                <div class="alert alert-success">{{ session('instituteUpdateSuccess') }}</div>
            @endif
            <div class="form-floating mb-3">
                <input type="text" name="institute_name" id="institute_name" class="form-control" placeholder="institute_name" value="@php if(old('institute_name') !== null){echo old('institute_name');}elseif(isset($settings['institute_name'])){echo ucwords($settings['institute_name']);}else{echo NULL;} @endphp">
                <label for="institute_name" class="form-label">Nama Institut</label>
                @error('institute_name')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">    
                <label for="institute_address" class="form-label">Alamat Institut</label>
                <textarea class="form-control" id="institute_address" name="institute_address" rows="2">@php if(old('institute_address') !== null){echo old('institute_address');}elseif(isset($settings['institute_address'])){echo ucwords($settings['institute_address']);}else{echo NULL;} @endphp</textarea>
                @error('institute_address')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-floating mb-3">
                <input type="text" name="institute_email_address" id="institute_email_address" class="form-control" placeholder="institute_email_address" value="@php if(old('institute_email_address') !== null){echo old('institute_email_address');}elseif(isset($settings['institute_email_address'])){echo strtolower($settings['institute_email_address']);}else{echo NULL;} @endphp">
                <label for="institute_email_address" class="form-label">Alamat Emel Institut</label>
                @error('institute_email_address')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-floating mb-3">
                <input type="text" name="institute_phone_number" id="institute_phone_number" class="form-control" placeholder="institute_phone_number" value="@php if(old('institute_phone_number') !== null){echo old('institute_phone_number');}elseif(isset($settings['institute_phone_number'])){echo ucwords($settings['institute_phone_number']);}else{echo NULL;} @endphp">
                <label for="institute_phone_number" class="form-label">Nombor Telefon Institut</label>
                @error('institute_phone_number')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-floating mb-3">
                <input type="text" name="institute_fax" id="institute_fax" class="form-control" placeholder="institute_fax" value="@php if(old('institute_fax') !== null){echo old('institute_fax');}elseif(isset($settings['institute_fax'])){echo ucwords($settings['institute_fax']);}else{echo NULL;} @endphp">
                <label for="institute_fax" class="form-label">Fax Institut</label>
                @error('institute_fax')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary w-100 hvr-shrink" name="info">Kemas Kini</button>
        </form>
    </div>
</div>
@endsection