<div class="sidebar p-3 text-white d-flex flex-column">

    <!-- Logo -->
    <div>
        <h4 class="fw-bold mb-4">
            <i class="bi bi-globe2"></i>
            Global SCM
        </h4>

        <ul class="nav flex-column">

            <li class="nav-item mb-2">
                <a href="{{ route('dashboard.negara') }}"
                   class="nav-link {{ request()->routeIs('dashboard.negara') ? 'active' : '' }}">
                    <i class="bi bi-globe"></i>
                    Negara
                </a>
            </li>

            <li class="nav-item mb-2">
                <a href="{{ route('dashboard.cuaca') }}"
                   class="nav-link {{ request()->routeIs('dashboard.cuaca') ? 'active' : '' }}">
                    <i class="bi bi-cloud-sun"></i>
                    Cuaca
                </a>
            </li>

            <li class="nav-item mb-2">
                <a href="{{ route('dashboard.nilai-tukar') }}"
                   class="nav-link {{ request()->routeIs('dashboard.nilai-tukar') ? 'active' : '' }}">
                    <i class="bi bi-currency-exchange"></i>
                    Nilai Tukar
                </a>
            </li>

            <li class="nav-item mb-2">
                <a href="{{ route('berita.index') }}"
                   class="nav-link {{ request()->routeIs('berita.*') ? 'active' : '' }}">
                    <i class="bi bi-newspaper"></i>
                    Berita
                </a>
            </li>

            <li class="nav-item mb-2">
                <a href="{{ route('pelabuhan.index') }}"
                   class="nav-link {{ request()->routeIs('pelabuhan.*') ? 'active' : '' }}">
                    <i class="bi bi-geo-alt-fill"></i>
                    Pelabuhan
                </a>
            </li>

            <li class="nav-item mb-2">
                <a href="{{ route('dashboard.risiko') }}"
                   class="nav-link {{ request()->routeIs('risiko.*') ? 'active' : '' }}">
                    <i class="bi bi-exclamation-triangle"></i>
                    Skor Risiko
                </a>
            </li>

            <li class="nav-item mb-2">
                <a href="{{ route('pantauan.index') }}"
                   class="nav-link {{ request()->routeIs('pantauan.*') ? 'active' : '' }}">
                    <i class="bi bi-bookmark-star"></i>
                    Daftar Pantauan (Favorit)
                </a>
            </li>

            <li class="nav-item mb-2">
                <a href="{{ route('perbandingan.index') }}"
                   class="nav-link {{ request()->routeIs('perbandingan.*') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart-line"></i>
                    Perbandingan
                </a>
            </li>

        </ul>
    </div>

    <!-- Logout -->
    <div class="mt-auto">

        <hr class="border-light opacity-25">

        <form action="{{ route('logout') }}" method="POST">
            @csrf

            <button type="submit" class="btn btn-danger w-100">
                <i class="bi bi-box-arrow-right me-2"></i>
                Logout
            </button>

        </form>

    </div>

</div>