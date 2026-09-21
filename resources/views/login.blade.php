@extends('layouts.main')

@section('container')
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="container rounded p-3" style="max-width: 900px;">
            <div class="row rounded shadow d-flex justify-content-center flex-wrap">
                <div class="col rounded-start" style="padding: 50px; background-color: #fbcd70;">
                    <p class="text-center mb-5" style="color:  #5a70f9;"><b>BATTLE OF BRAIN</b>
                        <br>
                        <small class="text-center text-secondary">Berusaha dan Jadilah Juara</small>
                    </p>

                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label" style="color:  #5a70f9;">Email</label>
                            <input type="text" name="email" class="form-control" id="email"
                                placeholder="Masukkan Email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label" style="color:  #5a70f9;">Password</label>
                            <input type="password" name="password" class="form-control" id="password"
                                placeholder="Masukkan password" required>
                        </div>
                        @if ($errors->has('error'))
                            <div class="alert alert-danger text-center">
                                {{ $errors->first('error') }}
                            </div>
                        @endif

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn text-light"
                                style="background-color:  #5a70f9;">Masuk</button>
                        </div>
                    </form>
                </div>
                <div class="col bg-light rounded-end text-center d-flex align-items-center justify-content-center"
                    style="padding: 50px">
                    <div>
                        <img src="images/logo.png" alt="" width="200px">
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="login-box">
        <div class="row g-0">
            <div class="col-md-6 login-image" style="background-image: url('img.jpg');"></div>
            <div class="col-md-6 p-4">
                <h2 class="text-center mb-4">Login</h2>
                <form>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" placeholder="Masukkan username">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Masukkan password">
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}
@endsection
