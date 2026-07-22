<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            background:#f4f6f9;
            font-family:'Segoe UI',sans-serif;
        }

        .wrapper{
            display:flex;
            min-height:100vh;
        }

        /* =======================
            SIDEBAR
        ========================== */

        .sidebar{

            width:260px;
            background:#1E3A8A;
            color:#fff;
            display:flex;
            flex-direction:column;
            box-shadow:3px 0 10px rgba(0,0,0,.1);

        }

        .sidebar-header{

            text-align:center;
            padding:30px 20px;
            border-bottom:1px solid rgba(255,255,255,.15);

        }

        .sidebar-header i{

            font-size:45px;

        }

        .sidebar-header h5{

            margin-top:10px;
            margin-bottom:0;
            font-weight:bold;

        }

        .sidebar-header small{

            color:#dbeafe;

        }

        .sidebar-menu{

            margin-top:20px;

        }

        .sidebar-menu a{

            display:flex;
            align-items:center;
            gap:12px;

            color:white;
            text-decoration:none;

            padding:15px 25px;

            transition:.3s;

        }

        .sidebar-menu a:hover{

            background:#2563EB;
            padding-left:35px;

        }

        .sidebar-menu .active{

            background:#2563EB;
            border-left:5px solid #fff;

        }

        .sidebar-menu i{

            font-size:18px;

        }

        .logout{

            margin-top:auto;
            border-top:1px solid rgba(255,255,255,.15);

        }

        .content{

            flex:1;
            padding:30px;

        }

        .header{

            background:#fff;

            border-radius:15px;

            padding:20px 30px;

            display:flex;
            justify-content:space-between;
            align-items:center;

            box-shadow:0 3px 10px rgba(0,0,0,.08);

            margin-bottom:30px;

        }

        .header h3{

            font-weight:bold;
            margin-bottom:5px;

        }

        .header small{

            color:#777;

        }

    </style>

</head>

<body>

<div class="wrapper">

    <!-- Sidebar -->

    <div class="sidebar">

        <div class="sidebar-header">

            <i class="bi bi-cloud-sun-fill"></i>

            <h5>ADMIN</h5>

            <small>Sistem Monitoring Global Supply Chain</small>

        </div>

        <div class="sidebar-menu">

            <a href="{{ route('admin.dashboard') }}">
                <i class="bi bi-speedometer2"></i>
                Dashboard
            </a>

            <a href="{{ route('admin.pengguna.index') }}">
                <i class="bi bi-people-fill"></i>
                Pengguna
            </a>

            <a href="{{ route('admin.pelabuhan.index') }}">
                <i class="bi bi-geo-alt-fill"></i>
                Pelabuhan
            </a>

            <a href="{{ route('admin.berita.index') }}">
                <i class="bi bi-newspaper"></i>
                Berita
            </a>

        </div>

        <div class="logout">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" style="background:none;border:none;color:white;display:flex;align-items:center;gap:12px;padding:15px 25px;width:100%;text-align:left;">
                    <i class="bi bi-box-arrow-right"></i>
                    Logout
                </button>
            </form>
        </div>

    </div>

    <!-- Content -->

    <div class="content">

        <div class="header">

            <div>

                <h3>Sistem Monitoring Global Supply Chain</h3>

                <small>Dashboard Administrator</small>

            </div>

            <div class="text-end">

                <strong>

                    <i class="bi bi-person-circle"></i>

                    Admin

                </strong>

                <br>

                <small>{{ now()->format('d F Y') }}</small>

            </div>

        </div>

        @yield('content')

    </div>

</div>

</body>

</html>