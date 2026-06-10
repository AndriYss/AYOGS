// 1. Fungsi untuk mengubah teks menjadi hash SHA-1 secara asinkron
async function hashSHA1(pesan) {
    const encoder = new TextEncoder();
    const data = encoder.encode(pesan);
    // Menggunakan Web Crypto API bawaan browser
    const hashBuffer = await crypto.subtle.digest('SHA-1', data);
    const hashArray = Array.from(new Uint8Array(hashBuffer));
    // Mengubah buffer menjadi string heksadesimal
    return hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
}

// 2. Tambahkan kata 'async' pada event listener karena proses hashing membutuhkan waktu (Promise)
document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const user = document.getElementById('username').value;
    const pass = document.getElementById('password').value;

    // Hashing password yang diketik oleh pengguna
    const hashedInput = await hashSHA1(pass);

    // Ganti teks acak di bawah dengan Hash SHA-1 asli dari kata "kultivasi".
    // Gunakan web seperti sha1-online.com untuk menghasilkan hash dari password Anda.
    // Sebagai contoh, hash untuk kata "admin" adalah: d033e22ae348aeb5660fc2140aec35850c4da997
    const validHash = "58caa8d6630dd002310d3968e5cf0f388ed8b849"; 

    if(user === "admin" && hashedInput === validHash) {
        // Simpan tanda login berhasil
        sessionStorage.setItem('isLoggedIn', 'true');
        window.location.href = 'dashboard.html';
    } else {
        document.getElementById('error-message').textContent = "Username atau password salah!";
        console.log("Hash input Anda adalah:", hashedInput); // Hapus baris ini saat sistem sudah live
    }
});
