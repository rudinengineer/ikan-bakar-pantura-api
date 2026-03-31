<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="@yield('theme', 'light')" data-color-theme="Blue_Theme" data-layout="vertical">
<head>
    @include('components.partials.head')
    <title>@yield('title', 'Dashboard')</title>
    @yield('css')
</head>
<body class="link-sidebar">
    <!-- Preloader -->
    <div class="preloader">
        <img src="{{ url('assets/images/logos/favicon.png') }}" alt="loader" class="lds-ripple img-fluid" />
    </div>
    <div id="main-wrapper">

        <!-- Sidebar Start -->
        <aside class="left-sidebar with-vertical">
            <div>@include('components.partials.sidebar')</div>
        </aside>
        <!-- Sidebar End -->

        <div class="page-wrapper">
            <!-- Header Start -->
            <header class="topbar">
                <div class="with-vertical">@include('components.partials.header')</div>
                <div class="app-header with-horizontal">@include('components.partials.horizontal-header')</div>
            </header>
            <!-- Header End -->
            
            <aside class="left-sidebar with-horizontal">
                @include('components.partials.horizontal-sidebar')
            </aside>

            <div class="body-wrapper">
                <div class="container-fluid">
                    <div id="app"></div>
                </div>
            </div>
            {{-- @include('components.partials.customizer') --}}
        </div>
    </div>
    <div class="dark-transparent sidebartoggler"></div>
    @include('components.partials.scripts')
    @yield('scripts')
</body>
</html>
