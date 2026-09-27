<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
    <style>
        :root {
            --blue-50: #eff6ff;
            --blue-100: #dbeafe;
            --blue-200: #bfdbfe;
            --blue-300: #93c5fd;
            --blue-400: #60a5fa;
            --blue-500: #3b82f6;
            --blue-600: #2563eb;
            --blue-700: #1d4ed8;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #1e3a5f 0%, #1e40af 30%, #2563eb 60%, #3b82f6 100%);
            position: relative;
        }

        .hero-gradient::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 20% 50%, rgba(191, 219, 254, 0.15) 0%, transparent 50%),
                        radial-gradient(ellipse at 80% 20%, rgba(96, 165, 250, 0.1) 0%, transparent 50%),
                        radial-gradient(ellipse at 50% 80%, rgba(37, 99, 235, 0.1) 0%, transparent 50%);
        }

        .card-hover {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 60px rgba(59, 130, 246, 0.15), 0 8px 20px rgba(0, 0, 0, 0.06);
        }

        .role-card {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.3s ease;
        }
        .role-card:hover {
            transform: translateY(-4px) scale(1.01);
            box-shadow: 0 16px 48px rgba(59, 130, 246, 0.12);
            border-color: rgba(59, 130, 246, 0.3);
        }

        .btn-blue {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .btn-blue::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .btn-blue:hover::before {
            opacity: 1;
        }
        .btn-blue:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(59, 130, 246, 0.4);
        }
        .btn-blue > * {
            position: relative;
            z-index: 1;
        }

        .btn-white-glow {
            background: white;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-white-glow:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(255, 255, 255, 0.3);
        }

        .nav-blur {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
        }

        .floating-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.3;
            animation: float 8s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-15px) rotate(2deg); }
            66% { transform: translateY(8px) rotate(-1deg); }
        }

        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.7s cubic-bezier(0.4, 0, 0.2, 1), transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .animate-on-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .stagger-1 { transition-delay: 0.05s; }
        .stagger-2 { transition-delay: 0.1s; }
        .stagger-3 { transition-delay: 0.15s; }
        .stagger-4 { transition-delay: 0.2s; }
        .stagger-5 { transition-delay: 0.25s; }
        .stagger-6 { transition-delay: 0.3s; }

        .hero-text-animate {
            animation: heroReveal 0.9s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }
        .hero-text-animate-delay-1 {
            animation: heroReveal 0.9s cubic-bezier(0.16, 1, 0.3, 1) 0.15s forwards;
            opacity: 0;
        }
        .hero-text-animate-delay-2 {
            animation: heroReveal 0.9s cubic-bezier(0.16, 1, 0.3, 1) 0.3s forwards;
            opacity: 0;
        }
        .hero-text-animate-delay-3 {
            animation: heroReveal 0.9s cubic-bezier(0.16, 1, 0.3, 1) 0.45s forwards;
            opacity: 0;
        }
        .hero-text-animate-delay-4 {
            animation: heroReveal 0.9s cubic-bezier(0.16, 1, 0.3, 1) 0.6s forwards;
            opacity: 0;
        }

        @keyframes heroReveal {
            from { opacity: 0; transform: translateY(25px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .badge-animate {
            animation: badgePulse 3s ease-in-out infinite;
        }
        @keyframes badgePulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4); }
            50% { box-shadow: 0 0 0 8px rgba(59, 130, 246, 0); }
        }

        .icon-float {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover .icon-float {
            transform: scale(1.1) rotate(-3deg);
        }

        .gradient-text {
            background: linear-gradient(135deg, #3b82f6, #2563eb, #2563eb);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .section-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.3), transparent);
        }

        .cta-gradient {
            background: linear-gradient(135deg, #1e3a5f 0%, #1e40af 40%, #2563eb 100%);
            position: relative;
            overflow: hidden;
        }
        .cta-gradient::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 30% 50%, rgba(96, 165, 250, 0.1) 0%, transparent 50%);
        }

        .footer-gradient {
            background: linear-gradient(180deg, #fdf2f8 0%, white 100%);
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            animation: particleFloat linear infinite;
        }

        @keyframes particleFloat {
            0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-10vh) rotate(720deg); opacity: 0; }
        }

        .wave-bottom {
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
        }
        .wave-bottom svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 60px;
        }
    </style>
