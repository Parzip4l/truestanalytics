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
              <form class="forms-sample" id="login-form">
                <div class="mb-3">
                  <label for="userEmail" class="form-label">Email address</label>
                  <input type="email" class="form-control" id="email" placeholder="Email">
                </div>
                <div class="mb-3">
                  <label for="userPassword" class="form-label">Password</label>
                  <input type="password" class="form-control" id="password" autocomplete="current-password" placeholder="Password">
                  <div class="invalid-feedback" id="error" style="display: none;"></div> <!-- Notifikasi error -->
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

@push('custom-scripts')
<script>
    $(document).ready(function() {
        $('#login-form').on('submit', function(e) {
            e.preventDefault();
            let email = $('#email').val();
            let password = $('#password').val();
            $.ajax({
                url: 'https://hris.truest.co.id/api/v1/login',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    email: email,
                    password: password
                }),
                success: function(response) {
                    if (response.success) { // Periksa apakah login berhasil
                        console.log('Login successful:', response);
                        localStorage.setItem('token', response.token);
                        window.location.href = '/dashboard'; // Arahkan ke halaman dashboard
                    } else {
                        let error = response.message || 'Login failed';
                        $('#error').text(error).show(); // Tampilkan pesan error
                    }
                },
                error: function(xhr) {
                    let error = xhr.responseJSON.message || 'Login failed';
                    $('#error').text(error).show(); // Tampilkan pesan error
                }
            });
        });
    });
</script>
@endpush
