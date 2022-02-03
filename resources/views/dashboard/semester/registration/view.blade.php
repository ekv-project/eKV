@extends('dashboard.layout.main')
@section('content')
    <div class="container-fluid mt-1 w-100 h-100 d-flex flex-column align-items-center">
        <div class="row rounded-3 shadow-lg mt-5 w-100 bg-light">
            <div class="col-6 my-3 text-start">
                <a href="" class="btn btn-primary"><i class="bi bi-arrow-return-left"></i>Dashboard</a>
            </div>
            <div class="col-6 my-3 text-end">
                {{-- <a href="" class="btn btn-primary"><i class="bi bi-plus"></i>Tambah</a> --}}
            </div>
        </div>
        <div class="row rounded-3 shadow-lg mt-2 mb-2 w-100 bg-light">
            {{-- If admin,  --}}
            @if(Gate::allows('authAdmin'))
                <h1 class="fs-2 text-center my-3">Pendaftaran Semester Pelajar</h1>

            @else
                test1
            @endif
        </div>
    </div>
@endsection
