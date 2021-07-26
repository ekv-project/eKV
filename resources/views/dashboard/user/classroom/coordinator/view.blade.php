@extends('dashboard.layout.main')
@section('content')
    <div class="container-fluid mt-1 w-100 h-100 d-flex flex-column align-items-center">
        <div class="row rounded-3 shadow-lg mt-5 w-100">
            <div class="col-6 my-3 text-start">
                <a href="{{ route('dashboard') }}" class="btn btn-primary"><i class="bi bi-arrow-return-left"></i>Dashboard</a>
            </div>
        </div>
        <div class="row rounded-3 shadow-lg mt-2 mb-2 w-100">
            <div class="row">
                <h1 class="fs-2 text-center my-3">Senarai Kelas Koordinator</h1>
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered border-secondary text-center mt-3">
                        <thead class="table-dark">
                            <tr>
                                <th class="col-1 align-middle fst-normal">ID KELAS</th>
                                <th class="col-1 align-middle">NAMA KELAS</th>
                                <th class="col-1 align-middle">KOD PROGRAM</th>
                                <th class="col-1 align-middle">TAHUN KEMASUKAN</th>
                                <th class="col-1 align-middle">TAHUN PENGAJIAN TERKINI</th>
                                <th class="col-1 align-middle">TAHAP PENGAJIAN</th>
                                <th class="col-1 align-middle">STATUS</th>
                                <th class="col-1 align-middle">LIHAT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($classroomList as $c)
                                <tr>
                                    <td>{{ $c->classroom->id }} </td>
                                    <td>{{ strtoupper($c->classroom->name) }}</td>
                                    <td>{{ strtoupper($c->classroom->programs_code) }}</td>
                                    <td>{{ strtoupper($c->classroom->admission_year) }}</td>
                                    <td>{{ strtoupper($c->classroom->study_year) }}</td>
                                    <td>{{ strtoupper($c->classroom->study_levels_code) }}</td>
                                    <td>
                                        @if($c->classroom->active_status == 1)
                                            <p class="btn btn-success text-light w-100">Aktif</p>
                                        @elseif($c->classroom->active_status == 0)
                                            <p class="btn btn-danger text-light w-100">Nyahaktif</p>
                                        @endif
                                    </td>
                                    <td><a href="{{ route('classroom.view', [$c->classroom->id]) }}" class="btn btn-primary hvr-shrink"><i class="bi bi-eye"></i></a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                    {{ $classroomList->links() }}
            </div>
        </div>
    </div>
@endsection