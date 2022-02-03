@extends('dashboard.layout.admin')
@section('content')
    <div class="w-100 h-100 mt-3">
        <div class="row text-center">
            <h1>Senarai Sesi Pendaftaran</h1>
        </div>
        <div class="row d-flex justify-content-center align-items-center mb-5">
            <div class="row d-flex justify-content-center align-items-center col-12 col-lg-10">
                <div class="row d-flex justify-content-center align-content-center mt-3">
                    <form action="" method="get" class="row">
                        <div class="row row-cols-1 row-cols-md-2">
                            <div class="col mb-2">
                                <div class="form-floating">
                                    <select class="form-select" id="sort_by" name="sort_by" aria-label="sortby">
                                        <option value="course_set_id">ID Set Kursus</option>
                                        <option value="study_level">Kod Tahap Pengajian</option>
                                        <option value="program">Kod Program</option>
                                        <option value="semester">Semester</option>
                                        <option value="status">Status</option>
                                        <option value="session">Sesi</option>
                                        <option value="year">Tahun</option>
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
                                    @if($filterAndSearch['sortBy'] == 'course_set_id')
                                        <p>Susun Mengikut: <span class="fst-italic">ID Set Kursus</span></p>
                                    @elseif($filterAndSearch['sortBy'] == 'study_level')
                                        <p>Susun Mengikut: <span class="fst-italic">Kod Tahap Pengajian</span></p>
                                    @elseif($filterAndSearch['sortBy'] == 'program')
                                        <p>Susun Mengikut: <span class="fst-italic">Kod Program</span></p>
                                    @elseif($filterAndSearch['sortBy'] == 'semester')
                                        <p>Susun Mengikut: <span class="fst-italic">Semester</span></p>
                                    @elseif($filterAndSearch['sortBy'] == 'status')
                                        <p>Susun Mengikut: <span class="fst-italic">Status</span></p>
                                    @elseif($filterAndSearch['sortBy'] == 'session')
                                        <p>Susun Mengikut: <span class="fst-italic">Sesi</span></p>
                                    @elseif($filterAndSearch['sortBy'] == 'year')
                                        <p>Susun Mengikut: <span class="fst-italic">Tahun</span></p>
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
                @if($semesterSessions->count() < 1)
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
                                    <th class="col-2 align-middle">ID SET KURSUS</th>
                                    <th class="col-3 align-middle">KOD TAHAP PENGAJIAN</th>
                                    <th class="col-2 align-middle">KOD PROGRAM</th>
                                    <th class="col-1 align-middle">SEMESTER</th>
                                    <th class="col-1 align-middle">SESI/TAHUN</th>
                                    <th class="col-1 align-middle">STATUS</th>
                                    <th class="col-1 align-middle">KEMAS KINI</th>
                                    <th class="col-1 align-middle">BUANG</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($semesterSessions as $ss)
                                    <tr>
                                        <td>{{ strtoupper($ss->course_sets_id) }}</td>
                                        <td>{{ strtoupper($ss->study_levels_code) }}</td>
                                        <td>{{ strtoupper($ss->programs_code) }}</td>
                                        <td>{{ strtoupper($ss->semester) }}</td>
                                        <td>{{ strtoupper($ss->session) }}/{{ strtoupper($ss->year) }}</td>
                                        <td>
                                            @if($ss->status == 'open')
                                                <p class="btn btn-success text-light w-100">Buka</p>
                                            @elseif($ss->status == 'close')
                                                <p class="btn btn-danger text-light w-100">Tutup</p>
                                        @endif
                                        </td>
                                        <td>
                                            <a class="btn btn-primary hvr-shrink" href="{{ route('admin.semester.registration.update', ['id' => $ss->id]) }}"><i class="bi bi-pencil-square"></i></a>
                                        </td>
                                        <td>
                                            <!-- Delete Static Backdrop Confirmation -->
                                            {{-- @php
                                                $deleteFormData = [array("nameAttr" => "code", "valueAttr" => strtolower($p->code))];
                                            @endphp
                                            <x-delete-confirmation name="program" :formData="$deleteFormData" :increment="$i"/>
                                            <x-delete-confirmation-button :increment="$i"/> --}}
                                        </td>
                                    </tr>
                                    @php
                                        $i = $i + 1;
                                    @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{-- {{ $semesterSessions->links() }} --}}
                @endif
            </div>
        </div>
    </div>
@endsection
