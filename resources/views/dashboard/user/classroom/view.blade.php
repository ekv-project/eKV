@extends('dashboard.layout.main')
@section('content')
@foreach ($students as $student)
    {{ $student->user->username }}
    {{ $student->user->fullname }} 
@endforeach
@endsection