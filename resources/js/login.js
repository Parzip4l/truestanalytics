$.ajax({
    url: 'https://hris.truest.co.id/api/v1/login',
    type: 'POST',
    contentType: 'application/json',
    data: JSON.stringify({
        email: email,
        password: password
    }),
    beforeSend: function(xhr) {
        let token = localStorage.getItem('token');
        if (token) {
            xhr.setRequestHeader('Authorization', 'Bearer ' + token);
        }
    },
    success: function(response) {
        // Code penanganan respons di sini
    },
    error: function(xhr) {
        // Code penanganan kesalahan di sini
    }
});
