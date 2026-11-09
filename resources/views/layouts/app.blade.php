
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kopi Kita - Sistem Kasir</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
        rel="stylesheet">

    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
            color: #333;
        }

        /* SIDEBAR */
        .sidebar {
            width: 230px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #fff;
            border-right: 1px solid #ddd;
            padding: 25px 20px;
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }

        .sidebar-brand {
            padding-bottom: 30px;
            border-bottom: 1px solid #ccc;
        }

        .sidebar-brand h3 {
            font-size: 25px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .sidebar-brand p {
            font-size: 13px;
            color: #777;
            margin: 0;
        }

        .sidebar-label {
            font-size: 11px;
            font-weight: 700;
            color: #777;
            margin-top: 32px;
            margin-bottom: 12px;
        }

        .sidebar-menu {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 13px 12px;
            color: #333;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            transition: 0.2s;
        }

        .sidebar-menu a:hover {
            background-color: #f0f0f0;
        }

        .sidebar-menu a.active {
            background-color: #dedede;
            font-weight: 700;
            border: 1px solid #ccc;
        }

        .sidebar-menu i {
            font-size: 17px;
        }

        /* INFORMASI GERAI */
        .store-info {
            margin-top: 35px;
            padding: 12px;
            border: 1px solid #ccc;
            background-color: #f8f8f8;
        }

        .store-info small {
            color: #777;
            font-size: 11px;
        }

        .store-info h6 {
            font-size: 14px;
            font-weight: 700;
            margin-top: 10px;
        }

        .store-info p {
            font-size: 12px;
            margin-bottom: 5px;
            color: #666;
        }

        /* SIDEBAR FOOTER */
        .sidebar-footer {
            margin-top: auto;
            padding-top: 15px;
            border-top: 1px solid #ccc;
        }

        .sidebar-footer h6 {
            font-size: 14px;
            font-weight: 700;
        }

        .sidebar-footer p {
            font-size: 12px;
            color: #777;
            margin-bottom: 12px;
        }

        /* MAIN CONTENT */
        .main-wrapper {
            margin-left: 230px;
            min-height: 100vh;
        }

        /* NAVBAR */
        .top-navbar {
            height: 77px;
            background-color: #fff;
            border-bottom: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
        }

        .top-navbar .navbar-title {
            font-size: 14px;
            color: #777;
            margin: 0;
        }

        .top-navbar .shift-info {
            font-size: 13px;
            color: #444;
            margin: 0;
        }

        /* CONTENT */
        .page-content {
            padding: 30px 32px;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .sidebar {
                width: 190px;
                padding: 20px 12px;
            }

            .main-wrapper {
                margin-left: 190px;
            }

            .top-navbar {
                padding: 0 15px;
            }

            .page-content {
                padding: 20px 15px;
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                width: 65px;
                padding: 20px 8px;
            }

            .sidebar-brand h3 {
                font-size: 0;
            }

            .sidebar-brand h3::after {
                content: "KK";
                font-size: 20px;
            }

            .sidebar-brand p,
            .sidebar-label,
            .sidebar-menu span,
            .store-info,
            .sidebar-footer .user-info,
            .sidebar-footer .btn-text {
                display: none;
            }

            .sidebar-menu a {
                justify-content: center;
                padding: 13px 5px;
            }

            .sidebar-menu i {
                font-size: 20px;
            }

            .main-wrapper {
                margin-left: 65px;
            }

            .top-navbar {
                height: 65px;
            }

            .top-navbar .shift-info {
                display: none;
            }
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <!-- BRAND -->
        <div class="sidebar-brand">
            <h3>KOPI KITA</h3>
            <p>Kasir & manajemen penjualan</p>
        </div>

        <!-- MENU NAVIGASI -->
        <div class="sidebar-label">
            RUANG KERJA
        </div>

        <nav class="sidebar-menu">

            <a href="{{ url('/dashboard') }}"
                class="{{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ url('/transaksi') }}"
                class="{{ request()->is('transaksi*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i>
                <span>Transaksi Kasir</span>
            </a>

            <a href="{{ url('/DataMenu') }}"
                class="{{ request()->is('DataMenu') ? 'active' : '' }}">
                <i class="bi bi-cup-hot"></i>
                <span>Data Menu</span>

            </a>

            <a href="{{ url('/kategori') }}"
                class="{{ request()->is('kategori') ? 'active' : '' }}">
                <i class="bi bi-tags"></i>
                <span>Kategori Menu</span>
            </a>

            <a href="{{ url('/laporan') }}"
                class="{{ request()->is('laporan*') ? 'active' : '' }}">
                <i class="bi bi-clipboard-data"></i>
                <span>Laporan & Struk</span>
            </a>

        </nav>


        <!-- FOOTER SIDEBAR -->
        <div class="sidebar-footer">

            <div class="user-info">
                <h6>
                    {{ auth()->user()->name }}
                </h6>

                <p>Admin / Kasir</p>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf

                <button type="submit"
                    class="btn btn-outline-secondary w-100 py-2">

                    <i class="bi bi-box-arrow-right me-1"></i>
                    <span class="btn-text">Keluar</span>

                </button>
            </form>

        </div>

    </aside>


    <!-- MAIN WRAPPER -->
    <div class="main-wrapper">

        <!-- NAVBAR -->
        <nav class="top-navbar">

            <p class="navbar-title">
                Sistem Informasi Kasir & Manajemen Penjualan
            </p>
        </nav>


        <!-- HALAMAN CONTENT -->
        <main class="page-content">

            {{ $slot }}

        </main>

    </div>


    <!-- BOOTSTRAP JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js">
    </script>

</body>
</html>
