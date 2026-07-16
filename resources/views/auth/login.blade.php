@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')
    <h1>Masuk ke akun kamu</h1>

    <div id="alertBox" class="alert alert-danger py-2 px-3 mb-3" role="alert"></div>

    <form id="formLogin" novalidate>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="nama@email.com" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Kata sandi</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Minimal 6 karakter" required>
        </div>

        <button type="submit" class="btn btn-primary" id="btnSubmit">
            <span id="btnText">Masuk</span>
            <span id="btnSpinner" class="spinner-border spinner-border-sm ms-1" style="display:none;"></span>
        </button>
    </form>

    <div class="auth-switch">
        Belum punya akun? <a href="{{ url('/register') }}">Daftar di sini</a>
    </div>
@endsection

@section('scripts')
<script>
    const formLogin = document.getElementById('formLogin');
    const alertBox = document.getElementById('alertBox');
    const btnSubmit = document.getElementById('btnSubmit');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');

    formLogin.addEventListener('submit', async function (e) {
        e.preventDefault();

        alertBox.style.display = 'none';
        btnSubmit.disabled = true;
        btnText.textContent = 'Memproses...';
        btnSpinner.style.display = 'inline-block';

        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;

        try {
            const response = await fetch('/api/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ email, password }),
            });

            const data = await response.json();

            if (!response.ok) {
                const pesan = data.message || (data.errors && Object.values(data.errors)[0][0]) || 'Email atau kata sandi salah.';
                throw new Error(pesan);
            }

            // Simpan token dan data pengguna untuk dipakai di halaman dashboard
            localStorage.setItem('token', data.token);
            localStorage.setItem('pengguna', JSON.stringify(data.pengguna));

            // Arahkan sesuai peran (admin atau user biasa)
            if (data.pengguna.peran === 'admin') {
                window.location.href = '/admin/dashboard';
            } else {
                window.location.href = '/dashboard';
            }
        } catch (error) {
            alertBox.textContent = error.message;
            alertBox.style.display = 'block';
        } finally {
            btnSubmit.disabled = false;
            btnText.textContent = 'Masuk';
            btnSpinner.style.display = 'none';
        }
    });
</script>
@endsection