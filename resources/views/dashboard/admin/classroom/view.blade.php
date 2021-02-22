@extends('dashboard.layout.admin')
@section('content')
    <div class="w-100 h-100 mt-3">
        <div class="row text-center">
            <h1>Senarai Kelas</h1>
        </div>
        <div class="row d-flex justify-content-center align-items-center mb-5">
            <div class="row d-flex justify-content-center align-items-center col-12 col-lg-11">
                <div class="row d-flex justify-content-center align-content-center mt-3">
                    <form action="" method="get" class="row">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-floating">
                                    <select class="form-select" id="sort_by" name="sort_by" aria-label="sortby">
                                        <option value="id">ID Kelas</option>
                                        <option value="name">Nama Kelas</option>
                                        <option value="program">Program Kelas</option>
                                        <option value="admission_year">Tahun Masuk</option>
                                        <option value="study_level">Tahap Pengajian</option>
                                    </select>
                                    <label for="sort_by">Susun Mengikut:</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-floating">
                                    <select class="form-select" id="sort_order" name="sort_order" aria-label="sortorder">
                                        <option value="asc">Meningkat</option>
                                        <option value="desc">Menurun</option>
                                    </select>
                                    <label for="sort_order">Susunan:</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-9 col-lg-10">
                                <input type="text" class="form-control" name="search" placeholder="Carian">
                            </div>
                            <div class="col-3 col-lg-2">
                                <button type="submit" class="btn btn-primary hvr-shrink w-100"><i class="bi bi-search" style="margin-right: 1rem;"></i>Cari</button>
                            </div>
                        </div>
                    </form>
                </div>
                @if(isset($filterAndSearch))
                    <div class="row d-flex justify-content-center align-content-center mt-3">
                        <div class="row">
                            <p class="text-center fw-bold">Hasil Carian</p>
                        </div>
                        <div class="row">
                            @if($filterAndSearch['sortBy'] != NULL AND $filterAndSearch['sortOrder'] != NULL)
                                <div class="col-6">
                                    @if($filterAndSearch['sortBy'] == 'id')
                                        <p>Susun Mengikut: <span class="fst-italic">ID Kelas</span></p>
                                    @elseif($filterAndSearch['sortBy'] == 'name')
                                        <p>Susun Mengikut: <span class="fst-italic">Nama Kelas</span></p>
                                    @elseif($filterAndSearch['sortBy'] == 'program')
                                        <p>Susun Mengikut: <span class="fst-italic">Program Kelas</span></p>
                                    @elseif($filterAndSearch['sortBy'] == 'admission_year')
                                        <p>Susun Mengikut: <span class="fst-italic">Tahun Kemasukan</span></p>
                                    @elseif($filterAndSearch['sortBy'] == 'study_level')
                                        <p>Susun Mengikut: <span class="fst-italic">Tahap Pengajian</span></p>
                                    @endif
                                </div>
                                <div class="col-6">
                                    @if($filterAndSearch['sortOrder'] == 'asc')
                                        <p>Susunan: <span class="fst-italic">Meningkat</span></p>
                                    @elseif($filterAndSearch['sortOrder'] == 'desc')
                                        <p>Susunan: <span class="fst-italic">Menurun</span></p>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            @if($filterAndSearch['search'] != NULL)
                                <p>Hasil carian bagi: <span class="fst-italic">{{ $filterAndSearch['search'] }}</span></p>
                            @endif
                        </div>
                    </div>
                @endif
                @if(session()->has('deleteSuccess'))
                    <div class="row d-flex justify-content-center align-content-center mt-3">
                        <div class="col-11 col-lg-11 alert alert-success">{{ session('deleteSuccess') }}</div>
                    </div>
                @endif
                @if($classroom->count() < 1)
                    <div class="row d-flex justify-content-center align-content-center mt-3">
                        <p class="text-center mt-3 fs-5">Tiada rekod dijumpai.</p>
                    </div>
                @else
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered border-secondary text-center mt-3">
                        <thead class="table-dark">
                            <tr>
                                @if(session()->has('successRemove'))
                                    <div class="alert alert-success">{{ session('successRemove') }}</div>
                                @endif
                                <tr>
                                <th class="col-1 align-middle fst-normal">ID KELAS</th>
                                <th class="col-1 align-middle">NAMA KELAS</th>
                                <th class="col-1 align-middle">KOD PROGRAM</th>
                                <th class="col-1 align-middle">TAHUN KEMASUKAN</th>
                                <th class="col-1 align-middle">TAHUN PENGAJIAN TERKINI</th>
                                <th class="col-1 align-middle">TAHAP PENGAJIAN</th>
                                <th class="col-1 align-middle">ID KOORDINATOR</th>
                                <th class="col-1 align-middle">LIHAT</th>
                                <th class="col-1 align-middle">KEMAS KINI</th>
                                <th class="col-1 align-middle">BUANG</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($classroom as $c)
                                <tr>
                                    <td>{{ $c->id }} </td>
                                    <td>{{ strtoupper($c->name) }}</td>
                                    <td>{{ strtoupper($c->programs_code) }}</td>
                                    <td>{{ strtoupper($c->admission_year) }}</td>
                                    <td>{{ strtoupper($c->study_year) }}</td>
                                    <td>{{ strtoupper($c->study_levels_code) }}</td>
                                    <td></td>
                                    <td><a href="{{ route('classroom.view', [$c->id]) }}" target="_blank" class="btn btn-primary hvr-shrink"><i class="bi bi-eye"></i></a></td>
                                    <td><a class="btn btn-primary hvr-shrink" href="{{ route('admin.classroom.update', ['id' => $c->id]) }}"><i class="bi bi-pencil-square"></i></a></td>
                                    <td>
                                        <form action="" method="post" class="d-flex justify-content-center">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ strtolower($c->id) }}">
                                            <button type="submit" class="btn btn-danger hvr-shrink" name="remove"><i class="bi bi-x-square"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                    {{ $classroom->links() }}
                @endif
            </div>
        </div>
    </div>
@endsection