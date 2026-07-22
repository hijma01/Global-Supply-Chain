@extends('layouts.guest')

@section('title', 'Daftar')

@section('content')
    <h1>Buat akun baru</h1>

    <div id="alertBox" class="alert alert-danger py-2 px-3 mb-3" style="display:none;" role="alert"></div>

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
            <input
                type="password"
                class="form-control"
                id="password"
                name="password"
                placeholder="Minimal 6 karakter"
                minlength="6"
                required>
        </div>

        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" id="daftarSebagaiAdmin">
            <label class="form-check-label" for="daftarSebagaiAdmin">
                Daftar sebagai Admin
            </label>
        </div>

        <div class="mb-3" id="kolomKodeAdmin" style="display:none;">
            <label for="kodeAdmin" class="form-label">Kode Admin</label>
            <input
                type="text"
                class="form-control"
                id="kodeAdmin"
                placeholder="Masukkan kode admin">
        </div>

        <button type="submit" class="btn btn-primary" id="btnSubmit">
            <span id="btnText">Daftar</span>
            <span
                id="btnSpinner"
                class="spinner-border spinner-border-sm ms-1"
                style="display:none;">
            </span>
        </button>
    </form>

    <div class="auth-switch mt-3">
        Sudah punya akun?
        <a href="{{ url('/login') }}">Masuk di sini</a>
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
const kodeAdminInput = document.getElementById('kodeAdmin');

checkboxAdmin.addEventListener('change', function () {
    if (this.checked) {
        kolomKodeAdmin.style.display = 'block';
    } else {
        kolomKodeAdmin.style.display = 'none';
        kodeAdminInput.value = '';
    }
});

formRegister.addEventListener('submit', async function (e) {
    e.preventDefault();

    alertBox.style.display = 'none';
    alertBox.textContent = '';

    const nama = document.getElementById('nama').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const kodeAdmin = checkboxAdmin.checked
        ? kodeAdminInput.value.trim()
        : null;

    if (!nama || !email || !password) {
        alertBox.textContent = 'Semua field wajib diisi.';
        alertBox.style.display = 'block';
        return;
    }

    if (password.length < 6) {
        alertBox.textContent = 'Password minimal 6 karakter.';
        alertBox.style.display = 'block';
        return;
    }

    if (checkboxAdmin.checked && kodeAdmin === '') {
        alertBox.textContent = 'Kode admin wajib diisi.';
        alertBox.style.display = 'block';
        return;
    }

    btnSubmit.disabled = true;
    btnText.textContent = 'Memproses...';
    btnSpinner.style.display = 'inline-block';

    try {

        const response = await fetch('/api/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                nama: nama,
                email: email,
                password: password,
                kode_admin: kodeAdmin
            })
        });

        const data = await response.json();

        if (!response.ok) {
            let pesan = 'Pendaftaran gagal.';

            if (data.message) {
                pesan = data.message;
            }

            if (data.errors) {
                pesan = Object.values(data.errors).flat().join('\n');
            }

            throw new Error(pesan);
        }

        alert('Pendaftaran berhasil. Silakan login.');

    
        window.location.href = '/login';

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