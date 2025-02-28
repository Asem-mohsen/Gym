<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <img src="{{ $site->getFirstMediaUrl('gym_logo') }}" alt="Smarven Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">{{$site->gym_name}}</span> 
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('assets/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="{{ route('admins.show' ,  Auth::guard('web')->user()->id)}}" class="d-block">
                    <p>{{ Auth::guard('web')->user()->name }}</p>
                </a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                    aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                {{-- Dashboard --}}
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                {{-- Website --}}
                <li class="nav-item">
                    <a href="{{ config('app.frontend_url') }}" target="_blank" class="nav-link ">
                        <i class="nav-icon fa-solid fa-desktop"></i>
                        <p>
                            Website
                        </p>
                    </a>
                </li>
                {{-- Admins --}}
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa-solid fa-user-tie"></i>
                        <p>
                            Admins
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admins.index') }}"
                                class="nav-link {{ request()->routeIs('admins.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Admins</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('roles.index') }}"
                                class="nav-link {{ request()->routeIs('roles.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Roles</p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- Users --}}
                <li class="nav-item">
                    <a href="{{ route('users.index') }}"
                        class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
                        <i class="nav-icon fa-solid fa-users"></i>
                        <p>
                            Users
                        </p>
                    </a>
                </li>
                {{-- Subscriptions --}}
                <li class="nav-item">
                    <a href="{{ route('subscriptions.index') }}"
                        class="nav-link {{ request()->routeIs('subscriptions.index') ? 'active' : '' }}">
                        <i class="nav-icon fa-solid fa-users"></i>
                        <p>
                            Subscriptions
                        </p>
                    </a>
                </li>
                {{-- Branches --}}
                <li class="nav-item">
                    <a href="{{ route('branches.index') }}"
                        class="nav-link {{ request()->routeIs('branches.index') ? 'active' : '' }}">
                        <i class="nav-icon fa-solid fa-users"></i>
                        <p>
                            Branches
                        </p>
                    </a>
                </li>

                 {{-- Lockers --}}
                 <li class="nav-item">
                    <a href="{{ route('lockers.index') }}"
                        class="nav-link {{ request()->routeIs('lockers.index') ? 'active' : '' }}">
                        <i class="nav-icon fa-solid fa-users"></i>
                        <p>
                            Lockers
                        </p>
                    </a>
                </li>

                {{-- Machines --}}
                <li class="nav-item">
                    <a href="{{ route('machines.index') }}"
                        class="nav-link {{ request()->routeIs('machines.index') ? 'active' : '' }}">
                        <i class="nav-icon fa-solid fa-users"></i>
                        <p>
                            Machines
                        </p>
                    </a>
                </li>

                {{-- Products --}}
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa-solid fa-box-open"></i>
                        <p>
                            Services
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('services.index') }}"
                                class="nav-link {{ request()->routeIs('services.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Services</p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- Offers --}}
                <li class="nav-item">
                    <a href="{{ route('offers.index') }}"
                        class="nav-link {{ request()->routeIs('offers.index') ? 'active' : '' }}">
                        <i class="nav-icon fa-solid fa-money-bill-1-wave"></i>
                        <p>
                            Offers
                        </p>
                    </a>
                </li>
                {{-- Payments --}}
                <li class="nav-item">
                    <a href="{{ route('payments.index') }}"
                        class="nav-link {{ request()->routeIs('payments.index') ? 'active' : '' }}">
                        <i class="nav-icon fa-solid fa-money-bill-1-wave"></i>
                        <p>
                            Payments
                        </p>
                    </a>
                </li>
                {{-- Memberships --}}
                <li class="nav-item">
                    <a href="{{ route('membership.index') }}"
                        class="nav-link {{ request()->routeIs('membership.index') ? 'active' : '' }}">
                        <i class="nav-icon fa-solid fa-money-bill-1-wave"></i>
                        <p>
                            Memberships
                        </p>
                    </a>
                </li>

                <li class="nav-header">Others</li>

                {{-- Site Settings --}}
                <li class="nav-item">
                    <a href="{{ route('site-settings.edit', $site?->id) }}"
                        class="nav-link {{ request()->routeIs('site-settings.edit') ? 'active' : '' }}">
                        <i class="nav-icon fa-solid fa-address-card"></i>
                        <p>Site Settings</p>
                    </a>
                </li>

                {{-- about --}}
                <li class="nav-item">
                    <a href="{{ route('admin.about.index') }}"
                        class="nav-link {{ request()->routeIs('admin.about.index') ? 'active' : '' }}">
                        <i class="nav-icon fa-solid fa-address-card"></i>
                        <p>
                            About
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
