<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perpustakaan Digital - SMP Negeri 4 Jember</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
    <style>
        :root {
            --scroll-progress: 0%;
        }

        html, body {
            overflow-x: hidden;
            max-width: 100%;
        }

        .hero-backdrop {
            background-image:
                linear-gradient(135deg, rgba(12, 10, 9, 0.95), rgba(12, 10, 9, 0.8) 48%, rgba(28, 25, 23, 0.6)),
                url('{{ asset('images/skh.png') }}');
            background-position: center;
            background-size: cover;
            background-attachment: fixed;
        }

        /* background-attachment: fixed is buggy/janky on touch devices (iOS Safari, most Android
           browsers, and even some tablets/laptops with touch) so we disable it below desktop sizes */
        @media (max-width: 1024px), (hover: none) {
            .hero-backdrop {
                background-attachment: scroll;
            }
        }

        .book-title {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            overflow: hidden;
        }

        /* ===== ANIMASI SCROLL ===== */
        @media (prefers-reduced-motion: no-preference) {
            .rise-in {
                animation: riseIn 0.7s cubic-bezier(0.16, 1, 0.3, 1) both;
            }

            @keyframes riseIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }

            .scroll-fade-in {
                opacity: 0;
                transform: translateY(30px);
                transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .scroll-fade-in.visible {
                opacity: 1;
                transform: translateY(0);
            }

            .scroll-slide-left {
                opacity: 0;
                transform: translateX(-50px);
                transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .scroll-slide-left.visible {
                opacity: 1;
                transform: translateX(0);
            }

            .scroll-slide-right {
                opacity: 0;
                transform: translateX(50px);
                transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .scroll-slide-right.visible {
                opacity: 1;
                transform: translateX(0);
            }

            .scroll-scale-up {
                opacity: 0;
                transform: scale(0.95);
                transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .scroll-scale-up.visible {
                opacity: 1;
                transform: scale(1);
            }

            .scroll-stagger > * {
                opacity: 0;
                transform: translateY(20px);
                transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .scroll-stagger.visible > * {
                opacity: 1;
                transform: translateY(0);
            }

            .scroll-stagger > *:nth-child(1) { transition-delay: 0.1s; }
            .scroll-stagger > *:nth-child(2) { transition-delay: 0.2s; }
            .scroll-stagger > *:nth-child(3) { transition-delay: 0.3s; }
            .scroll-stagger > *:nth-child(4) { transition-delay: 0.4s; }
            .scroll-stagger > *:nth-child(5) { transition-delay: 0.5s; }
        }

        /* On touch devices, elements that never scroll into "visible" (e.g. because JS hasn't
           run yet, or IntersectionObserver is unsupported) should not stay invisible forever */
        .no-js .scroll-fade-in,
        .no-js .scroll-slide-left,
        .no-js .scroll-slide-right,
        .no-js .scroll-scale-up,
        .no-js .scroll-stagger > * {
            opacity: 1 !important;
            transform: none !important;
        }

        /* ===== SOCIAL MEDIA SIDEBAR ===== */
        .social-sidebar {
            position: fixed;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            z-index: 40;
            display: flex;
            flex-direction: column;
            gap: 0;
            background: linear-gradient(180deg, rgba(12, 10, 9, 0.95) 0%, rgba(12, 10, 9, 0.85) 100%);
            backdrop-filter: blur(10px);
            border-left: 2px solid rgba(245, 158, 11, 0.3);
            border-radius: 12px 0 0 12px;
            padding: 8px 0;
            margin-right: 0;
            box-shadow: -4px 0 20px rgba(0, 0, 0, 0.3);
        }

        .social-link {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fafaf9;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            font-size: 18px;
            border-left: 3px solid transparent;
        }

        .social-link:hover {
            background: rgba(245, 158, 11, 0.15);
            border-left-color: #f59e0b;
            padding-left: 8px;
            transform: translateX(-4px);
        }

        .social-link i {
            transition: transform 0.3s ease;
        }

        .social-link:hover i {
            transform: scale(1.2) rotate(5deg);
        }

        .social-link::after {
            content: attr(data-tooltip);
            position: absolute;
            right: 60px;
            background: #f59e0b;
            color: #0c0a09;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s ease;
            transform: translateX(10px);
        }

        .social-link:hover::after {
            opacity: 1;
            transform: translateX(0);
        }

        /* ===== SCROLL PROGRESS BAR ===== */
        .scroll-progress {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            background: linear-gradient(90deg, #f59e0b, #ec4899, #06b6d4);
            width: var(--scroll-progress);
            z-index: 60;
            transition: width 0.1s ease;
        }

        /* ===== STRUKTUR ORGANISASI TREE CSS ===== */
        .org-tree-wrapper {
            -ms-overflow-style: none; /* IE and Edge */
            scrollbar-width: none; /* Firefox */
        }
        .org-tree-wrapper::-webkit-scrollbar {
            display: none; /* Chrome, Safari and Opera */
        }
        .org-tree ul {
            padding-top: 30px; 
            position: relative;
            display: flex;
            justify-content: center;
            padding-left: 0;
        }
        .org-tree li {
            float: left; text-align: center;
            list-style-type: none;
            position: relative;
            padding: 30px 10px 0 10px;
        }
        .org-tree li::before, .org-tree li::after {
            content: '';
            position: absolute; top: 0; right: 50%;
            border-top: 2px solid #d6d3d1; /* stone-300 */
            width: 50%; height: 30px;
        }
        .org-tree li::after {
            right: auto; left: 50%;
            border-left: 2px solid #d6d3d1;
        }
        .org-tree li:only-child::after, .org-tree li:only-child::before {
            display: none;
        }
        .org-tree li:only-child { 
            padding-top: 0;
        }
        .org-tree li:first-child::before, .org-tree li:last-child::after {
            border: 0 none;
        }
        .org-tree li:last-child::before {
            border-right: 2px solid #d6d3d1;
            border-radius: 0 8px 0 0;
        }
        .org-tree li:first-child::after {
            border-radius: 8px 0 0 0;
        }
        .org-tree ul ul::before {
            content: '';
            position: absolute; top: 0; left: 50%;
            border-left: 2px solid #d6d3d1;
            width: 0; height: 30px;
            margin-left: -1px;
        }
        
        .org-node {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            cursor: default;
        }

        /* ===== FOOTER ===== */
        footer {
            background: linear-gradient(135deg, #1c1917 0%, #0f0d0c 100%);
            border-top: 1px solid rgba(245, 158, 11, 0.2);
            color: #fafaf9;
        }

        .footer-section h3 {
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #f59e0b;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 2px solid rgba(245, 158, 11, 0.3);
        }

        .footer-link {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #d4d4d8;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
            padding: 6px 0;
        }

        .footer-link:hover {
            color: #f59e0b;
            transform: translateX(6px);
        }

        .footer-link i {
            width: 16px;
            color: #f59e0b;
            font-size: 14px;
            text-align: center;
        }

        .footer-social-links {
            display: flex;
            gap: 12px;
            margin-top: 16px;
        }

        .footer-social-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            font-size: 16px;
        }

        .footer-social-icon:hover {
            background: #f59e0b;
            color: #0c0a09;
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(245, 158, 11, 0.3);
        }

        .footer-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(245, 158, 11, 0.3), transparent);
            margin: 32px 0;
        }

        .footer-bottom {
            background: rgba(0, 0, 0, 0.4);
            border-top: 1px solid rgba(245, 158, 11, 0.1);
            padding: 24px 0;
        }

        .footer-bottom-text {
            font-size: 13px;
            color: #a1a1a1;
        }

        .footer-bottom-link {
            color: #f59e0b;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-bottom-link:hover {
            color: #fbbf24;
        }

        /* ===== RESPONSIVE ENHANCEMENTS ===== */
        @media (max-width: 1024px) {
            .social-sidebar {
                right: -4px;
                transform: translateY(-50%) scale(0.9);
                opacity: 0.95;
            }
        }

        @media (max-width: 768px) {
            .social-sidebar {
                right: -60px;
                width: 50px;
                border-radius: 12px 0 0 12px;
                padding: 6px 0;
            }

            .social-link::after { display: none; }
            .social-link { width: 50px; height: 50px; font-size: 16px; }
            footer { padding: 40px 20px; }
            .footer-section { margin-bottom: 32px; }

            .hero-backdrop {
                min-height: 620px;
                background-attachment: scroll;
            }

            .hero-title {
                font-size: clamp(2.5rem, 10vw, 4rem);
            }

            .hero-actions {
                width: 100%;
            }

            .hero-actions a,
            .hero-actions button {
                width: 100%;
            }

            .search-shell {
                padding: 0.75rem;
            }

            .search-row {
                flex-direction: column;
                align-items: stretch;
            }

            .search-btn {
                width: 100%;
            }
        }

        @media (max-width: 640px) {
            .social-sidebar {
                display: none;
            }

            header .header-logo p:first-child {
                font-size: 0.75rem;
            }

            header .header-logo p:last-child {
                font-size: 0.625rem;
            }

            .mobile-menu-panel a,
            .mobile-menu-panel button {
                width: 100%;
                justify-content: center;
            }

            .org-tree-wrapper {
                padding-bottom: 0;
            }

            .footer-bottom .grid {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .footer-bottom .flex {
                justify-content: center;
                flex-wrap: wrap;
            }
        }

        @media (max-width: 480px) {
            .hero-backdrop {
                min-height: 560px;
            }

            .hero-backdrop .mx-auto {
                padding-top: 2rem;
                padding-bottom: 2rem;
            }

            #modalOverlay .relative {
                margin: 0.75rem;
                max-height: calc(100vh - 1.5rem);
            }

            #modalOverlay .p-8 {
                padding: 1.25rem;
            }

            #modalOverlay .flex.gap-6 {
                flex-direction: column;
            }

            #modalOverlay .grid-cols-2 {
                grid-template-columns: 1fr 1fr;
            }

            #modalOverlay .flex.gap-6 .h-32.w-24 {
                margin-top: -3.5rem;
                width: 5.5rem;
                height: 7.5rem;
            }

            .stats-grid {
                gap: 1rem !important;
            }

            .stats-grid > div {
                min-width: 0;
            }

            .stats-grid p {
                font-size: 1.25rem;
                word-break: break-word;
            }
        }

        @media (max-width: 360px) {
            .stats-grid {
                grid-template-columns: 1fr !important;
            }
        }

        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="min-h-screen bg-stone-50 font-sans text-stone-950 antialiased">
    
    <div class="scroll-progress"></div>

    <aside class="social-sidebar" aria-label="Social Media Links">
        <a href="https://www.facebook.com/profile.php?id=100069959832475" target="_blank" rel="noopener noreferrer" class="social-link" data-tooltip="Facebook">
            <i class="fab fa-facebook-f"></i>
        </a>
        <a href="https://www.instagram.com/smpn4jember/" target="_blank" rel="noopener noreferrer" class="social-link" data-tooltip="Instagram">
            <i class="fab fa-instagram"></i>
        </a>
        <a href="https://www.tiktok.com/@snipersmpn4jember" target="_blank" rel="noopener noreferrer" class="social-link" data-tooltip="Twitter">
            <i class="fab fa-tiktok"></i>
        </a>
        <a href="https://www.youtube.com/@SMPN4JEMBER" target="_blank" rel="noopener noreferrer" class="social-link" data-tooltip="YouTube">
            <i class="fab fa-youtube"></i>
        </a>
        <a href="mailto:perpustakaan@smpn4jember.sch.id" class="social-link" data-tooltip="Email">
            <i class="fas fa-envelope"></i>
        </a>
    </aside>

    <header class="fixed inset-x-0 top-0 z-50 border-b border-white/10 bg-stone-950/80 text-white backdrop-blur-md shadow-sm">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="header-logo flex min-w-0 items-center gap-3 transition hover:opacity-80">
                <img src="{{ asset('images/smp.png') }}" alt="Logo SMP Negeri 4 Jember" class="h-10 w-10 shrink-0 rounded-md bg-white object-contain p-1">
                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold tracking-wide">Perpustakaan Digital</p>
                    <p class="truncate text-xs text-amber-400">SMP Negeri 4 Jember</p>
                </div>
            </a>

            <nav class="hidden items-center gap-8 text-sm font-medium text-stone-300 md:flex">
                <a href="#koleksi" class="transition hover:text-amber-400">Koleksi</a>
                <a href="#layanan" class="transition hover:text-amber-400">Layanan</a>
                <a href="#pengumuman" class="transition hover:text-amber-400">Info</a>
                <a href="#struktur" class="transition hover:text-amber-400">Struktur</a>
                <a href="#kontak" class="transition hover:text-amber-400">Kontak</a>
            </nav>

            <div class="flex items-center gap-2 md:hidden">
                <button id="mobileMenuButton" type="button" aria-label="Buka menu" aria-expanded="false" aria-controls="mobileMenu" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-white/10 bg-white/5 text-white transition hover:bg-white/10">
                    <i class="fas fa-bars text-lg"></i>
                </button>
            </div>

            <a href="{{ route('login') }}" class="hidden md:inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-amber-500 px-5 text-sm font-semibold text-stone-950 transition hover:bg-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2 focus:ring-offset-stone-950 shadow-lg shadow-amber-500/20">
                <i class="fas fa-sign-in-alt"></i>
                <span class="hidden sm:inline">Login Admin</span>
            </a>
        </div>

        <div id="mobileMenu" class="mobile-menu-panel hidden border-t border-white/10 bg-stone-950/95 px-4 py-4 md:hidden">
            <nav class="flex flex-col gap-2 text-sm font-medium text-stone-200">
                <a href="#koleksi" class="rounded-lg px-3 py-2.5 transition hover:bg-white/5 hover:text-amber-400">Koleksi</a>
                <a href="#layanan" class="rounded-lg px-3 py-2.5 transition hover:bg-white/5 hover:text-amber-400">Layanan</a>
                <a href="#pengumuman" class="rounded-lg px-3 py-2.5 transition hover:bg-white/5 hover:text-amber-400">Info</a>
                <a href="#struktur" class="rounded-lg px-3 py-2.5 transition hover:bg-white/5 hover:text-amber-400">Struktur</a>
                <a href="#kontak" class="rounded-lg px-3 py-2.5 transition hover:bg-white/5 hover:text-amber-400">Kontak</a>
                <a href="{{ route('login') }}" class="mt-2 inline-flex items-center justify-center gap-2 rounded-lg bg-amber-500 px-4 py-2.5 font-semibold text-stone-950 transition hover:bg-amber-400">
                    <i class="fas fa-sign-in-alt"></i>
                    Login Admin
                </a>
            </nav>
        </div>
    </header>

    <main>
        <section class="hero-backdrop relative isolate min-h-[560px] sm:min-h-[620px] lg:min-h-[720px] overflow-hidden pt-24 text-white flex items-center">
            <div class="absolute inset-0 -z-10 bg-[linear-gradient(180deg,transparent,rgba(250,250,249,0.02)_60%,#fafaf9_100%)] backdrop-blur-[2px]"></div>

            <div class="mx-auto flex w-full max-w-7xl flex-col gap-10 px-4 pb-14 pt-14 sm:px-6 lg:px-8">
                <div class="max-w-3xl rise-in">
                    <div class="inline-flex items-center gap-3 rounded-full border border-amber-400/30 bg-amber-500/10 px-4 py-2 text-sm font-semibold text-amber-300 backdrop-blur-md shadow-lg">
                        <i class="fas fa-book-reader text-amber-400"></i>
                        Katalog Digital Interaktif Sekolah
                    </div>

                    <h1 class="hero-title mt-8 text-4xl font-bold tracking-tight leading-[1.1] sm:text-6xl lg:text-7xl scroll-fade-in drop-shadow-md">
                        Jendela Dunia dalam <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 to-amber-500">Satu Genggaman</span>
                    </h1>
                    <p class="mt-6 max-w-2xl text-base sm:text-lg leading-relaxed text-stone-300 scroll-fade-in drop-shadow" style="transition-delay: 0.2s;">
                        Jelajahi ribuan koleksi buku pelajaran, fiksi, sains, hingga referensi sejarah di ruang baca digital SMP Negeri 4 Jember yang rapi dan nyaman.
                    </p>

                    <div class="hero-actions mt-10 flex flex-col gap-4 sm:flex-row scroll-fade-in" style="transition-delay: 0.3s;">
                        <a href="#koleksi" class="inline-flex h-14 items-center justify-center gap-2 rounded-xl bg-amber-500 px-8 text-base font-bold text-stone-950 transition hover:bg-amber-400 hover:scale-105 hover:shadow-xl hover:shadow-amber-500/30">
                            <i class="fas fa-search"></i>
                            Jelajahi Koleksi
                        </a>
                        <a href="#pengumuman" class="inline-flex h-14 items-center justify-center gap-2 rounded-xl border border-white/20 bg-white/5 px-8 text-base font-semibold text-white backdrop-blur-md transition hover:bg-white/15 hover:border-white/40">
                            <i class="far fa-bell"></i>
                            Lihat Pengumuman
                        </a>
                    </div>
                </div>

                <div class="mt-4 lg:w-2/3 scroll-scale-up" style="animation-delay: 0.4s">
                    <div class="search-shell rounded-2xl border border-white/10 bg-white/10 p-3 shadow-2xl backdrop-blur-lg">
                        <div class="search-row flex flex-col gap-3 sm:flex-row">
                            <div class="flex min-h-[56px] flex-1 items-center gap-3 rounded-xl bg-white px-5 text-stone-600 focus-within:ring-2 focus-within:ring-amber-400 transition-all shadow-inner">
                                <i class="fas fa-search text-stone-400 text-lg"></i>
                                <input id="searchInput" type="search" class="h-full w-full bg-transparent text-base text-stone-900 outline-none placeholder:text-stone-400" placeholder="Cari judul buku, nama penulis, atau kategori..." oninput="cariBuku()">
                            </div>
                            <button type="button" onclick="scrollToCollection()" class="search-btn inline-flex h-[56px] items-center justify-center rounded-xl bg-stone-900 px-8 text-base font-semibold text-white transition hover:bg-stone-800 shadow-md">
                                Cari Buku
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="koleksi" class="scroll-fade-in relative px-4 py-16 sm:py-24 sm:px-6 lg:px-8 bg-stone-50">
            <div class="mx-auto max-w-7xl">
                <div class="mb-12 scroll-fade-in text-center">
                    <h2 class="text-2xl font-bold text-stone-900 sm:text-3xl lg:text-4xl">Koleksi Buku Pilihan</h2>
                    <p class="mt-4 text-stone-500 max-w-2xl mx-auto">
                        Temukan ribuan judul buku yang kami miliki, mulai dari buku pelajaran, literasi, hingga referensi pengetahuan umum
                    </p>
                </div>

                <div class="mb-10 flex flex-wrap justify-center gap-3 scroll-stagger">
                    <button class="category-chip inline-flex items-center gap-2 rounded-full border border-stone-200 bg-amber-500 px-5 py-2.5 text-sm font-semibold text-stone-950 transition shadow-sm active" onclick="filterKategori('semua', this)">
                        <i class="fas fa-layer-group"></i> Semua
                    </button>
                    @foreach($kategoris as $kategori)
                    <button class="category-chip inline-flex items-center gap-2 rounded-full border border-stone-200 bg-white px-5 py-2.5 text-sm font-semibold text-stone-600 transition hover:border-amber-400 hover:text-amber-600 shadow-sm" onclick="filterKategori('{{ $kategori->nama }}', this)">
                        @switch($kategori->nama)
                            @case('Pelajaran')
                                <i class="fas fa-book"></i>
                                @break
                            @case('Fiksi')
                                <i class="fas fa-book-open"></i>
                                @break
                            @case('Sains')
                                <i class="fas fa-flask"></i>
                                @break
                            @case('Sejarah')
                                <i class="fas fa-landmark"></i>
                                @break
                            @case('Agama')
                                <i class="fas fa-moon"></i>
                                @break
                            @case('Bahasa')
                                <i class="fas fa-language"></i>
                                @break
                            @default
                                <i class="fas fa-book"></i>
                        @endswitch
                        {{ $kategori->nama }}
                    </button>
                    @endforeach
                </div>

                <div id="bukuGrid" class="grid gap-4 sm:gap-6 scroll-stagger grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"></div>
                
                <div id="paginationContainer" class="mt-12 flex flex-wrap justify-center items-center gap-2 scroll-fade-in"></div>
            </div>
        </section>

        <section id="layanan" class="scroll-fade-in relative px-4 py-16 sm:py-24 sm:px-6 lg:px-8 bg-stone-100 border-t border-stone-200">
            <div class="mx-auto max-w-7xl">
                <div class="text-center mb-16 scroll-fade-in">
                    <h2 class="text-2xl font-bold text-stone-900 sm:text-3xl lg:text-4xl">Layanan Perpustakaan</h2>
                    <p class="mt-4 text-stone-500 max-w-2xl mx-auto">
                        Berbagai fasilitas dan kemudahan yang kami sediakan untuk mendukung kegiatan literasi dan pembelajaran siswa.
                    </p>
                </div>

                <div class="grid gap-6 sm:gap-8 sm:grid-cols-2 lg:grid-cols-4 scroll-stagger">
                    <div class="group relative overflow-hidden rounded-2xl bg-white p-6 sm:p-8 border border-stone-100 shadow-sm transition-all hover:shadow-xl hover:-translate-y-1 hover:border-amber-200">
                        <div class="mb-6 inline-flex h-14 w-14 items-center justify-center rounded-xl bg-amber-100 text-amber-600 text-2xl transition-transform group-hover:scale-110 group-hover:rotate-3">
                            <i class="fas fa-book-reader"></i>
                        </div>
                        <h3 class="mb-3 text-xl font-bold text-stone-900">Ruang Baca Digital</h3>
                        <p class="text-sm text-stone-500 leading-relaxed">Akses e-book dan modul pembelajaran secara langsung melalui perangkat Anda tanpa batas waktu.</p>
                    </div>

                    <div class="group relative overflow-hidden rounded-2xl bg-white p-6 sm:p-8 border border-stone-100 shadow-sm transition-all hover:shadow-xl hover:-translate-y-1 hover:border-amber-200">
                        <div class="mb-6 inline-flex h-14 w-14 items-center justify-center rounded-xl bg-sky-100 text-sky-600 text-2xl transition-transform group-hover:scale-110 group-hover:rotate-3">
                            <i class="fas fa-hand-holding-heart"></i>
                        </div>
                        <h3 class="mb-3 text-xl font-bold text-stone-900">Peminjaman Buku</h3>
                        <p class="text-sm text-stone-500 leading-relaxed">Layanan peminjaman buku fisik secara mandiri untuk mempermudah reservasi sebelum ke perpustakaan.</p>
                    </div>

                    <div class="group relative overflow-hidden rounded-2xl bg-white p-6 sm:p-8 border border-stone-100 shadow-sm transition-all hover:shadow-xl hover:-translate-y-1 hover:border-amber-200">
                        <div class="mb-6 inline-flex h-14 w-14 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 text-2xl transition-transform group-hover:scale-110 group-hover:rotate-3">
                            <i class="fas fa-laptop-code"></i>
                        </div>
                        <h3 class="mb-3 text-xl font-bold text-stone-900">Fasilitas Komputer</h3>
                        <p class="text-sm text-stone-500 leading-relaxed">Tersedia unit komputer dengan akses internet cepat untuk keperluan riset dan tugas sekolah siswa.</p>
                    </div>

                    <div class="group relative overflow-hidden rounded-2xl bg-white p-6 sm:p-8 border border-stone-100 shadow-sm transition-all hover:shadow-xl hover:-translate-y-1 hover:border-amber-200">
                        <div class="mb-6 inline-flex h-14 w-14 items-center justify-center rounded-xl bg-rose-100 text-rose-600 text-2xl transition-transform group-hover:scale-110 group-hover:rotate-3">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <h3 class="mb-3 text-xl font-bold text-stone-900">Klinik Referensi</h3>
                        <p class="text-sm text-stone-500 leading-relaxed">Bantuan penelusuran informasi dan rujukan tugas dari pustakawan untuk mendukung efektivitas studi.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="pengumuman" class="scroll-fade-in relative bg-white px-4 py-16 sm:py-20 sm:px-6 lg:px-8 border-y border-stone-200">
            <div class="mx-auto max-w-7xl">
                <h2 class="text-2xl font-bold text-stone-900 sm:text-3xl lg:text-4xl scroll-fade-in">Informasi & Pengumuman</h2>
                <p class="mt-4 text-base text-stone-500 scroll-fade-in" style="transition-delay: 0.2s;">
                    Update terbaru mengenai perpustakaan dan koleksi buku
                </p>

                <div class="mt-12 grid gap-6 scroll-stagger sm:grid-cols-2 lg:grid-cols-3">
                    @forelse($pengumumans as $index => $info)
                        @php
                            $gradient = match($info->tipe_warna) {
                                'emerald' => 'from-emerald-400 to-teal-500',
                                'sky' => 'from-sky-400 to-blue-500',
                                default => 'from-amber-400 to-amber-500',
                            };
                            $icon = match($info->tipe_warna) {
                                'emerald' => 'fa-check-circle',
                                'sky' => 'fa-clock',
                                default => 'fa-bullhorn',
                            };
                            $delay = $index * 100;
                        @endphp
                        <article class="overflow-hidden rounded-2xl border border-stone-100 bg-stone-50 shadow-sm transition hover:shadow-lg hover:-translate-y-1 scroll-scale-up" style="transition-delay: {{ $delay }}ms;">
                            <div class="h-32 bg-gradient-to-r {{ $gradient }}"></div>
                            <div class="p-6 -mt-8">
                                <span class="inline-flex items-center justify-center h-12 w-12 rounded-xl bg-white shadow-md text-stone-600 mb-4 text-xl">
                                    <i class="fas {{ $icon }}"></i>
                                </span>
                                <h3 class="text-lg font-bold text-stone-900">{{ $info->judul_pengumuman }}</h3>
                                <p class="mt-2 text-sm text-stone-500 leading-relaxed">{{ Str::limit($info->isi_informasi, 120) }}</p>
                                <p class="mt-4 text-xs font-semibold text-stone-400">{{ $info->tanggal_publish?->format('d M Y') }}</p>
                            </div>
                        </article>
                    @empty
                        <div class="sm:col-span-2 lg:col-span-3 rounded-2xl border border-stone-100 bg-stone-50 p-8 text-center text-stone-500">
                            Belum ada informasi atau pengumuman terbaru.
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <section id="struktur" class="scroll-fade-in relative px-4 py-16 sm:py-24 sm:px-6 lg:px-8 bg-stone-100 border-b border-stone-200">
            <div class="mx-auto max-w-7xl">
                <div class="text-center mb-12 scroll-fade-in">
                    <h2 class="text-2xl font-bold text-stone-900 sm:text-3xl lg:text-4xl">Struktur Organisasi</h2>
                    <p class="mt-4 text-stone-500 max-w-2xl mx-auto">
                        Tim pengelola Perpustakaan Digital SMP Negeri 4 Jember yang berdedikasi melayani kebutuhan literasi Anda.
                    </p>
                </div>

                <p class="mb-3 text-center text-xs font-medium text-stone-400 lg:hidden">
                    <i class="fas fa-arrows-left-right mr-1"></i> Geser untuk melihat struktur lengkap
                </p>

                <div class="org-tree-wrapper overflow-x-auto pb-10 w-full scroll-scale-up">
                    @if($strukturs->isEmpty())
                        <div class="rounded-3xl border border-stone-200 bg-white p-12 text-center text-stone-500 shadow-sm">
                            Belum ada data struktur organisasi. Silakan tambahkan melalui panel admin.
                        </div>
                    @else
                        @php
                            $levels = $strukturs->groupBy('level')->sortKeys();
                            $topLevel = $levels->get(1, collect());
                            $secondLevel = $levels->get(2, collect());
                            $thirdLevel = $levels->get(3, collect());
                            $fourthLevel = $levels->get(4, collect());
                        @endphp

                        <div class="org-tree min-w-[900px] w-full">
                            <ul>
                                @foreach($topLevel as $top)
                                    <li>
                                        <div class="org-node group">
                                            <div class="relative w-24 h-24 rounded-full border-4 border-rose-500 p-1 bg-white shadow-xl mx-auto z-10 transition-transform duration-300 group-hover:scale-105 group-hover:-translate-y-2">
                                                <img src="{{ $top->foto ? asset('storage/'.$top->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($top->nama) . '&background=ffe4e6&color=e11d48&font-size=0.33' }}" alt="{{ $top->nama }}" class="w-full h-full rounded-full object-cover">
                                            </div>
                                            <div class="bg-gradient-to-br from-rose-500 to-pink-600 text-white rounded-2xl px-6 py-4 shadow-lg min-w-[200px] text-center relative z-0 -mt-6 pt-8 border border-white/20 transition-all duration-300 group-hover:shadow-rose-500/30">
                                                <h4 class="font-bold text-sm tracking-wide">{{ $top->nama }}</h4>
                                                <p class="text-xs text-rose-100 mt-1 font-medium bg-black/10 inline-block px-3 py-1 rounded-full">({{ $top->jabatan }})</p>
                                            </div>
                                        </div>

                                        @if($secondLevel->isNotEmpty())
                                            <ul>
                                                @foreach($secondLevel as $second)
                                                    <li>
                                                        <div class="org-node group">
                                                            <div class="relative w-20 h-20 rounded-full border-4 border-amber-500 p-1 bg-white shadow-lg mx-auto z-10 transition-transform duration-300 group-hover:scale-105 group-hover:-translate-y-2">
                                                                <img src="{{ $second->foto ? asset('storage/'.$second->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($second->nama) . '&background=fef3c7&color=d97706&font-size=0.33' }}" alt="{{ $second->nama }}" class="w-full h-full rounded-full object-cover">
                                                            </div>
                                                            <div class="bg-gradient-to-br from-amber-500 to-orange-500 text-white rounded-2xl px-5 py-3 shadow-md min-w-[180px] text-center relative z-0 -mt-5 pt-7 border border-white/20 transition-all duration-300 group-hover:shadow-amber-500/30">
                                                                <h4 class="font-bold text-sm">{{ $second->nama }}</h4>
                                                                <p class="text-[11px] text-amber-100 mt-1 font-medium bg-black/10 inline-block px-2.5 py-0.5 rounded-full">({{ $second->jabatan }})</p>
                                                            </div>
                                                        </div>

                                                        @if($thirdLevel->isNotEmpty())
                                                            <ul>
                                                                @foreach($thirdLevel as $third)
                                                                    <li>
                                                                        <div class="org-node group">
                                                                            <div class="relative w-16 h-16 rounded-full border-[3px] border-emerald-400 p-1 bg-white shadow mx-auto z-10 transition-transform duration-300 group-hover:scale-105 group-hover:-translate-y-2">
                                                                                <img src="{{ $third->foto ? asset('storage/'.$third->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($third->nama) . '&background=d1fae5&color=059669&font-size=0.33' }}" alt="{{ $third->nama }}" class="w-full h-full rounded-full object-cover">
                                                                            </div>
                                                                            <div class="bg-gradient-to-br from-emerald-400 to-teal-500 text-white rounded-xl px-4 py-2 shadow min-w-[140px] text-center relative z-0 -mt-4 pt-5 border border-white/20 transition-all duration-300 group-hover:shadow-emerald-500/30">
                                                                                <h4 class="font-bold text-xs">{{ $third->nama }}</h4>
                                                                                <p class="text-[10px] text-emerald-50 mt-1">({{ $third->jabatan }})</p>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <section id="kontak" class="scroll-fade-in relative px-4 py-16 sm:py-24 sm:px-6 lg:px-8 bg-stone-50">
            <div class="mx-auto max-w-7xl">
                <div class="grid gap-12 lg:gap-16 lg:grid-cols-2">
                    <div class="scroll-slide-left">
                        <h2 class="text-2xl font-bold text-stone-900 sm:text-3xl lg:text-4xl">Hubungi Kami</h2>
                        <p class="mt-4 text-base text-stone-500">
                            Memiliki pertanyaan atau saran untuk perpustakaan? Jangan ragu untuk menghubungi kami melalui berbagai saluran komunikasi di bawah ini.
                        </p>

                        <div class="mt-10 space-y-6">
                            <div class="flex gap-4 p-4 rounded-xl bg-white border border-stone-100 shadow-sm">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-600 text-xl">
                                    <i class="fas fa-phone-alt"></i>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="font-bold text-stone-900">Telepon</h3>
                                    <p class="mt-1 text-sm text-stone-500">+62 331 123456</p>
                                </div>
                            </div>

                            <div class="flex gap-4 p-4 rounded-xl bg-white border border-stone-100 shadow-sm">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-600 text-xl">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="font-bold text-stone-900">Email</h3>
                                    <p class="mt-1 text-sm text-stone-500 break-words">perpustakaan@smpn4jember.sch.id</p>
                                </div>
                            </div>

                            <div class="flex gap-4 p-4 rounded-xl bg-white border border-stone-100 shadow-sm">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-600 text-xl">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="font-bold text-stone-900">Alamat</h3>
                                    <p class="mt-1 text-sm text-stone-500">Jl. Nusa Indah No.14, Krajan, Jemberlor, Kec. Patrang, Kabupaten Jember, Jawa Timur 68118</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="scroll-slide-right">
                        <div class="rounded-2xl bg-white p-5 sm:p-8 border border-stone-200 shadow-lg">
                            <h3 class="text-xl sm:text-2xl font-bold text-stone-900">Statistik Perpustakaan</h3>
                            <p class="text-sm text-stone-500 mt-2">Data perkembangan buku & pengunjung tahun 2026</p>
                            
                            <div class="relative w-full h-40 sm:h-56 mt-8">
                                @php
                                    // Normalize chart values for SVG (0-200 range)
                                    $maxValue = max($chartValues) > 0 ? max($chartValues) : 100;
                                    $normalizedValues = array_map(function($val) use ($maxValue) {
                                        return 200 - ($val / $maxValue * 180);
                                    }, $chartValues);
                                    
                                    // Generate path coordinates
                                    $xStep = 500 / (count($chartValues) - 1);
                                    $pathPoints = [];
                                    foreach ($normalizedValues as $i => $y) {
                                        $x = $i * $xStep;
                                        $pathPoints[] = "{$x},{$y}";
                                    }
                                    $pathData = implode(' ', $pathPoints);
                                    
                                    // Create area path
                                    $areaPath = "M0,200 L" . $pathData . " L500,200 Z";
                                    
                                    // Create line path
                                    $linePath = "M" . $pathData;
                                    
                                    // Generate circle points
                                    $circlePoints = [];
                                    foreach ($normalizedValues as $i => $y) {
                                        $x = $i * $xStep;
                                        $circlePoints[] = ['x' => $x, 'y' => $y];
                                    }
                                @endphp
                                <svg viewBox="0 0 500 200" class="w-full h-full drop-shadow-sm" preserveAspectRatio="none">
                                    <defs>
                                        <linearGradient id="lineGrad" x1="0" y1="0" x2="0" y2="1">
                                            <stop offset="0%" stop-color="#f59e0b" stop-opacity="0.3"/>
                                            <stop offset="100%" stop-color="#f59e0b" stop-opacity="0"/>
                                        </linearGradient>
                                    </defs>
                                    
                                    <line x1="0" y1="50" x2="500" y2="50" stroke="#f5f5f4" stroke-width="2" />
                                    <line x1="0" y1="100" x2="500" y2="100" stroke="#f5f5f4" stroke-width="2" />
                                    <line x1="0" y1="150" x2="500" y2="150" stroke="#f5f5f4" stroke-width="2" />
                                    
                                    <path d="{{ $areaPath }}" fill="url(#lineGrad)" />
                                    
                                    <path d="{{ $linePath }}" fill="none" stroke="#f59e0b" stroke-width="4" stroke-linecap="round" />
                                    
                                    @foreach($circlePoints as $point)
                                    <circle cx="{{ $point['x'] }}" cy="{{ $point['y'] }}" r="5" fill="#fff" stroke="#f59e0b" stroke-width="3" />
                                    @endforeach
                                </svg>
                                
                                <div class="flex justify-between text-[10px] sm:text-xs font-semibold text-stone-400 mt-3 px-2">
                                    @foreach($chartLabels as $label)
                                    <span>{{ $label }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="stats-grid grid grid-cols-2 gap-4 sm:gap-6 mt-8 pt-6 border-t border-stone-100">
                                <div>
                                    <span class="text-xs font-semibold text-stone-400 uppercase tracking-wider">Total Buku</span>
                                    <p class="text-xl sm:text-2xl font-bold text-stone-900 mt-1">{{ number_format($statistics['totalBuku']) }}</p>
                                </div>
                                <div>
                                    <span class="text-xs font-semibold text-stone-400 uppercase tracking-wider">Anggota Aktif</span>
                                    <p class="text-xl sm:text-2xl font-bold text-stone-900 mt-1">{{ number_format($statistics['anggotaAktif']) }}</p>
                                </div>
                                <div>
                                    <span class="text-xs font-semibold text-stone-400 uppercase tracking-wider">Pengunjung</span>
                                    <p class="text-xl sm:text-2xl font-bold text-stone-900 mt-1">{{ number_format($statistics['pengunjungBulanIni']) }} <span class="text-sm font-normal text-amber-500">/Bulan</span></p>
                                </div>
                                <div>
                                    <span class="text-xs font-semibold text-stone-400 uppercase tracking-wider">Kepuasan</span>
                                    <p class="text-xl sm:text-2xl font-bold text-stone-900 mt-1">{{ $statistics['kepuasan'] }}%</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="mt-0">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:py-16 sm:px-6 lg:px-8">
            <div class="grid gap-10 sm:gap-12 sm:grid-cols-2 lg:grid-cols-4">
                <div class="footer-section scroll-fade-in">
                    <div class="flex items-center gap-3 mb-6">
                        <img src="{{ asset('images/smp.png') }}" alt="Logo" class="h-12 w-12 rounded-xl bg-white object-contain p-1.5 shadow-md">
                        <div>
                            <h4 class="font-bold text-white text-lg">Perpus Digital</h4>
                            <p class="text-xs font-medium text-amber-400">SMP Negeri 4 Jember</p>
                        </div>
                    </div>
                    <p class="text-sm text-stone-400 leading-relaxed">
                        Menyediakan akses digital ke koleksi buku terlengkap untuk mendukung pembelajaran siswa dan mengembangkan budaya membaca di sekolah.
                    </p>
                </div>

                <div class="footer-section scroll-fade-in" style="transition-delay: 0.1s;">
                    <h3>Katalog</h3>
                    <ul class="space-y-3 mt-4">
                        <li><a href="#koleksi" class="footer-link"><i class="fas fa-layer-group"></i> Semua Koleksi</a></li>
                        <li><a href="#koleksi" class="footer-link"><i class="fas fa-flask"></i> Sains & Teknologi</a></li>
                        <li><a href="#koleksi" class="footer-link"><i class="fas fa-book-open"></i> Fiksi & Literasi</a></li>
                        <li><a href="#koleksi" class="footer-link"><i class="fas fa-landmark"></i> Sejarah & Budaya</a></li>
                        <li><a href="#koleksi" class="footer-link"><i class="fas fa-language"></i> Referensi Bahasa</a></li>
                    </ul>
                </div>

                <div class="footer-section scroll-fade-in" style="transition-delay: 0.2s;">
                    <h3>Layanan</h3>
                    <ul class="space-y-3 mt-4">
                        <li><a href="{{ route('login') }}" class="footer-link"><i class="fas fa-sign-in-alt"></i> Login Admin</a></li>
                        <li><a href="#kontak" class="footer-link"><i class="fas fa-phone-alt"></i> Hubungi Kami</a></li>
                        <li><a href="#pengumuman" class="footer-link"><i class="fas fa-bullhorn"></i> Pengumuman</a></li>
                        <li><a href="#koleksi" class="footer-link"><i class="fas fa-search"></i> Cari Buku</a></li>
                    </ul>
                </div>

                <div class="footer-section scroll-fade-in" style="transition-delay: 0.3s;">
                    <h3>Ikuti Kami</h3>
                    <div class="footer-social-links mt-4">
                        <a href="https://www.facebook.com/profile.php?id=100069959832475" target="_blank" rel="noopener noreferrer" class="footer-social-icon" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://www.instagram.com/smpn4jember/" target="_blank" rel="noopener noreferrer" class="footer-social-icon" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://www.tiktok.com/@snipersmpn4jember" target="_blank" rel="noopener noreferrer" class="footer-social-icon" title="Twitter">
                            <i class="fab fa-tiktok"></i>
                        </a>
                        <a href="https://www.youtube.com/@SMPN4JEMBER" target="_blank" rel="noopener noreferrer" class="footer-social-icon" title="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                    <p class="mt-6 text-sm text-stone-400">
                        <strong>Email Bantuan:</strong><br/>
                        <a href="mailto:perpustakaan@smpn4jember.sch.id" class="footer-bottom-link mt-1 inline-block break-words">perpustakaan@smpn4jember.sch.id</a>
                    </p>
                </div>
            </div>

            <div class="footer-divider"></div>

            <div class="footer-bottom rounded-2xl px-6">
                <div class="grid gap-4 md:grid-cols-2 items-center">
                    <div class="footer-bottom-text">
                        &copy; 2024 Perpustakaan Digital SMPN 4 Jember. Semua hak cipta dilindungi.
                    </div>
                    <div class="flex gap-6 text-sm md:justify-end">
                        <a href="#" class="footer-bottom-link">Kebijakan Privasi</a>
                        <a href="#" class="footer-bottom-link">Syarat & Ketentuan</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <div id="modalOverlay" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-stone-950/60 backdrop-blur-sm" onclick="tutupModal(event)" aria-hidden="true">
        <div class="relative max-h-[90vh] max-w-2xl w-full overflow-y-auto rounded-3xl bg-white shadow-2xl mx-4">
            <button type="button" onclick="tutupModalBtn()" class="absolute right-4 top-4 z-10 rounded-full bg-white/20 backdrop-blur-md p-2 text-white transition hover:bg-white/40 focus:outline-none">
                <i class="fas fa-times text-lg w-6 h-6 flex items-center justify-center"></i>
            </button>

            <div id="modalCover" class="h-40 sm:h-56 w-full"></div>

            <div class="p-5 sm:p-8">
                <div class="flex flex-col sm:flex-row gap-6">
                    <div class="h-28 w-20 sm:h-32 sm:w-24 shrink-0 rounded-xl border-4 border-white bg-stone-100 shadow-lg -mt-12 sm:-mt-16 overflow-hidden flex items-center justify-center">
                        <img id="modalInitial" src="" alt="Sampul" class="h-full w-full object-cover">
                    </div>

                    <div class="flex-1 sm:-mt-2 min-w-0">
                        <h2 id="modalJudul" class="text-xl sm:text-2xl font-bold text-stone-900 break-words"></h2>
                        <p id="modalPenulis" class="mt-1 text-sm font-medium text-amber-600"></p>

                        <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
                            <div class="flex flex-col bg-stone-50 p-3 rounded-xl border border-stone-100 min-w-0">
                                <span class="text-xs font-semibold text-stone-400">Kategori</span>
                                <span id="modalKategori" class="font-bold text-stone-900 text-sm mt-1 truncate"></span>
                            </div>
                            <div class="flex flex-col bg-stone-50 p-3 rounded-xl border border-stone-100 min-w-0">
                                <span class="text-xs font-semibold text-stone-400">Tahun</span>
                                <span id="modalTahun" class="font-bold text-stone-900 text-sm mt-1 truncate"></span>
                            </div>
                            <div class="flex flex-col bg-stone-50 p-3 rounded-xl border border-stone-100 min-w-0">
                                <span class="text-xs font-semibold text-stone-400">Penerbit</span>
                                <span id="modalPenerbit" class="font-bold text-stone-900 text-sm mt-1 truncate" title=""></span>
                            </div>
                            <div class="flex flex-col bg-stone-50 p-3 rounded-xl border border-stone-100 min-w-0">
                                <span class="text-xs font-semibold text-stone-400">Status</span>
                                <span id="modalStatus" class="font-bold text-teal-600 text-sm mt-1 truncate"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-lg font-bold text-stone-900">Sinopsis</h3>
                    <p id="modalDeskripsi" class="mt-3 text-sm text-stone-500 leading-relaxed"></p>
                </div>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <button type="button" onclick="tutupModalBtn()" class="w-full rounded-xl bg-amber-500 px-6 py-3.5 font-bold text-stone-900 shadow-lg shadow-amber-500/30 transition hover:bg-amber-400 hover:-translate-y-1">
                        Kembali
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ===== SCROLL PROGRESS BAR =====
        window.addEventListener('scroll', () => {
            const scrollTop = window.scrollY;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            const scrollPercent = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
            document.documentElement.style.setProperty('--scroll-progress', scrollPercent + '%');
        });

        // ===== TOGGLE MENU MOBILE =====
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const mobileMenu = document.getElementById('mobileMenu');
        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', () => {
                const isHidden = mobileMenu.classList.toggle('hidden');
                mobileMenuButton.setAttribute('aria-expanded', String(!isHidden));
            });
            mobileMenu.querySelectorAll('a').forEach((link) => {
                link.addEventListener('click', () => {
                    mobileMenu.classList.add('hidden');
                    mobileMenuButton.setAttribute('aria-expanded', 'false');
                });
            });
        }

        // ===== SCROLL ANIMATIONS (1 ARAH) =====
        if ('IntersectionObserver' in window) {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries, observerInstance) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observerInstance.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.scroll-fade-in, .scroll-slide-left, .scroll-slide-right, .scroll-scale-up, .scroll-stagger').forEach(el => {
                observer.observe(el);
            });
        } else {
            // Fallback for very old browsers without IntersectionObserver support
            document.documentElement.classList.add('no-js');
        }

        // ===== WARNA GRADIEN UNTUK SETIAP KATEGORI =====
        const warnaKategori = {
            'Pelajaran': 'linear-gradient(135deg, #f59e0b, #ec4899)',
            'Fiksi': 'linear-gradient(135deg, #ec4899, #f472b6)',
            'Sains': 'linear-gradient(135deg, #ef4444, #f97316)',
            'Sejarah': 'linear-gradient(135deg, #8b5cf6, #a78bfa)',
            'Agama': 'linear-gradient(135deg, #06b6d4, #0891b2)',
            'Bahasa': 'linear-gradient(135deg, #fbbf24, #f59e0b)',
        };

        // ===== DATA BUKU DARI DATABASE =====
        const dataBuku = @json($bukuData);

        let kategoriFilter = 'semua';
        let pencarianTeks = '';
        
        // ===== VARIABEL PAGINATION =====
        let currentPage = 1;
        const itemsPerPage = 8; // Batasan 8 card per halaman

        const activeChipClasses = ['bg-amber-500', 'text-stone-950', 'border-amber-500'];
        const inactiveChipClasses = ['bg-white', 'text-stone-600', 'border-stone-200'];

        function renderBuku() {
            const grid = document.getElementById('bukuGrid');
            const paginationContainer = document.getElementById('paginationContainer');
            
            const filtered = dataBuku.filter((buku) => {
                const cocokKategori = kategoriFilter === 'semua' || buku.kategori === kategoriFilter;
                const kataKunci = `${buku.judul} ${buku.penulis} ${buku.kategori}`.toLowerCase();
                return cocokKategori && kataKunci.includes(pencarianTeks);
            });

            if (filtered.length === 0) {
                grid.innerHTML = `
                    <div class="col-span-full rounded-2xl border-2 border-dashed border-stone-200 bg-white p-8 sm:p-12 text-center">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-stone-50 text-stone-400 text-2xl mb-4">
                            <i class="fas fa-search-minus"></i>
                        </div>
                        <p class="font-bold text-stone-900 text-lg">Buku tidak ditemukan</p>
                        <p class="mt-1 text-sm text-stone-500">Coba gunakan kata kunci atau kategori pencarian lain.</p>
                    </div>
                `;
                paginationContainer.innerHTML = ''; // Sembunyikan pagination jika kosong
                return;
            }

            // ===== LOGIKA PAGINATION =====
            const totalPages = Math.ceil(filtered.length / itemsPerPage);
            
            // Pastikan halaman tidak keluar batas jika hasil pencarian berubah
            if (currentPage > totalPages) currentPage = totalPages;
            if (currentPage < 1) currentPage = 1;

            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const paginatedData = filtered.slice(startIndex, endIndex);

            // Render Card Buku berdasarkan halaman saat ini
            grid.innerHTML = paginatedData.map((buku, index) => {
                const statusClass = buku.status === 'tersedia'
                    ? 'border-teal-200 bg-teal-50 text-teal-700'
                    : 'border-rose-200 bg-rose-50 text-rose-700';
                const statusLabel = buku.status === 'tersedia' ? 'Tersedia' : 'Dipinjam';
                
                return `
                    <article class="group overflow-hidden rounded-2xl border border-stone-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl hover:border-amber-200 scroll-scale-up" style="transition-delay: ${(index % 8) * 0.05}s;">
                        <button type="button" class="block h-full w-full text-left" onclick="bukaModal(${buku.id})">
                            <div class="relative flex h-36 sm:h-56 items-center justify-center p-3 text-white bg-stone-100 overflow-hidden">
                                <img src="${buku.sampul}" alt="${buku.judul}" class="h-full w-full object-cover hover:scale-110 transition-transform duration-300" onerror="this.src='{{ asset('images/placeholder-book.svg') }}'">
                                <span class="absolute left-2 top-2 sm:left-4 sm:top-4 rounded-full border ${statusClass} px-2 py-0.5 sm:px-3 sm:py-1 text-[10px] sm:text-xs font-bold shadow-sm backdrop-blur-sm bg-white/90">${statusLabel}</span>
                                <div class="absolute bottom-0 left-0 right-0 h-12 bg-gradient-to-t from-black/30 to-transparent"></div>
                            </div>
                            <div class="p-3 sm:p-5">
                                <p class="book-title min-h-[40px] sm:min-h-[48px] text-sm sm:text-base font-bold leading-snug text-stone-900 group-hover:text-amber-600 transition-colors">${buku.judul}</p>
                                <p class="mt-1 truncate text-xs sm:text-sm font-medium text-stone-500">${buku.penulis}</p>
                                <div class="mt-3 sm:mt-5 flex items-center justify-between gap-2 pt-3 sm:pt-4 border-t border-stone-100">
                                    <span class="inline-flex items-center gap-1 sm:gap-1.5 rounded-lg bg-stone-50 px-2 py-1 text-[10px] sm:text-xs font-semibold text-stone-600 border border-stone-100 truncate">
                                        <i class="fas fa-tag text-amber-500"></i> ${buku.kategori}
                                    </span>
                                    <span class="text-[10px] sm:text-xs font-bold text-amber-600 flex items-center gap-1 group-hover:translate-x-1 transition-transform shrink-0">
                                        Detail <i class="fas fa-arrow-right"></i>
                                    </span>
                                </div>
                            </div>
                        </button>
                    </article>
                `;
            }).join('');

            renderPaginationControls(totalPages);
        }

        // ===== FUNGSI RENDER TOMBOL PAGINATION =====
        function renderPaginationControls(totalPages) {
            const container = document.getElementById('paginationContainer');
            
            if (totalPages <= 1) {
                container.innerHTML = '';
                return;
            }

            let html = '';

            // Tombol Prev
            const prevDisabled = currentPage === 1 ? 'opacity-50 cursor-not-allowed bg-stone-100' : 'hover:bg-amber-400 hover:text-stone-900 transition bg-white';
            html += `<button onclick="${currentPage === 1 ? '' : `changePage(${currentPage - 1})`}" class="px-3 sm:px-4 py-2 rounded-xl border border-stone-200 text-sm font-bold text-stone-600 shadow-sm flex items-center gap-1 sm:gap-2 ${prevDisabled}">
                        <i class="fas fa-chevron-left"></i> <span class="hidden sm:inline">Prev</span>
                     </button>`;

            // Angka Halaman
            for (let i = 1; i <= totalPages; i++) {
                const activeClass = i === currentPage 
                    ? 'bg-amber-500 text-stone-950 border-amber-500 shadow-md scale-105' 
                    : 'bg-white text-stone-600 border-stone-200 hover:bg-stone-50 transition';
                html += `<button onclick="changePage(${i})" class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl border text-sm font-bold shadow-sm flex items-center justify-center transition-all ${activeClass}">${i}</button>`;
            }

            // Tombol Next
            const nextDisabled = currentPage === totalPages ? 'opacity-50 cursor-not-allowed bg-stone-100' : 'hover:bg-amber-400 hover:text-stone-900 transition bg-white';
            html += `<button onclick="${currentPage === totalPages ? '' : `changePage(${currentPage + 1})`}" class="px-3 sm:px-4 py-2 rounded-xl border border-stone-200 text-sm font-bold text-stone-600 shadow-sm flex items-center gap-1 sm:gap-2 ${nextDisabled}">
                        <span class="hidden sm:inline">Next</span> <i class="fas fa-chevron-right"></i>
                     </button>`;

            container.innerHTML = html;
        }

        // ===== FUNGSI GANTI HALAMAN =====
        function changePage(page) {
            currentPage = page;
            renderBuku();
            // Gulir kembali ke awal section koleksi secara mulus
            document.getElementById('koleksi').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function filterKategori(kategori, element) {
            kategoriFilter = kategori;
            currentPage = 1; // Reset ke halaman 1 saat filter diubah

            document.querySelectorAll('.category-chip').forEach((chip) => {
                chip.classList.remove(...activeChipClasses);
                chip.classList.add(...inactiveChipClasses);
            });

            element.classList.remove(...inactiveChipClasses);
            element.classList.add(...activeChipClasses);
            renderBuku();
        }

        function cariBuku() {
            pencarianTeks = document.getElementById('searchInput').value.toLowerCase().trim();
            currentPage = 1; // Reset ke halaman 1 saat pencarian
            renderBuku();
        }

        function scrollToCollection() {
            cariBuku();
            document.getElementById('koleksi').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function bukaModal(id) {
            const buku = dataBuku.find((item) => item.id === id);

            if (!buku) return;

            const warna = warnaKategori[buku.kategori] || 'linear-gradient(135deg, #f59e0b, #ec4899)';
            document.getElementById('modalCover').style.background = warna;

            const modalImg = document.getElementById('modalInitial');
            modalImg.src = buku.sampul;
            modalImg.onerror = function() {
                this.src = '{{ asset('images/placeholder-book.svg') }}';
            };
            document.getElementById('modalJudul').textContent = buku.judul;
            document.getElementById('modalPenulis').textContent = `Karya: ${buku.penulis}`;
            document.getElementById('modalKategori').innerHTML = `<i class="fas fa-tag text-amber-500 mr-1"></i> ${buku.kategori}`;
            document.getElementById('modalTahun').innerHTML = `<i class="far fa-calendar-alt text-amber-500 mr-1"></i> ${buku.tahun}`;
            document.getElementById('modalPenerbit').innerHTML = `<i class="far fa-building text-amber-500 mr-1"></i> ${buku.penerbit}`;
            document.getElementById('modalPenerbit').title = buku.penerbit;
            
            const statusBadge = buku.status === 'tersedia' 
                ? '<i class="fas fa-check-circle mr-1"></i> Tersedia' 
                : '<i class="fas fa-times-circle text-rose-500 mr-1"></i> <span class="text-rose-600">Dipinjam</span>';
            document.getElementById('modalStatus').innerHTML = statusBadge;
            
            document.getElementById('modalDeskripsi').textContent = buku.deskripsi;

            const overlay = document.getElementById('modalOverlay');
            overlay.classList.remove('hidden');
            overlay.classList.add('flex');
            overlay.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden'; 
        }

        function tutupModal(event) {
            if (event.target === document.getElementById('modalOverlay')) {
                tutupModalBtn();
            }
        }

        function tutupModalBtn() {
            const overlay = document.getElementById('modalOverlay');
            overlay.classList.add('hidden');
            overlay.classList.remove('flex');
            overlay.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = ''; 
        }

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                tutupModalBtn();
            }
        });

        // Init
        renderBuku();
    </script>
</body>
</html>