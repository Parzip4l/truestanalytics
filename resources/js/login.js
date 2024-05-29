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
                console.log('Login successful:', response);
                localStorage.setItem('token', response.token);
                window.location.href = '/dashboard';
            },
            error: function(xhr) {
                let error = xhr.responseJSON.message || 'Login failed';
                $('#error').text(error).show();
            }
        });
    });
});
