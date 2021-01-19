@extends('dashboard.layout.main')
@section('content')
    <div class="w-100 h-100">
        <div class="row text-center">
            <h1>Senarai Program</h1>
        </div>
        <div class="row d-flex justify-content-center align-items-center">
            <div class="row d-flex justify-content-center align-items-center table-responsive col-12 col-lg-10">
                <div class="row d-flex justify-content-center align-content-center mt-3">
                    <form action="" method="get" class="row">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-floating">
                                    <select class="form-select" id="sort_by" name="sort_by" aria-label="sortby">
                                        <option value="code">Kod Program</option>
                                        <option value="name">Nama Program</option>
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
                                    @if($filterAndSearch['sortBy'] == 'code')
                                        <p>Susun Mengikut: <span class="fst-italic">Kod Kursus</span></p>
                                    @elseif($filterAndSearch['sortBy'] == 'nama')
                                        <p>Susun Mengikut: <span class="fst-italic">Nama Kursus</span></p>
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
                @if($program->count() < 1)
                    <div class="row d-flex justify-content-center align-content-center mt-3">
                        <p class="text-center mt-3 fs-5">Tiada rekod dijumpai.</p>
                    </div>
                @else
                    <table class="table table-hover table-striped table-bordered border-secondary text-center mt-3">
                        <thead>
                            <tr>
                                @if(session()->has('successRemove'))
                                    <div class="alert alert-success">{{ session('successRemove') }}</div>
                                @endif
                                <tr>
                                <th class="col-2">KOD PROGRAM</th>
                                <th class="col-3">NAMA PROGRAM</th>
                                <th class="col-1"></th>
                                <th class="col-1"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($program as $p)
                                <tr>
                                    <td>{{ strtoupper($p->code) }}</td>
                                    <td>{{ strtoupper($p->name) }}</td>
                                    <td><a class="btn btn-primary hvr-shrink" href="{{ route('admin.program.update', ['code' => strtolower($p->code)]) }}"><i class="bi bi-pencil-square"></i></a></td>
                                    <td>
                                        <form action="" method="post" class="d-flex justify-content-center">
                                            @csrf
                                            <input type="hidden" name="code" value="{{ strtolower($p->code) }}">
                                            <button type="submit" class="btn btn-danger hvr-shrink" name="remove"><i class="bi bi-x-square"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $program->links() }}
                @endif
            </div>
        </div>
    </div>
@endsection