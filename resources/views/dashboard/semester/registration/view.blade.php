@extends('dashboard.layout.main')
@section('content')
    <div class="container-fluid mt-1 w-100 h-100 d-flex flex-column align-items-center">
        <div class="row rounded-3 shadow-lg mt-5 w-100 bg-light">
            <div class="col-6 my-3 text-start">
                <a href="{{ route('dashboard') }}" class="btn btn-primary"><i class="bi bi-arrow-return-left"></i>Dashboard</a>
            </div>
            <div class="col-6 my-3 text-end">
                {{-- <a href="" class="btn btn-primary"><i class="bi bi-plus"></i>Tambah</a> --}}
            </div>
        </div>
        <div class="row rounded-3 shadow-lg mt-2 mb-2 w-100 bg-light">
            <h1 class="fs-2 text-center my-3">Pendaftaran Semester Pelajar</h1>
            @if(count($semesterSessions) > 0)
                <div class="table-responsive my-3">
                    <table class="table table-hover table-bordered border-secondary text-center">
                        <thead class="table-dark">
                            <tr>
                                <th class="col-1">Kod Tahap Pengajian</th>
                                <th class="col-1">Kod Program</th>
                                <th class="col-1">Sesi/Tahun</th>
                                <th class="col-1">Status Sesi</th>
                                <th class="col-1">Status Permohonan</th>
                                <th class="col-1">Mohon</th>
                                <th class="col-1">Muat Turun Permohonan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($semesterSessions as $session)
                                <tr>
                                    <td>{{ strtoupper($session['studyLevel']) }}</td>
                                    <td>{{ strtoupper($session['program']) }}</td>
                                    <td>{{ $session['session'] }}/{{ $session['year'] }}</td>
                                    <td>
                                        @if($session['sessionStatus'] == 'open')
                                            <p class="btn btn-success text-light w-100">Buka</p>
                                        @elseif($session['sessionStatus'] == 'close')
                                            <p class="btn btn-danger text-light w-100">Tutup</p>
                                        @endif
                                    </td>
                                    <td>
                                        @if($session['registrationStatus'] == 1)
                                            <p class="btn btn-success text-light w-100">Telah Memohon</p>
                                        @elseif($session['registrationStatus'] == 0)
                                            <p class="btn btn-danger text-light w-100">Belum Memohon</p>
                                        @endif
                                    </td>
                                    <td>
                                        @if($session['registrationStatus'] == 0 && $session['sessionStatus'] == 'open')
                                            <a href="{{ route('semester.registration.apply', ['username' => Auth::user()->username, 'id' => $session['id']]) }}" class="btn btn-primary hvr-shrink"><i class="bi bi-pen"></i></a>
                                        @else
                                            <button class="btn btn-primary hvr-shrink" disabled><i class="bi bi-slash-circle"></i></button>
                                        @endif
                                    </td>
                                    <td>
                                        @if($session['registrationStatus'] == 1)
                                            <a href="{{ route('semester.registration.view', ['username' => Auth::user()->username, 'id' => $session['id']]) }}" class="btn btn-primary hvr-shrink"><i class="bi bi-download"></i></a>
                                        @else
                                            <button class="btn btn-primary hvr-shrink" disabled><i class="bi bi-slash-circle"></i></button>
                                        @endif
                                    </td>
                                </tr>
                                @php
                                    $i = $i + 1;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="fst-italic fw-bold text-center my-4">Tiada sesi pendaftaran semester dijumpai!</p>
            @endif
        </div>
    </div>
@endsection
