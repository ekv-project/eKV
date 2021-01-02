@extends('dashboard.layout.main')
@section('content')
{{ "Nama Institut: " . $settings['institute_name'] }}
<br>
{{ "Alamat Institut: " . $settings['institute_address'] }}
<br>
{{ "Alamat Emel: " . $settings['institute_email_address'] }}
<br>
{{ "Nombor Telefon: " . $settings['institute_phone_number'] }}
<br>
{{ "Nombor Fax: " . $settings['institute_fax'] }}
<br>
<a href="{{ route('admin.system.update') }}" class="btn btn-primary hvr-shrink">Kemas Kini</a>  
@endsection
