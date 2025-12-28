document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Mencegah form dari submit default

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const errorMessage = document.getElementById('error-message');

    // Cek username dan password
    if (username === 'admin' && password === 'kultivasi') {
        // Set session storage untuk menandakan pengguna sudah login
        sessionStorage.setItem('authenticated', 'true');
        // Arahkan ke halaman home
        window.location.href = 'home.html';
    } else {
        errorMessage.innerText = 'Username atau Password salah';
    }
});


