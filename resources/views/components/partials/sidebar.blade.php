<!-- ---------------------------------- -->
<!-- Start Vertical Layout Sidebar -->
<!-- ---------------------------------- -->
<div class="brand-logo d-flex align-items-center justify-content-between">
    <a href="{{ url('#dashboard') }}" class="text-nowrap logo-img">
        <img src="{{ url('assets/images/logos/dark-logo.svg') }}" class="dark-logo" alt="Logo-Dark" />
        <img src="{{ url('assets/images/logos/light-logo.svg') }}" class="light-logo" alt="Logo-light" />
    </a>
    <a href="javascript:void(0)" class="sidebartoggler ms-auto text-decoration-none fs-5 d-block d-xl-none">
        <i class="ti ti-x"></i>
    </a>
</div>

<nav class="sidebar-nav scroll-sidebar" data-simplebar>
    <ul id="sidebarnav">
        <!-- ---------------------------------- -->
        <!-- Home -->
        <!-- ---------------------------------- -->
        <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            {{-- <span class="hide-menu">Home</span> --}}
        </li>
        <!-- ---------------------------------- -->
        <!-- Dashboard -->
        <!-- ---------------------------------- -->
        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('#dashboard') }}" aria-expanded="false">
                <span>
                    <i class="ti ti-home"></i>
                </span>
                <span class="hide-menu">Dashboard</span>
            </a>
        </li>

        @if (check_user_access('order', 'read'))
            <li class="sidebar-item">
                <a class="sidebar-link {{ request()->is('/order') ? 'active' : '' }}" href="{{ url('#order') }}" aria-expanded="false">
                    <span>
                        <i class="ti ti-shopping-cart"></i>
                    </span>
                    <span class="hide-menu">Kelola Pesanan</span>
                </a>
            </li>
        @endif
        
        <!-- ---------------------------------- -->
        <!-- Apps -->
        <!-- ---------------------------------- -->
        @php
            $accessCategory = check_user_access('category', 'read');
            $accessMenu = check_user_access('product', 'read');
            $accessPacket = check_user_access('packet', 'read');
            $accessStore = check_user_access('store', 'read');
        @endphp

        @if ($accessCategory || $accessMenu || $accessPacket || $accessStore)
            <li class="nav-small-cap">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu">Master Data</span>
            </li>

            @if ($accessCategory)
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->is('category') ? 'active' : '' }}" href="#category" aria-expanded="false">
                        <span>
                            <i class="ti ti-folder"></i>
                        </span>
                        <span class="hide-menu">Kelola Kategori</span>
                    </a>
                </li>
            @endif

            @if ($accessMenu)
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->is('product') ? 'active' : '' }}" href="#product" aria-expanded="false">
                        <span>
                            <i class="ti ti-tools-kitchen-2"></i>
                        </span>
                        <span class="hide-menu">Kelola Menu</span>
                    </a>
                </li>
            @endif

            @if ($accessPacket)
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->is('packet') ? 'active' : '' }}" href="#packet" aria-expanded="false">
                        <span>
                            <i class="ti ti-list-details"></i>
                        </span>
                        <span class="hide-menu">Kelola Paket</span>
                    </a>
                </li>
            @endif

            @if ($accessStore)
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->is('store') ? 'active' : '' }}" href="#store" aria-expanded="false">
                        <span>
                            <i class="ti ti-building"></i>
                        </span>
                        <span class="hide-menu">Kelola Cabang</span>
                    </a>
                </li>
            @endif
        @endif
        <!-- ---------------------------------- -->
        <!-- PAGES -->
        <!-- ---------------------------------- -->

        @php
            $accessUser = check_user_access('user-management', 'read');
            $accessRole = check_user_access('role', 'read');
            $accessSetting = check_user_access('app-setting', 'read');
            $accessAccessSetting = check_user_access('user-access-item', 'read');
        @endphp

        @if ($accessUser || $accessRole || $accessSetting || $accessAccessSetting)
            <li class="nav-small-cap">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu">LAINNYA</span>
            </li>

            @if ($accessUser || $accessRole)
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                        <span class="d-flex">
                            <i class="ti ti-users"></i>
                        </span>
                        <span class="hide-menu">Kelola Pengguna</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        @if ($accessUser)
                            <li class="sidebar-item">
                                <a class="sidebar-link" href="{{ url('#user-management') }}" aria-expanded="false">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-circle"></i>
                                    </div>
                                    <span class="hide-menu">Semua Pengguna</span>
                                </a>
                            </li>
                        @endif

                        @if ($accessRole)
                            <li class="sidebar-item">
                                <a href="{{ url('#role') }}" class="sidebar-link {{ request()->is('main/blog-detail') ? 'active' : '' }}">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-circle"></i>
                                    </div>
                                    <span class="hide-menu">Peran Pengguna</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if ($accessSetting || $accessAccessSetting)
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                        <span class="d-flex">
                            <i class="ti ti-settings"></i>
                        </span>
                        <span class="hide-menu">Pengaturan</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        @if ($accessSetting || auth()->user()->role->level > 1)
                            <li class="sidebar-item">
                                <a class="sidebar-link" href="{{ url('#app-setting') }}" aria-expanded="false">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-circle"></i>
                                    </div>
                                    <span class="hide-menu">Pengaturan Aplikasi</span>
                                </a>
                            </li>
                        @endif

                        @if ($accessAccessSetting)
                            <li class="sidebar-item">
                                <a href="{{ url('#user-access-item') }}" class="sidebar-link {{ request()->is('main/blog-detail') ? 'active' : '' }}">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-circle"></i>
                                    </div>
                                    <span class="hide-menu">Pengaturan Akses</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        @endif
        
    </ul>
</nav>

<!-- ---------------------------------- -->
<!-- Start Vertical Layout Sidebar -->
<!-- ---------------------------------- -->