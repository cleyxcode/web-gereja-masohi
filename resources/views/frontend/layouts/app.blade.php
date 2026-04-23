<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Sistem Informasi Gereja Bethesda</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#3c83f6",
                        "primary-dark": "#2563eb",
                        "secondary": "#10b981",
                        "danger": "#ef4444",
                        "background-light": "#f5f7f8",
                        "background-dark": "#101722",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: { 
                        "DEFAULT": "0.25rem", 
                        "lg": "0.5rem", 
                        "xl": "0.75rem", 
                        "full": "9999px" 
                    },
                },
            },
        }
    </script>
    
    <script>
        // Global SweetAlert2 Toast Definition
        window.showToast = function(message, type = 'success') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: type,
                title: message
            });
        };
        
        window.showConfirm = async function(title, text, icon = 'warning') {
            const result = await Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: '#3c83f6',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            });
            return result.isConfirmed;
        };
    </script>

    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
    @stack('styles')
</head>
<body class="bg-background-light text-[#111418] font-display antialiased min-h-screen flex flex-col">
    
    {{-- Navbar tampil untuk semua (auth check sudah ada di dalam navbar.blade.php) --}}
    @include('frontend.layouts.navbar')
    
    @yield('content')
    
    {{-- Footer tampil untuk semua --}}
    @include('frontend.layouts.footer')
    
    <script src="{{ asset('frontend/js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>