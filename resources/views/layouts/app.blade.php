<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <!-- SweetAlert2 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
        <!-- Animate.css for animations -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
        @stack('owl-carousel-css')
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        @stack('swiper-css')
        @stack('styles')
    </head>
    <body style="background: #fff;" class="font-sans antialiased">
    <!-- Page Loader Overlay -->
    <div id="page-loader" style="position:fixed;z-index:2000;top:0;left:0;width:100vw;height:100vh;background:#f8f9fa;display:flex;align-items:center;justify-content:center;transition:opacity 0.5s;">
        <div class="spinner-border" style="width:3rem;height:3rem;color:#fff;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
        <div class="min-h-screen bg-gray-100">

            @include('layouts.partials.header')

            <!-- Flash Messages -->
            @if(session('welcome'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Welcome Back!',
                            html: `
                                <div style="text-align: center; padding: 20px;">
                                    <img src="{{ asset('logo/logo.png') }}" alt="Logo" style="width: 180px; height: auto; margin-bottom: 20px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); object-fit: contain;">
                                    <h3 style="color: #c52b36; margin-bottom: 10px; font-size: 1.5rem;">{{ session('welcome') }}</h3>
                                    <p style="color: #6c757d; margin: 0; font-size: 1rem;">You have successfully logged in</p>
                                </div>
                            `,
                            showConfirmButton: false,
                            timer: 4000,
                            toast: false,
                            position: 'center',
                            background: '#ffffff',
                            color: '#333',
                            width: '500px',
                            padding: '25px',
                            borderRadius: '15px',
                            showClass: {
                                popup: 'animate__animated animate__fadeInDown'
                            },
                            hideClass: {
                                popup: 'animate__animated animate__fadeOutUp'
                            }
                        });
                    });
                </script>
            @endif
            @if(session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: '{{ session('success') }}',
                            showConfirmButton: false,
                            timer: 3000,
                            toast: true,
                            position: 'top-end',
                            background: '#d4edda',
                            color: '#155724'
                        });
                    });
                </script>
            @endif
            @if(session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: '{{ session('error') }}',
                            showConfirmButton: false,
                            timer: 5000,
                            toast: true,
                            position: 'top-end',
                            background: '#f8d7da',
                            color: '#721c24'
                        });
                    });
                </script>
            @endif
            @if($errors->any())
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var errorMessages = @json($errors->all());
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            html: errorMessages.join('<br>'),
                            showConfirmButton: true,
                            confirmButtonColor: '#dc3545',
                            background: '#f8d7da',
                            color: '#721c24'
                        });
                    });
                </script>
            @endif
            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
            @include('layouts.partials.footer')
        </div>

        {{-- Floating Components - Show only on non-admin pages --}}
        @if(!request()->is('admin*') && !request()->is('auth/admin*'))
            @include('components.floating-buttons')
            @include('components.floating-discounts')
        @endif

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- SweetAlert2 JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- Custom Alert Functions -->
        <script src="{{ asset('js/alerts.js') }}"></script>
        @stack('owl-carousel-js')
        @stack('swiper-js')
        @livewireScripts
        <script>
            // Page Loader Fade Out
            window.addEventListener('load', function() {
                var loader = document.getElementById('page-loader');
                if(loader){
                    loader.style.opacity = '0';
                    setTimeout(function(){ loader.style.display = 'none'; }, 500);
                }
            });
        </script>
    </body>
</html>
