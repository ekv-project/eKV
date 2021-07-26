@extends('dashboard.layout.admin')
@section('content')
@if(isset($apiToken) AND $apiToken != NULL)
<meta name="api-token" content="{{ $apiToken }}">
@endif
<div class="row w-100 d-flex justify-content-center">
    <div class="col-12 col-lg-11 d-flex flex-column justify-content-center">
        <h1 class="text-center fs-2 my-2">Statistik</h1>
        <div class="row">
            <p>Jangka Masa</p>
        </div>
        <div class="row mb-5">
            <div class="col-5 d-flex">
                <select class="form-select form-select-sm me-1" aria-label=".form-select-sm example" id="time-period-select" name="time-period-select">
                    <option value="today">Hari Ini</option>
                    <option value="week">Minggu Ini</option>
                    <option value="month">Bulan Ini</option>
                    <option value="year">Tahun Ini</option>
                </select>
                <button class="btn btn-primary btn-sm" id="time-period-button" name="time-period-button">Kemas Kini</button>
            </div>
        </div>
        <div class="row row-cols-2 row-cols-lg-3 gy-5 mb-5 d-flex justify-content-center">
            <div class="col chart-container">
                <p class="text-center" id="chart1Text"></p>
                <canvas id="chart1"></canvas>
                <p class="text-center mt-3" id="chart1Total"></p>
            </div>
            <div class="col chart-container">
                <p class="text-center" id="chart2Text"></p>
                <canvas id="chart2"></canvas>
                <p class="text-center mt-3" id="chart2Total"></p>
            </div>
            <div class="col chart-container">
                <p class="text-center" id="chart3Text"></p>
                <canvas id="chart3"></canvas>
                <p class="text-center mt-3" id="chart3Total"></p>
            </div>
        </div>
    </div>
</div>
<script type="module" src="{{ asset('js/statistic.js') }}"></script>
@endsection
