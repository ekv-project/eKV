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
                <input type="text" name="institute_name" id="institute_name" class="form-control" placeholder="institute_name" value="@php if(old('institute_name') !== null){echo old('institute_name');}elseif(isset($settings['institute_name'])){echo $settings['institute_name'];}else{echo NULL;} @endphp">
                <label for="institute_name" class="form-label">Nama Institut</label>
                @error('institute_name')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">    
                <label for="institute_address" class="form-label">Alamat Institut</label>
                <textarea class="form-control" id="institute_address" name="institute_address" rows="2">@php if(old('institute_address') !== null){echo old('institute_address');}elseif(isset($settings['institute_address'])){echo $settings['institute_address'];}else{echo NULL;} @endphp</textarea>
                @error('institute_address')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-floating mb-3">
                <input type="text" name="institute_email_address" id="institute_email_address" class="form-control" placeholder="institute_email_address" value="@php if(old('institute_email_address') !== null){echo old('institute_email_address');}elseif(isset($settings['institute_email_address'])){echo $settings['institute_email_address'];}else{echo NULL;} @endphp">
                <label for="institute_email_address" class="form-label">Alamat Emel Institut</label>
                @error('institute_email_address')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-floating mb-3">
                <input type="text" name="institute_phone_number" id="institute_phone_number" class="form-control" placeholder="institute_phone_number" value="@php if(old('institute_phone_number') !== null){echo old('institute_phone_number');}elseif(isset($settings['institute_phone_number'])){echo $settings['institute_phone_number'];}else{echo NULL;} @endphp">
                <label for="institute_phone_number" class="form-label">Nombor Telefon Institut</label>
                @error('institute_phone_number')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-floating mb-3">
                <input type="text" name="institute_fax" id="institute_fax" class="form-control" placeholder="institute_fax" value="@php if(old('institute_fax') !== null){echo old('institute_fax');}elseif(isset($settings['institute_fax'])){echo $settings['institute_fax'];}else{echo NULL;} @endphp">
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