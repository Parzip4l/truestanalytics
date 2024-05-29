<!-- resources/views/pages/auth/login.blade.php -->

@extends('layout.master2')

@section('content')
<div class="page-content d-flex align-items-center justify-content-center">
  <div class="row w-100 mx-0 auth-page">
    <div class="col-md-4 col-xl-4 mx-auto">
      <div class="card">
        <div class="row">
          <div class="col-md-12 ps-md-0">
            <div class="auth-form-wrapper px-4 py-5">
              <a href="#" class="noble-ui-logo d-block mb-2 text-center">TRUE<span>ST</span></a>
              <h5 class="text-muted fw-normal mb-4 text-center">Analytics Apps</h5>

              <!-- Menampilkan pesan kesalahan jika terjadi -->
              @if ($errors->any())
              <div class="alert alert-danger" role="alert">
                @foreach ($errors->all() as $error)
                {{ $error }}<br>
                @endforeach
              </div>
              @endif

              <!-- Menampilkan pesan sukses jika ada -->
              @if(session('status'))
              <div class="alert alert-success" role="alert">
                {{ session('status') }}
              </div>
              @endif

              <form method="POST" action="{{ route('login.proses') }}">
                @csrf
                <div class="mb-3">
                  <label for="email" class="form-label">Email address</label>
                  <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="mb-3">
                  <label for="password" class="form-label">Password</label>
                  <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                </div>
                <div>
                  <button type="submit" class="btn btn-primary w-100">Login</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
