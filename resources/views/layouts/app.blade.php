<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REXY CORP - @yield('title', 'Payroll Management')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Import Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f6fb;
        }
    </style>
</head>
<body class="text-slate-800 antialiased flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-slate-200 flex flex-col h-full shrink-0 print:hidden">
        <!-- Logo -->
        <div class="h-16 flex items-center px-6 border-b border-slate-100 mb-4 mt-2">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-blue-900 rounded shadow flex items-center justify-center text-white font-bold text-lg">
                    RC
                </div>
                <div>
                    <div class="font-bold text-lg leading-tight text-slate-900">REXY CORP</div>
                    <div class="text-xs text-slate-500 font-medium">Payroll Management</div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 space-y-1 overflow-y-auto">
            @php
                $currentRoute = Route::currentRouteName();
                $user = auth()->user();
            @endphp
            
            <a href="{{ route('dashboard') ?? '/' }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ $currentRoute == 'dashboard' || request()->is('/') ? 'bg-blue-900 text-white shadow-md' : 'text-slate-600 hover:bg-slate-50' }}">
                <svg class="w-5 h-5 {{ $currentRoute == 'dashboard' || request()->is('/') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>
            
            @if(in_array(auth()->user()->role, ['admin', 'atasan']))
                <div class="px-3 mt-4 mb-2 text-xs font-semibold text-slate-400 uppercase tracking-wider">Manajemen Pegawai</div>
                <a href="{{ route('pegawai.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('pegawai.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-600 hover:bg-slate-50 transition-colors' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('pegawai.*') ? 'text-blue-700' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Daftar Karyawan
                </a>
            @endif
            
            @if(auth()->user()->role == 'admin')
                <div class="px-3 mt-4 mb-2 text-xs font-semibold text-slate-400 uppercase tracking-wider">Pantauan HR</div>
                <a href="{{ route('absensi.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('absensi.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-600 hover:bg-slate-50 transition-colors' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('absensi.*') ? 'text-blue-700' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Absensi Hari Ini
                </a>
            @endif
            
            @if($user && $user->role === 'atasan')
            <a href="{{ route('penggajian.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ $currentRoute == 'penggajian.create' ? 'bg-blue-900 text-white shadow-md' : 'text-slate-600 hover:bg-slate-50' }}">
                <svg class="w-5 h-5 {{ $currentRoute == 'penggajian.create' ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Run Payroll
            </a>
            @endif

            @if($user && ($user->role === 'atasan' || $user->role === 'admin' || $user->role === 'pegawai'))
            <a href="{{ route('penggajian.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ $currentRoute == 'penggajian.index' ? 'bg-blue-900 text-white shadow-md' : 'text-slate-600 hover:bg-slate-50' }}">
                <svg class="w-5 h-5 {{ $currentRoute == 'penggajian.index' ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Riwayat Penggajian
            </a>
            
            <a href="{{ route('izincuti.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ Str::startsWith($currentRoute, 'izincuti') ? 'bg-blue-900 text-white shadow-md' : 'text-slate-600 hover:bg-slate-50' }}">
                <svg class="w-5 h-5 {{ Str::startsWith($currentRoute, 'izincuti') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Izin & Cuti
            </a>
            
            @if($user->role === 'pegawai')
            <a href="{{ route('kalender.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ $currentRoute == 'kalender.index' ? 'bg-blue-900 text-white shadow-md' : 'text-slate-600 hover:bg-slate-50' }}">
                <svg class="w-5 h-5 {{ $currentRoute == 'kalender.index' ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Kalender Kehadiran
            </a>
            @endif
            @endif
            
            @if($user && $user->role === 'atasan')
            <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ Str::startsWith($currentRoute, 'reports') ? 'bg-blue-900 text-white shadow-md' : 'text-slate-600 hover:bg-slate-50' }}">
                <svg class="w-5 h-5 {{ Str::startsWith($currentRoute, 'reports') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Reports
            </a>
            @endif
        </nav>


    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col h-full overflow-hidden">
        
        <!-- Topbar -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 shrink-0 z-10 print:hidden">
            <!-- Search -->
            <div class="flex-1 max-w-md">
                @if(auth()->check() && auth()->user()->role !== 'pegawai')
                <form action="{{ route('search') }}" method="GET" class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="q" value="{{ request('q') }}" class="block w-full pl-9 pr-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-sm placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="Cari karyawan, slip gaji, dll...">
                </form>
                @endif
            </div>

            <!-- Right Actions -->
            <div class="flex items-center gap-6">
                @auth
                @php
                    $unreadNotifications = auth()->user()->unreadNotifications;
                    $unreadCount = $unreadNotifications->count();
                @endphp
                <div class="relative" id="notification-container">
                    <button id="notification-button" class="relative text-slate-500 hover:text-slate-800 transition-colors p-1 rounded-full hover:bg-slate-100 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <span id="notification-badge" class="hidden absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full shadow-sm"></span>
                    </button>
                    
                    <!-- Dropdown -->
                    <div id="notification-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden z-50">
                        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 bg-slate-50">
                            <h3 class="font-semibold text-slate-800 text-sm">Notifikasi</h3>
                            <form action="{{ route('notifications.readAll') }}" method="POST" id="form-read-all" class="hidden">
                                @csrf
                                <button type="submit" class="text-xs text-blue-600 hover:text-blue-800 font-medium">Tandai semua dibaca</button>
                            </form>
                        </div>
                        <div class="max-h-80 overflow-y-auto" id="notification-list">
                            <!-- Dynamic content here -->
                        </div>
                    </div>
                </div>
                
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const btn = document.getElementById('notification-button');
                        const dropdown = document.getElementById('notification-dropdown');
                        const badge = document.getElementById('notification-badge');
                        const list = document.getElementById('notification-list');
                        const formReadAll = document.getElementById('form-read-all');
                        
                        if(btn && dropdown) {
                            btn.addEventListener('click', function(e) {
                                e.stopPropagation();
                                dropdown.classList.toggle('hidden');
                            });
                            
                            document.addEventListener('click', function(e) {
                                if (!dropdown.contains(e.target) && !btn.contains(e.target)) {
                                    dropdown.classList.add('hidden');
                                }
                            });
                        }

                        // Polling function
                        function fetchNotifications() {
                            fetch("{{ route('notifications.unread') }}", {
                                headers: {
                                    "X-Requested-With": "XMLHttpRequest"
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                // Update badge
                                if (data.count > 0) {
                                    badge.textContent = data.count > 99 ? '99+' : data.count;
                                    badge.classList.remove('hidden');
                                    formReadAll.classList.remove('hidden');
                                } else {
                                    badge.classList.add('hidden');
                                    formReadAll.classList.add('hidden');
                                }

                                // Update list
                                list.innerHTML = '';
                                if (data.count > 0) {
                                    let html = '';
                                    data.notifications.forEach(notif => {
                                        html += `
                                            <form action="${notif.read_url}" method="POST">
                                                @csrf
                                                <button type="submit" class="w-full text-left px-4 py-3 border-b border-slate-50 hover:bg-slate-50 transition-colors flex gap-3">
                                                    <div class="mt-1 w-2 h-2 rounded-full bg-blue-600 shrink-0"></div>
                                                    <div>
                                                        <p class="text-sm text-slate-800">${notif.data.message}</p>
                                                        <p class="text-xs text-slate-500 mt-1">${notif.created_at}</p>
                                                    </div>
                                                </button>
                                            </form>
                                        `;
                                    });
                                    list.innerHTML = html;
                                } else {
                                    list.innerHTML = `<div class="px-4 py-6 text-center text-sm text-slate-500">Tidak ada notifikasi baru.</div>`;
                                }
                            })
                            .catch(error => console.error('Error fetching notifications:', error));
                        }

                        // Fetch immediately and then every 3 seconds
                        fetchNotifications();
                        setInterval(fetchNotifications, 3000);
                    });
                </script>
                @endauth
                <div class="h-8 w-px bg-slate-200"></div>

                @auth
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-800 font-bold text-sm uppercase">
                        {{ substr(auth()->user()->name, 0, 2) }}
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-slate-700 leading-none">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-slate-500 mt-1 capitalize">{{ auth()->user()->role }}</div>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="ml-4 text-xs bg-slate-50 text-slate-600 px-3 py-1.5 rounded hover:bg-slate-100 font-medium transition-colors border border-slate-200">Profil Saya</a>
                    <form action="{{ route('logout') }}" method="POST" class="ml-2">
                        @csrf
                        <button type="submit" class="text-xs bg-red-50 text-red-600 px-3 py-1.5 rounded hover:bg-red-100 font-medium transition-colors border border-red-100">Logout</button>
                    </form>
                </div>
                @endauth
            </div>
        </header>

        <!-- Content Page -->
        <div class="flex-1 overflow-y-auto p-8 relative">
            @yield('content')
        </div>

    </main>
    <!-- SweetAlert2 for beautiful popups -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const absenSuccessMsg = sessionStorage.getItem('absen_success_msg');
            if (absenSuccessMsg) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: absenSuccessMsg,
                    icon: 'success',
                    confirmButtonText: 'Mantap',
                    confirmButtonColor: '#1e3a8a',
                    timer: 3000,
                    timerProgressBar: true
                });
                sessionStorage.removeItem('absen_success_msg');
            }
        });
    </script>
</body>
</html>
