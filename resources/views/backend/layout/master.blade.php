<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <link
            rel="icon"
            type="image/png"
            sizes="16x16"
            href="{{ asset('backend_assets/assets/images/setec_logo.png') }}"
        />
        <title>SETEC-INSTITUTE-LOAN</title>

        @include('backend.layout.style') @stack('styles')
    </head>

    <body>
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>
        <div
            id="main-wrapper"
            data-theme="light"
            data-layout="vertical"
            data-navbarbg="skin6"
            data-sidebartype="full"
            data-sidebar-position="fixed"
            data-header-position="fixed"
            data-boxed-layout="full"
        >
            <!-- Header -->
            @include('backend.layout.header')
            <!-- Left side bar -->
            @include('backend.layout.leftsidebar')
            <!-- Dynamic Content -->
            @yield('contents')
        </div>
        <!-- Footer -->
        @include('backend.layout.footer')
        <!-- Script -->
        @include('backend.layout.script') @stack('scripts')
    </body>
</html>
