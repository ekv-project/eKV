@extends('dashboard.layout.admin')
@section('content')
    <div class="w-100 h-100 mt-3">
        <div class="row text-center">
            <h1>Senarai Kursus</h1>
        </div>
        <div class="row d-flex justify-content-center align-items-center mb-5">
            <div class="row d-flex justify-content-center align-items-center col-12 col-lg-10">
                <div class="row d-flex justify-content-center align-content-center mt-3">
                    <form action="" method="get" class="row">
                        <div class="row row-cols-1 row-cols-md-2">
                            <div class="col mb-2">
                                <div class="form-floating">
                                    <select class="form-select" id="sort_by" name="sort_by" aria-label="sortby">
                                        <option value="code">Kod Kursus</option>
                                        <option value="name">Nama Kursus</option>
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
                @if($course->count() < 1)
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
                                    <th class="col-2">KOD KURSUS</th>
                                    <th class="col-3">NAMA KURSUS</th>
                                    <th class="col-1">JAM KREDIT</th>
                                    <th class="col-1">JAM PERTEMUAN</th>
                                    <th class="col-3">KATEGORI</th>
                                    <th class="col-2">KEMAS KINI</th>
                                    <th class="col-1">BUANG</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($course as $c)
                                    <tr>
                                        <td>{{ strtoupper($c->code) }}</td>
                                        <td>{{ strtoupper($c->name) }}</td>
                                        <td>{{ strtoupper($c->credit_hour) }}</td>
                                        <td>{{ strtoupper($c->total_hour) }}</td>
                                        {{-- 1 = Pengajian Umum
                                        2 = Teras
                                        3 = Pengkhususan
                                        4 = Elektif
                                        5 = On-The-Job Training --}}
                                        <td>
                                            @if(!empty($c->category))
                                                @switch($c->category)
                                                    @case(1)
                                                        PENGAJIAN UMUM
                                                        @break
                                                    @case(2)
                                                        TERAS
                                                        @break
                                                    @case(3)
                                                        PENGKHUSUSAN
                                                        @break
                                                    @case(4)
                                                        ELEKTIF
                                                        @break
                                                    @case(5)
                                                        ON-THE-JOB TRAINING
                                                        @break
                                                    @default
                                                        @break
                                                @endswitch
                                            @endif
                                        </td>
                                        <td><a class="btn btn-primary hvr-shrink" href="{{ route('admin.course.update', ['code' => strtolower($c->code)]) }}"><i class="bi bi-pencil-square"></i></a></td>
                                        <td>
                                            <!-- Delete Static Backdrop Confirmation -->
                                            @php
                                                $deleteFormData = [array("nameAttr" => "code", "valueAttr" => strtolower($c->code))];
                                            @endphp
                                            <x-delete-confirmation name="kursus" :formData="$deleteFormData" :increment="$i"/>
                                            <x-delete-confirmation-button :increment="$i"/>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $course->links() }}
                @endif
            </div>
        </div>
    </div>
@endsection
