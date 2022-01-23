@extends('dashboard.layout.main')
@section('content')
    <div class="container-fluid mt-1 w-100 h-100 d-flex flex-column align-items-center">
        <div class="row rounded-3 shadow-lg mt-5 w-100 bg-light">
            <div class="col-6 my-3 text-start">
                <a href="{{ route('dashboard') }}" class="btn btn-primary"><i class="bi bi-arrow-return-left"></i>Dashboard</a>
            </div>
            <div class="col-6 my-3 text-end">
                <a href="{{ asset('storage/spreadsheet/Templat_Transkrip_Semester_eKV.xlsx'); }}" class="btn btn-primary"><i class="bi bi-download"></i>Muat Turun Templat</a>
            </div>
        </div>
        <div class="row rounded-3 shadow-lg mt-2 mb-5 w-100 d-flex flex-column align-items-center justify-content-center bg-light">
            <div class="col-11 col-lg-9">
                <h2 class="text-center mt-5 mb-3">Tambah Transkrip Semester Pukal</h2>
                <div class="d-flex flex-column align-items-center justify-content-center">
                    @if(session()->has('spreadsheetErr'))
                        @if(count(session('spreadsheetErr')) > 0)
                            <div class="my-3 alert alert-danger w-100">
                                <p class="fw-bold">Ralat ({{ count(session('spreadsheetErr')) }}):</p>
                                <button class="btn btn-outline-danger" type="button" data-bs-toggle="collapse" data-bs-target="#errorCollapse" aria-expanded="false" aria-controls="errorCollapse"><i class="bi bi-arrows-expand"></i> Senarai Ralat</button>
                                <div class="collapse mt-3" id="errorCollapse">
                                    <div class="card card-body">
                                        @for ($i = 0; $i < count(session('spreadsheetErr')); $i++)
                                            <p class="mt-1">{{ $i + 1 . ": " }}{{ session('spreadsheetErr')[$i] }}</p>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                    @if(session()->has('excelStat'))
                        @if(count(session('excelStat')) > 0)
                            <div class="w-100 d-flex justify-content-center align-content-center mt-3">
                                <div class="mt-3 col-11 col-lg-11 alert alert-success">
                                    <p class="fw-bold">Status:</p>
                                    @for ($i = 0; $i < count(session('excelStat')); $i++)
                                        <p class="mt-3">{{ $i + 1 . ": " }}{{ session('excelStat')[$i] }}</p>
                                    @endfor
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
                <form action="" method="post" enctype="multipart/form-data" class="mt-4 mb-2">
                    @csrf
                    <p class="fw-bold">Info:</p>
                    <ul>
                        <li><p class="mt-3">Borang ini digunakan untuk menambah transkrip semester pelajar secara pukal.</p></li>
                        <li><p class="mt-3">Muat turun templat yang diperlukan bagi menyenaraikan transkrip yang ingin ditambah.</p></li>
                        <li><p class="mt-3">Pastikan anda mengisi templat tersebut mengikut format yang ditetapkan.</p></li>
                        <li><p class="mt-3">Hanya 30 transkrip dapat ditambah pada satu-satu masa.</p></li>
                        <li><p class="mt-3">Jika terdapat duplikasi data seperti ID Pelajar yang sama, hanya data yang pertama akan digunakan.</p></li>
                        <li><p class="mt-3">Jika terdapat sel kosong selepas sel yang mempunyai data, semua sel selepas sel kosong itu akan diabaikan.</p></li>
                    </ul>
                    <input type="file" name="spreadsheet" id="spreadsheet" class="form-control mb-3" required>
                    @error('institute-logo')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    @error('unsupportedType')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <button type="submit" class="btn btn-primary w-100 hvr-shrink mb-3" name="logo">Kemas Kini</button>
                </form>
            </div>
        </div>
    </div>
@endsection
