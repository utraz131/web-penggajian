<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Single Sign-on - Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        
        .bg-pattern {
            background-color: #2563eb;
            background-image: radial-gradient(#1d4ed8 1.5px, transparent 1.5px), radial-gradient(#1d4ed8 1.5px, transparent 1.5px);
            background-size: 36px 36px;
            background-position: 0 0, 18px 18px;
        }

        .wave-container {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 150px;
            overflow: hidden;
            z-index: 10;
        }

        .wave-shape {
            position: absolute;
            top: -5%;
            left: -100px;
            width: 250px;
            height: 110%;
            background: white;
            border-radius: 50% 50% 50% 50% / 40% 60% 40% 60%;
        }

        .animate-gradient {
            background: linear-gradient(to right, #2563eb, #10b981, #06b6d4, #2563eb);
            background-size: 300% auto;
            color: #000;
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shine 3s linear infinite;
        }
        
        @keyframes shine {
            to {
                background-position: 300% center;
            }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-white overflow-hidden m-0 p-0">
    <div class="flex w-full h-screen relative">
        
        <!-- Left Side (White) -->
        <div class="hidden md:flex w-[40%] flex-col justify-center items-start px-16 relative z-20 bg-white">
            <div class="absolute top-10 left-10 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-black flex items-center justify-center text-white shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg>
                </div>
                <div class="leading-tight">
                    <div class="text-blue-600 font-bold text-lg tracking-wider">REXY CORP</div>
                </div>
            </div>

            <div class="max-w-xs mt-20">
                <h1 class="text-3xl font-bold mb-4 tracking-wide uppercase animate-gradient">Selamat Datang Kembali!</h1>
                <p class="text-blue-400 text-lg leading-relaxed">Masukkan ID dan Kata Sandi untuk melanjutkan.</p>
            </div>
        </div>

        <!-- Right Side (Blue Background with pattern) -->
        <div class="w-full md:w-[60%] h-full relative bg-gradient-to-br from-blue-700 via-blue-600 to-blue-500 flex flex-col justify-center items-center">
            
            <!-- Custom wave separating left and right -->
            <div class="hidden md:block wave-container">
                <div class="wave-shape"></div>
            </div>

            <!-- Background Icons Pattern (Absolute elements for decorative effect) -->
            <div class="absolute inset-0 overflow-hidden opacity-10 pointer-events-none">
                <!-- Repeating keys and locks -->
                <svg class="absolute top-20 left-20 w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                <svg class="absolute top-40 right-32 w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                <svg class="absolute bottom-32 left-40 w-14 h-14 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                <svg class="absolute bottom-20 right-20 w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                <!-- Add a few more generic shapes if needed -->
                <div class="absolute top-[30%] left-[60%] w-20 h-20 border-2 border-white rounded-full"></div>
                <div class="absolute top-[60%] left-[20%] w-10 h-10 border-2 border-white rounded-md transform rotate-45"></div>
            </div>

            <!-- Login Form Container -->
            <div class="relative z-20 w-full max-w-sm px-6">
                
                <div class="text-center mb-8 text-white">
                    <h2 class="text-3xl font-medium tracking-wide mb-1">SIGN IN</h2>
                    <p class="text-sm font-light uppercase tracking-widest text-blue-100">TO ACCESS THE PORTAL</p>
                </div>

                @if(session('success'))
                    <div class="mb-6 bg-emerald-500/80 text-white p-3 rounded-xl text-sm text-center shadow-lg border border-emerald-400">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 bg-red-500/80 text-white p-3 rounded-full text-xs text-center shadow-lg border border-red-400">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                    @csrf
                    <!-- User Name Field -->
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full pl-11 pr-4 py-3 bg-white border-0 rounded-full text-sm text-slate-800 placeholder-slate-400 focus:ring-4 focus:ring-white/30 transition-all shadow-lg" placeholder="Enter User Name Here">
                    </div>
                    
                    <!-- Password Field -->
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd"></path></svg>
                        </div>
                        <input type="password" name="password" required class="w-full pl-11 pr-4 py-3 bg-white border-0 rounded-full text-sm text-slate-800 placeholder-slate-400 focus:ring-4 focus:ring-white/30 transition-all shadow-lg" placeholder="Enter Password">
                    </div>
                    
                    <!-- Login Button -->
                    <button type="submit" class="w-full py-3.5 px-4 bg-[#00d084] hover:bg-[#00b875] text-white font-medium rounded-full shadow-lg shadow-[#00d084]/40 transition-all hover:-translate-y-0.5 mt-2 text-sm tracking-wide">
                        Login
                    </button>
                </form>

                <div class="mt-6 text-center flex flex-col gap-3">
                    <a href="{{ route('forgot.password') }}" class="text-sm text-blue-100 hover:text-white transition-colors underline decoration-blue-300 decoration-1 underline-offset-4">Lupa Kata Sandi?</a>
                </div>
            </div>

            <!-- Mobile Only Logo -->
            <div class="md:hidden absolute top-8 left-8 flex items-center gap-2 text-white">
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">RC</div>
                <div class="text-sm font-bold tracking-wider">REXY CORP</div>
            </div>

            <!-- Copyright Footer -->
            <div class="absolute bottom-6 w-full text-center text-xs text-blue-200/50">
                Copyright &copy; {{ date('Y') }} REXY CORP. All rights reserved.
            </div>

        </div>
    </div>
</body>
</html>
