@extends('dashboard.layout.admin')
@section('content')
    <div class="w-100 h-100 mt-3">
        <div class="row text-center">
            <h1>Senarai Program</h1>
        </div>
        <div class="row d-flex justify-content-center align-items-center mb-5">
            <div class="row d-flex justify-content-center align-items-center col-12 col-lg-10">
                <div class="row d-flex justify-content-center align-content-center mt-3">
                    <form action="" method="get" class="row">
                        <div class="row row-cols-1 row-cols-md-2">
                            <div class="col mb-2">
                                <div class="form-floating">
                                    <select class="form-select" id="sort_by" name="sort_by" aria-label="sortby">
                                        <option value="id">ID</option>
                                        <option value="title">Tajuk</option>
                                        <option value="created_at">Tarikh Dan Waktu Terbitan</option>
                                    </select>
                                    <label for="sort_by">Susun Mengikut:</label>
                                </div>
                            </div>
                            <div class="col">
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
                            <div class="col-7 col-md-10">
                                <input type="text" class="form-control" name="search" placeholder="Carian">
                            </div>
                            <div class="col-5 col-md-2">
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
                                        <p>Susun Mengikut: <span class="fst-italic">ID Kursus</span></p>
                                    @elseif($filterAndSearch['sortBy'] == 'title')
                                        <p>Susun Mengikut: <span class="fst-italic">Tajuk Pengumuman</span></p>
                                    @elseif($filterAndSearch['sortBy'] == 'created_at')
                                        <p>Susun Mengikut: <span class="fst-italic">Tarikh Dan Waktu Terbitan</span></p>
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
                @if($announcementPost->count() < 1)
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
                                    <th class="col-1 align-middle">ID</th>
                                    <th class="col align-middle">TAJUK</th>
                                    <th class="col-2 align-middle">TARIKH DAN WAKTU</th>
                                    <th class="col-2 align-middle">DITERBITKAN OLEH</th>
                                    <th class="col-1 align-middle">KEMAS KINI</th>
                                    <th class="col-1 align-middle">BUANG</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($announcementPost as $a)
                                    <tr>
                                        <td>{{ $a->id }}</td>
                                        <td>{{ strtoupper($a->title) }}</td>
                                        <td><x-buk-carbon :date="$a->created_at" format="d/m/Y, h:i A"/></td>
                                        <td>{{ strtoupper($a->username) }}</td>
                                        <td><a class="btn btn-primary hvr-shrink" href="{{ route('admin.announcement.update', ['id' => $a->id]) }}"><i class="bi bi-pencil-square"></i></a></td>
                                        <td>
                                            <form action="" method="post" class="d-flex justify-content-center">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $a->id }}">
                                                <button type="submit" class="btn btn-danger hvr-shrink" name="remove"><i class="bi bi-trash"></i></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $announcementPost->links() }}
                @endif
            </div>
        </div>
    </div>
@endsection