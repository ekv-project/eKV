@extends('dashboard.layout.main')
@section('content')
<div class="container col-8">
    <form action="" method="post" class="mt-5">
        <div class="row">
            <label for="studentID" class="form-label">ID Pelajar</label>
        </div>
        <div class="row">
            <div class="col-10">
                <input type="text" name="studentID" class="form-control" id="searchInput">
                <ul class="list-group hover" id="searchResult"></ul>
            </div>
            <div class="col-2">
                <button class="btn btn-primary">Tambah</button>
            </div>
        </div>
    </form>
    <table class="table table-hover table-bordered border-secondary text-center mt-3">
        <thead>
          <tr>
            <th class="col-2">ID Pelajar</th>
            <th class="col-3">Nama Pelajar</th>
            <th class="col-1">Buang</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($students as $student)
            <tr>
                <td>{{ $student->user->username }}</td>
                <td>{{ $student->user->fullname }} </td>
                <td>
                    <form action="" method="post"  class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-danger">X</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
      </table>
</div>
@endsection