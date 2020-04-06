<aside class="main-sidebar elevation-4 sidebar-light-primary">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="{{ asset('lte/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
        style="opacity: .8">
        <span class="brand-text font-weight-light">IHC</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('lte/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Alexander Pierce</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ url('/home') }}" class="nav-link {{ Request::is('dokter/*') || Request::is('home') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Home
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('home.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-calendar"></i>
                        <p>
                            Jadwal
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('home.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-credit-card"></i>
                        <p>
                            Bayar
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('home.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-user-alt"></i>
                        <p>
                            Profile
                        </p>
                    </a>
                </li>
                <!-- <li class="nav-item has-treeview {{ Request::is('dokter') || Request::is('dokter/*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('dokter') || Request::is('dokter/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-table"></i>
                        <p>
                            Pencarian
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('/dokter') }}" class="nav-link {{ Request::is('dokter') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Doker</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/dokter/search') }}" class="nav-link {{ Request::is('dokter/search') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Search Dokter</p>
                            </a>
                        </li>
                    </ul>
                </li> -->
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
<!-- /.sidebar -->
</aside>
