@extends('dashboard.layout.admin')
@section('content')
<div class="row">
    <div class="col-12 col-lg-10">
        @if(isset($settings))
            {{ "Nama Institut: " . ucwords($settings['institute_name']) }}
            <br>
            {{ "Alamat Institut: " . ucwords($settings['institute_address']) }}
            <br>
            {{ "Alamat Emel: " . strtolower($settings['institute_email_address']) }}
            <br>
            {{ "Nombor Telefon: " . $settings['institute_phone_number'] }}
            <br>
            {{ "Nombor Fax: " . $settings['institute_fax'] }}
            <br>
        @else
            {{ "Nama Institut: "}}
            <br>
            {{ "Alamat Institut: "}}
            <br>
            {{ "Alamat Emel: "}}
            <br>
            {{ "Nombor Telefon: "}}
            <br>
            {{ "Nombor Fax: "}}
            <br>   
        @endif
    </div>
</div>
@endsection