</head>
<body class="min-h-screen bg-white text-zinc-900">

    {{-- Navbar --}}
    <nav class="nav-blur border-b border-blue-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center size-10 rounded-xl bg-white shadow-lg p-1">
                        <x-app-logo-icon class="h-8 w-auto" />
                    </div>
                    <div>
                        <span class="font-bold text-sm leading-tight text-zinc-800">LPP RRI Kupang</span>
                        <span class="block text-[10px] text-blue-500 font-medium leading-tight">Sistem Manajemen Tugas</span>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn-blue inline-flex items-center px-5 py-2 text-sm font-semibold text-white rounded-xl">
                                <span class="flex items-center gap-2">
                                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6z" /></svg>
                                    Dashboard
                                </span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn-blue inline-flex items-center px-5 py-2 text-sm font-semibold text-white rounded-xl">
                                <span class="flex items-center gap-2">
                                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15" /></svg>
                                    Masuk
                                </span>
                            </a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="hero-gradient relative overflow-hidden">
        <div class="absolute inset-0">
            <div class="floating-orb size-96 bg-blue-300 top-10 -left-20" style="animation-delay: 0s;"></div>
            <div class="floating-orb size-72 bg-indigo-400 top-40 right-10" style="animation-delay: 2s;"></div>
            <div class="floating-orb size-64 bg-cyan-300 bottom-10 left-1/3" style="animation-delay: 4s;"></div>
            <div class="particle" style="left: 10%; animation-duration: 12s; animation-delay: 0s;"></div>
            <div class="particle" style="left: 25%; animation-duration: 15s; animation-delay: 1s;"></div>
            <div class="particle" style="left: 40%; animation-duration: 11s; animation-delay: 3s;"></div>
            <div class="particle" style="left: 55%; animation-duration: 14s; animation-delay: 2s;"></div>
            <div class="particle" style="left: 70%; animation-duration: 13s; animation-delay: 0.5s;"></div>
            <div class="particle" style="left: 85%; animation-duration: 16s; animation-delay: 4s;"></div>
            <div class="particle" style="left: 95%; animation-duration: 10s; animation-delay: 1.5s;"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 sm:py-32 relative">
            <div class="text-center">
                <div class="hero-text-animate inline-flex items-center gap-2 px-5 py-2 rounded-full bg-white/15 border border-white/25 text-white/90 text-xs font-semibold mb-8 badge-animate backdrop-blur-sm">
                    <span class="size-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Lembaga Penyiaran Publik
                </div>
                <h1 class="hero-text-animate-delay-1 text-4xl sm:text-5xl lg:text-6xl font-bold text-white tracking-tight leading-tight">
                    Sistem Manajemen<br>Tugas <span class="text-blue-200">LPP RRI Kupang</span>
                </h1>
                <p class="hero-text-animate-delay-2 mt-6 text-lg text-blue-100/80 max-w-2xl mx-auto leading-relaxed">
                    Platform terpusat untuk mengelola, mendistribusikan, dan memantau pekerjaan harian seluruh pegawai LPP RRI Kupang secara efisien dan transparan.
                </p>
                <div class="hero-text-animate-delay-3 mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn-white-glow inline-flex items-center gap-2 px-8 py-3.5 text-sm font-semibold text-blue-700 rounded-xl shadow-xl">
                                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" /></svg>
                                Buka Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn-white-glow inline-flex items-center gap-2 px-8 py-3.5 text-sm font-semibold text-blue-700 rounded-xl shadow-xl">
                                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" /></svg>
                                Masuk ke Sistem
                            </a>
                        @endauth
                    @endif
                </div>
                <div class="hero-text-animate-delay-4 mt-12 flex items-center justify-center gap-8 text-blue-200/60 text-sm">
                    <div class="flex items-center gap-2">
                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" /></svg>
                        Aman & Terpercaya
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" /></svg>
                        Real-time
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3" /></svg>
                        Mobile Friendly
                    </div>
                </div>
            </div>
        </div>
        <div class="wave-bottom">
            <svg viewBox="0 0 1200 60" preserveAspectRatio="none" fill="white">
                <path d="M0,60 C300,0 600,60 900,20 C1050,5 1150,30 1200,40 L1200,60 Z"></path>
            </svg>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="py-20 sm:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 animate-on-scroll">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-50 border border-blue-100 text-blue-600 text-xs font-semibold mb-4">
                    <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" /></svg>
                    Fitur Unggulan
                </div>
                <h2 class="text-3xl font-bold text-zinc-900">Fitur Utama</h2>
                <p class="mt-3 text-zinc-500 max-w-xl mx-auto">
                    Dirancang untuk memenuhi kebutuhan operasional harian LPP RRI Kupang
                </p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                {{-- Feature 1 --}}
                <div class="animate-on-scroll stagger-1 card-hover p-6 rounded-2xl border border-blue-100 bg-gradient-to-br from-white to-blue-50/50">
                    <div class="icon-float size-11 rounded-xl bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center mb-4 shadow-sm">
                        <svg class="size-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" /></svg>
                    </div>
                    <h3 class="font-semibold text-zinc-900 mb-2">Pencatatan Tugas</h3>
                    <p class="text-sm text-zinc-500 leading-relaxed">
                        Catat dan distribusikan tugas kepada pegawai dengan detail waktu, lokasi, dan instruksi kerja.
                    </p>
                </div>

                {{-- Feature 2 --}}
                <div class="animate-on-scroll stagger-2 card-hover p-6 rounded-2xl border border-blue-100 bg-gradient-to-br from-white to-blue-50/50">
                    <div class="icon-float size-11 rounded-xl bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center mb-4 shadow-sm">
                        <svg class="size-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="font-semibold text-zinc-900 mb-2">Validasi Pimpinan</h3>
                    <p class="text-sm text-zinc-500 leading-relaxed">
                        Pimpinan dapat memvalidasi penyelesaian tugas dan memberikan persetujuan secara digital.
                    </p>
                </div>

                {{-- Feature 3 --}}
                <div class="animate-on-scroll stagger-3 card-hover p-6 rounded-2xl border border-blue-100 bg-gradient-to-br from-white to-blue-50/50">
                    <div class="icon-float size-11 rounded-xl bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center mb-4 shadow-sm">
                        <svg class="size-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" /></svg>
                    </div>
                    <h3 class="font-semibold text-zinc-900 mb-2">Notifikasi WhatsApp</h3>
                    <p class="text-sm text-zinc-500 leading-relaxed">
                        Notifikasi otomatis via WhatsApp saat tugas baru diterbitkan atau status berubah.
                    </p>
                </div>

                {{-- Feature 4 --}}
                <div class="animate-on-scroll stagger-4 card-hover p-6 rounded-2xl border border-blue-100 bg-gradient-to-br from-white to-blue-50/50">
                    <div class="icon-float size-11 rounded-xl bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center mb-4 shadow-sm">
                        <svg class="size-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>
                    </div>
                    <h3 class="font-semibold text-zinc-900 mb-2">Laporan & Rekap</h3>
                    <p class="text-sm text-zinc-500 leading-relaxed">
                        Rekap data tugas harian secara otomatis dan unduh laporan dalam format PDF.
                    </p>
                </div>

                {{-- Feature 5 --}}
                <div class="animate-on-scroll stagger-5 card-hover p-6 rounded-2xl border border-blue-100 bg-gradient-to-br from-white to-blue-50/50">
                    <div class="icon-float size-11 rounded-xl bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center mb-4 shadow-sm">
                        <svg class="size-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                    </div>
                    <h3 class="font-semibold text-zinc-900 mb-2">Manajemen Pengguna</h3>
                    <p class="text-sm text-zinc-500 leading-relaxed">
                        Kelola data pegawai, unit kerja, jabatan, dan hak akses pengguna berdasarkan peran.
                    </p>
                </div>

                {{-- Feature 6 --}}
                <div class="animate-on-scroll stagger-6 card-hover p-6 rounded-2xl border border-blue-100 bg-gradient-to-br from-white to-blue-50/50">
                    <div class="icon-float size-11 rounded-xl bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center mb-4 shadow-sm">
                        <svg class="size-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" /><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z" /></svg>
                    </div>
                    <h3 class="font-semibold text-zinc-900 mb-2">Tiga Peran Pengguna</h3>
                    <p class="text-sm text-zinc-500 leading-relaxed">
                        Admin, Pimpinan, dan Pegawai masing-masing memiliki dashboard dan akses sesuai perannya.
                    </p>
                </div>

            </div>
        </div>
    </section>

    <div class="section-divider"></div>

    {{-- Roles Section --}}
    <section class="py-20 sm:py-28 bg-gradient-to-b from-blue-50/50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 animate-on-scroll">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-50 border border-blue-100 text-blue-600 text-xs font-semibold mb-4">
                    <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                    Peran Pengguna
                </div>
                <h2 class="text-3xl font-bold text-zinc-900">Akses Sesuai Peran</h2>
                <p class="mt-3 text-zinc-500 max-w-xl mx-auto">
                    Setiap pengguna mendapatkan antarmuka yang sesuai dengan tanggung jawabnya
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Admin --}}
                <div class="animate-on-scroll stagger-1 role-card text-center p-8 rounded-2xl border border-blue-100 bg-white">
                    <div class="size-16 rounded-2xl bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center mx-auto mb-6 shadow-lg shadow-blue-200">
                        <svg class="size-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.384 3.18A1.125 1.125 0 014.5 17.36V5.64a1.125 1.125 0 011.536-1.051l5.384 3.18a1.125 1.125 0 010 1.942z" /><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v12m0 0l3-1.5m-3 1.5l-3-1.5" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-zinc-900 mb-2">Admin</h3>
                    <p class="text-sm text-zinc-500 leading-relaxed">
                        Mengelola data master: unit kerja, jabatan, pegawai, dan akun pengguna.
                    </p>
                </div>

                {{-- Pimpinan --}}
                <div class="animate-on-scroll stagger-2 role-card text-center p-8 rounded-2xl border border-blue-100 bg-white">
                    <div class="size-16 rounded-2xl bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center mx-auto mb-6 shadow-lg shadow-blue-200">
                        <svg class="size-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-zinc-900 mb-2">Pimpinan</h3>
                    <p class="text-sm text-zinc-500 leading-relaxed">
                        Menerbitkan tugas, memvalidasi penyelesaian, dan melihat laporan kerja.
                    </p>
                </div>

                {{-- Pegawai --}}
                <div class="animate-on-scroll stagger-3 role-card text-center p-8 rounded-2xl border border-blue-100 bg-white">
                    <div class="size-16 rounded-2xl bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center mx-auto mb-6 shadow-lg shadow-blue-200">
                        <svg class="size-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-zinc-900 mb-2">Pegawai</h3>
                    <p class="text-sm text-zinc-500 leading-relaxed">
                        Melihat tugas yang ditugaskan, memperbarui progres, dan menandakan selesai.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="cta-gradient py-20 relative overflow-hidden">
        <div class="absolute inset-0">
            <div class="floating-orb size-64 bg-blue-300/20 -top-20 -right-20" style="animation-delay: 0s;"></div>
            <div class="floating-orb size-48 bg-indigo-300/20 bottom-10 -left-10" style="animation-delay: 3s;"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative">
            <div class="animate-on-scroll">
                <h2 class="text-2xl sm:text-3xl font-bold text-white mb-4">Siap untuk memulai?</h2>
                <p class="text-blue-200/70 mb-8 max-w-md mx-auto">
                    Masuk ke sistem untuk mengelola tugas harian Anda.
                </p>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-white-glow inline-flex items-center gap-2 px-8 py-3.5 text-sm font-semibold text-blue-700 rounded-xl shadow-xl">
                            Buka Dashboard
                            <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-white-glow inline-flex items-center gap-2 px-8 py-3.5 text-sm font-semibold text-blue-700 rounded-xl shadow-xl">
                            Masuk ke Sistem
                            <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="footer-gradient py-8 border-t border-blue-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2 text-sm text-zinc-500">
                    <div class="flex items-center justify-center size-7 rounded-lg bg-white p-0.5 shadow-sm">
                        <x-app-logo-icon class="h-6 w-auto" />
                    </div>
                    <span>LPP RRI Kupang &copy; {{ date('Y') }}</span>
                </div>
                <div class="text-xs text-blue-400 font-medium">
                    Sistem Manajemen Tugas
                </div>
            </div>
        </div>
    </footer>

    @fluxScripts
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -40px 0px'
            });

            document.querySelectorAll('.animate-on-scroll').forEach(el => {
                observer.observe(el);
            });
        });
    </script>
</body>
</html>
