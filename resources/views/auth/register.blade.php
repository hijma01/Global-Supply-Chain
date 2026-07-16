@extends('layouts.guest')

@section('title', 'Daftar')

@section('content')
    <h1>Buat akun baru</h1>
    
    <div id="alertBox" class="alert alert-danger py-2 px-3 mb-3" role="alert"></div>

    <form id="formRegister" novalidate>
        <div class="mb-3">
            <label for="nama" class="form-label">Username</label>
            <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama kamu" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="nama@email.com" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Kata sandi</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Minimal 6 karakter" minlength="6" required>
        </div>

        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" id="daftarSebagaiAdmin">
            <label class="form-check-label" for="daftarSebagaiAdmin" style="font-size:0.85rem;">
                Daftar sebagai Admin
            </label>
        </div>

        <div class="mb-3" id="kolomKodeAdmin" style="display:none;">
            <label for="kodeAdmin" class="form-label">Kode admin</label>
            <input type="text" class="form-control" id="kodeAdmin" name="kodeAdmin" placeholder="Masukkan kode rahasia admin">
        </div>

        <button type="submit" class="btn btn-primary" id="btnSubmit">
            <span id="btnText">Daftar</span>
            <span id="btnSpinner" class="spinner-border spinner-border-sm ms-1" style="display:none;"></span>
        </button>
    </form>

    <div class="auth-switch">
        Sudah punya akun? <a href="{{ url('/login') }}">Masuk di sini</a>
    </div>
@endsection

@section('scripts')
<script>
    const formRegister = document.getElementById('formRegister');
    const alertBox = document.getElementById('alertBox');
    const btnSubmit = document.getElementById('btnSubmit');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');
    const checkboxAdmin = document.getElementById('daftarSebagaiAdmin');
    const kolomKodeAdmin = document.getElementById('kolomKodeAdmin');

    checkboxAdmin.addEventListener('change', function () {
        kolomKodeAdmin.style.display = this.checked ? 'block' : 'none';
        if (!this.checked) {
            document.getElementById('kodeAdmin').value = '';
        }
    });

    formRegister.addEventListener('submit', async function (e) {
        e.preventDefault();

        alertBox.style.display = 'none';
        btnSubmit.disabled = true;
        btnText.textContent = 'Memproses...';
        btnSpinner.style.display = 'inline-block';

        const nama = document.getElementById('nama').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const kodeAdmin = checkboxAdmin.checked
            ? document.getElementById('kodeAdmin').value.trim()
            : null;

        try {
            const response = await fetch('/api/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ nama, email, password, kode_admin: kodeAdmin }),
            });

            const data = await response.json();

            if (!response.ok) {
                const pesan = data.message || (data.errors && Object.values(data.errors)[0][0]) || 'Pendaftaran gagal, cek kembali data kamu.';
                throw new Error(pesan);
            }

            localStorage.setItem('token', data.token);
            localStorage.setItem('pengguna', JSON.stringify(data.pengguna));

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
            btnText.textContent = 'Daftar';
            btnSpinner.style.display = 'none';
        }
    });
</script>
@endsection