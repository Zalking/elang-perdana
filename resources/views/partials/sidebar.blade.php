<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('dashboard') }}" class="brand-link">
        <i class="fas fa-feather-alt brand-icon"></i>
        <span class="brand-text font-weight-light">Elang Perdana</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <i class="fas fa-user-circle img-circle elevation-2"></i>
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->name }}</a>
                <small class="text-success">{{ ucfirst(Auth::user()->role) }}</small>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Menu Penjualan -->
                <li class="nav-item {{ request()->routeIs('penjualan.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('penjualan.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cash-register"></i>
                        <p>
                            Penjualan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('penjualan.dashboard') }}" class="nav-link {{ request()->routeIs('penjualan.dashboard') ? 'active' : '' }}">
                                <i class="fas fa-chart-line nav-icon"></i>
                                <p>Dashboard Penjualan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('penjualan.index') }}" class="nav-link {{ request()->routeIs('penjualan.index') ? 'active' : '' }}">
                                <i class="fas fa-list nav-icon"></i>
                                <p>Data Penjualan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('penjualan.create') }}" class="nav-link {{ request()->routeIs('penjualan.create') ? 'active' : '' }}">
                                <i class="fas fa-plus nav-icon"></i>
                                <p>Tambah Penjualan</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Menu Stok Ban -->
                <li class="nav-item {{ request()->routeIs('stokban.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('stokban.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tire"></i>
                        <p>
                            Stok Ban
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('stokban.dashboard') }}" class="nav-link {{ request()->routeIs('stokban.dashboard') ? 'active' : '' }}">
                                <i class="fas fa-chart-pie nav-icon"></i>
                                <p>Dashboard Ban</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('stokban.index') }}" class="nav-link {{ request()->routeIs('stokban.index') ? 'active' : '' }}">
                                <i class="fas fa-list nav-icon"></i>
                                <p>Data Stok Ban</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('stokban.create') }}" class="nav-link {{ request()->routeIs('stokban.create') ? 'active' : '' }}">
                                <i class="fas fa-plus nav-icon"></i>
                                <p>Tambah Stok Ban</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Menu Hutang Retail -->
                <li class="nav-item {{ request()->routeIs('hutangretail.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('hutangretail.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>
                            Hutang Retail
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('hutangretail.dashboard') }}" class="nav-link {{ request()->routeIs('hutangretail.dashboard') ? 'active' : '' }}">
                                <i class="fas fa-chart-bar nav-icon"></i>
                                <p>Dashboard Hutang</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('hutangretail.index') }}" class="nav-link {{ request()->routeIs('hutangretail.index') ? 'active' : '' }}">
                                <i class="fas fa-list nav-icon"></i>
                                <p>Data Hutang</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('hutangretail.create') }}" class="nav-link {{ request()->routeIs('hutangretail.create') ? 'active' : '' }}">
                                <i class="fas fa-plus nav-icon"></i>
                                <p>Tambah Hutang</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('laporan.index') }}" class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Laporan</p>
                    </a>
                </li>

                @if(Auth::user()->role === 'superadmin')
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Manajemen User</p>
                    </a>
                </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>