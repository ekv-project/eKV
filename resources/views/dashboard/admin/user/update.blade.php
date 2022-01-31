@extends('dashboard.layout.admin')
@section('content')
    <div class="w-100 h-100 mt-3">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-12 col-lg-10">
                @if($user->username == "admin")
                    @if(Auth::user()->username != "admin")
                        <p class="fs-3">Anda tidak boleh mengemaskini pengguna ini!</p>
                    @else
                        <form action="" method="post" class="mt-3 mb-5">
                            @csrf
                            <h2 class="text-center">Kemas Kini Pengguna</h2>
                            @if(session()->has('userUpdateSuccess'))
                                <div class="alert alert-success">{{ session('userUpdateSuccess') }}</div>
                            @endif
                            @error('notExisted')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <div class="mb-3">
                                <p class="fs-5">ID Pengguna: <span>{{ strtoupper($user->username) }}</span></p>
                                <input type="hidden" name="username" value="{{ $user->username }}">
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" name="fullname" id="fullname" class="form-control" placeholder="fullname" value="@php if(old('fullname') !== null){echo old('fullname');}elseif(isset($user->fullname)){echo ucwords($user->fullname);}else{echo NULL;} @endphp">
                                <label for="fullname" class="form-label">Nama Pengguna</label>
                                @error('fullname')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" name="email" id="email" class="form-control" placeholder="email" value="@php if(old('email') !== null){echo old('email');}elseif(isset($user->email)){echo $user->email;}else{echo NULL;} @endphp">
                                <label for="emailr" class="form-label">E-mel Pengguna</label>
                                @error('email')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary w-100 hvr-shrink" name="info">Kemas Kini</button>
                        </form>
                    @endif
                @else
                    <form action="" method="post" class="mt-3 mb-5">
                        @csrf
                        <h2 class="text-center">Kemas Kini Pengguna</h2>
                        @if(session()->has('userUpdateSuccess'))
                            <div class="alert alert-success">{{ session('userUpdateSuccess') }}</div>
                        @endif
                        @error('notExisted')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <div class="mb-3">
                            <p class="fs-5">ID Pengguna: <span>{{ strtoupper($user->username) }}</span></p>
                            <input type="hidden" name="username" value="{{ $user->username }}">
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="fullname" id="fullname" class="form-control" placeholder="fullname" value="@php if(old('fullname') !== null){echo old('fullname');}elseif(isset($user->fullname)){echo ucwords($user->fullname);}else{echo NULL;} @endphp">
                            <label for="fullname" class="form-label">Nama Pengguna</label>
                            @error('fullname')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <select name="gender" id="gender" class="form-select mb-1">
                                @switch($user->gender)
                                    @case(0)
                                        <option value="male" selected>Lelaki</option>
                                        <option value="female">Perempuan</option>
                                        <option value="notapplicable">Tidak Berkaitan</option>
                                        @break
                                    @case(1)
                                        <option value="male">Lelaki</option>
                                        <option value="female" selected>Perempuan</option>
                                        <option value="notapplicable">Tidak Berkaitan</option>
                                        @break
                                    @default
                                        <option value="male">Lelaki</option>
                                        <option value="female">Perempuan</option>
                                        <option value="notapplicable" selected>Tidak Berkaitan</option>
                                        @break
                                @endswitch
                            </select>
                            <label for="gender" class="form-label">Jantina</label>
                            @error('gender')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="email" id="email" class="form-control" placeholder="email" value="@php if(old('email') !== null){echo old('email');}elseif(isset($user->email)){echo $user->email;}else{echo NULL;} @endphp">
                            <label for="email" class="form-label">E-mel Pengguna</label>
                            @error('email')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100 hvr-shrink" name="info">Kemas Kini</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
