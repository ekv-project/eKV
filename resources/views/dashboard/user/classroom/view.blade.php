@extends('dashboard.layout.main')
@section('content')
@foreach ($students as $student)
    {{ $student->user->username }}
    {{ ucwords($student->user->fullname) }} 
@endforeach
@endsection