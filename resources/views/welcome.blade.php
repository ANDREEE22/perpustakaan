<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perpustakaan Digital - SMP Negeri 4 Jember</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .hero-backdrop {
            background-image:
                linear-gradient(90deg, rgba(12, 10, 9, 0.94), rgba(12, 10, 9, 0.76) 48%, rgba(12, 10, 9, 0.52)),
                url('{{ asset('images/skh.png') }}');
            background-position: center;
            background-size: cover;
        }

        .book-title {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            overflow: hidden;
        }

        .shelf-line {
            background: linear-gradient(90deg, transparent, rgba(245, 158, 11, 0.65), transparent);
        }

        @media (prefers-reduced-motion: no-preference) {
            .rise-in {
                animation: riseIn 0.55s ease both;
            }

            @keyframes riseIn {
                from {
                    opacity: 0;
                    transform: translateY(18px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        }
    </style>
</head>
<body class="min-h-screen bg-stone-50 font-sans text-stone-950 antialiased">
    <header class="fixed inset-x-0 top-0 z-50 border-b border-white/15 bg-stone-950/80 text-white backdrop-blur">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex min-w-0 items-center gap-3">
                <img src="{{ asset('images/smp.png') }}" alt="Logo SMP Negeri 4 Jember" class="h-10 w-10 shrink-0 rounded-md bg-white object-contain p-1">
                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold">Perpustakaan Digital</p>
                    <p class="truncate text-xs text-white/60">SMP Negeri 4 Jember</p>
                </div>
            </a>

            <nav class="hidden items-center gap-6 text-sm font-medium text-white/70 md:flex">
                <a href="#koleksi" class="transition hover:text-white">Koleksi</a>
                <a href="#layanan" class="transition hover:text-white">Layanan</a>
                <a href="#pengumuman" class="transition hover:text-white">Info</a>
            </nav>

            <a href="{{ route('login') }}" class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-amber-400 px-4 text-sm font-semibold text-stone-950 transition hover:bg-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-200 focus:ring-offset-2 focus:ring-offset-stone-950">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="m10 17 5-5-5-5" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H3" />
                </svg>
                <span class="hidden sm:inline">Login Admin</span>
            </a>
        </div>
    </header>

    <main>
        <section class="hero-backdrop relative isolate min-h-[690px] overflow-hidden pt-24 text-white">
            <div class="absolute inset-0 -z-10 bg-[linear-gradient(180deg,transparent,rgba(250,250,249,0.08)_70%,#fafaf9_100%)]"></div>

            <div class="mx-auto flex max-w-7xl flex-col gap-10 px-4 pb-14 pt-14 sm:px-6 lg:px-8 lg:pt-20">
                <div class="max-w-4xl rise-in">
                    <div class="inline-flex items-center gap-3 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-sm font-semibold text-amber-200 backdrop-blur">
                        <img src="{{ asset('images/smp.png') }}" alt="" class="h-8 w-8 rounded-full bg-white object-contain p-1">
                        Katalog digital sekolah
                    </div>

                    <h1 class="mt-8 max-w-4xl text-4xl font-semibold leading-[1.06] sm:text-6xl lg:text-7xl">
                        Perpustakaan Digital SMPN 4 Jember
                    </h1>
                    <p class="mt-6 max-w-2xl text-base leading-8 text-white/75 sm:text-lg">
                        Jelajahi koleksi pelajaran, fiksi, sains, sejarah, agama, dan bahasa dalam satu ruang baca yang cepat, rapi, dan nyaman untuk siswa.
                    </p>

                    <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                        <a href="#koleksi" class="inline-flex h-12 items-center justify-center gap-2 rounded-lg bg-emerald-500 px-5 text-sm font-semibold text-white transition hover:bg-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:ring-offset-2 focus:ring-offset-stone-950">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z" />
                            </svg>
                            Jelajahi Koleksi
                        </a>
                        <a href="#pengumuman" class="inline-flex h-12 items-center justify-center gap-2 rounded-lg border border-white/25 bg-white/10 px-5 text-sm font-semibold text-white backdrop-blur transition hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/50 focus:ring-offset-2 focus:ring-offset-stone-950">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.73 21a2 2 0 0 1-3.46 0" />
                            </svg>
                            Lihat Info
                        </a>
                    </div>
                </div>

                <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_360px] lg:items-end">
                    <div class="rounded-lg border border-white/20 bg-white p-2 shadow-2xl shadow-stone-950/25 rise-in" style="animation-delay: 0.12s">
                        <div class="flex flex-col gap-2 sm:flex-row">
                            <label for="searchInput" class="sr-only">Cari koleksi buku</label>
                            <div class="flex min-h-12 flex-1 items-center gap-3 rounded-md bg-stone-50 px-4 text-stone-500">
                                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="11" cy="11" r="8" />
                                    <path stroke-linecap="round" d="m21 21-4.35-4.35" />
                                </svg>
                                <input id="searchInput" type="search" class="h-12 w-full bg-transparent text-sm text-stone-900 outline-none placeholder:text-stone-400" placeholder="Cari judul, penulis, atau kategori..." oninput="cariBuku()">
                            </div>
                            <button type="button" onclick="scrollToCollection()" class="inline-flex h-12 items-center justify-center rounded-lg bg-stone-950 px-6 text-sm font-semibold text-white transition hover:bg-stone-800 focus:outline-none focus:ring-2 focus:ring-stone-300">
                                Cari Buku
                            </button>
                        </div>
                    </div>

                    <div class="hidden rounded-lg border border-white/20 bg-stone-950/60 p-5 backdrop-blur lg:block rise-in" style="animation-delay: 0.2s">
                        <div class="flex items-end gap-2">
                            <div class="h-28 w-10 rounded-md bg-amber-400"></div>
                            <div class="h-36 w-12 rounded-md bg-emerald-500"></div>
                            <div class="h-24 w-10 rounded-md bg-cyan-400"></div>
                            <div class="h-40 w-12 rounded-md bg-rose-400"></div>
                            <div class="h-32 w-10 rounded-md bg-lime-300"></div>
                            <div class="h-44 w-12 rounded-md bg-sky-400"></div>
                        </div>
                        <div class="shelf-line mt-5 h-px"></div>
                        <p class="mt-4 text-sm font-semibold text-white">Rak pilihan minggu ini</p>
                        <p class="mt-1 text-xs leading-5 text-white/60">10 koleksi contoh siap difilter dan dibuka detailnya.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="border-b border-stone-200 bg-white">
            <div class="mx-auto grid max-w-7xl grid-cols-2 gap-px bg-stone-200 px-4 sm:px-6 lg:grid-cols-4 lg:px-8">
                <div class="bg-white py-6">
                    <p class="text-3xl font-semibold text-stone-950">1.240</p>
                    <p class="mt-1 text-sm text-stone-500">Total koleksi</p>
                </div>
                <div class="bg-white py-6 pl-4 sm:pl-6">
                    <p class="text-3xl font-semibold text-emerald-700">48</p>
                    <p class="mt-1 text-sm text-stone-500">Dipinjam bulan ini</p>
                </div>
                <div class="bg-white py-6 lg:pl-6">
                    <p class="text-3xl font-semibold text-amber-600">12</p>
                    <p class="mt-1 text-sm text-stone-500">Kategori buku</p>
                </div>
                <div class="bg-white py-6 pl-4 sm:pl-6">
                    <p class="text-3xl font-semibold text-cyan-700">98%</p>
                    <p class="mt-1 text-sm text-stone-500">Koleksi tersedia</p>
                </div>
            </div>
        </section>

        <section id="layanan" class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="flex flex-col justify-between gap-4 md:flex-row md:items-end">
                <div>
                    <p class="text-sm font-semibold text-emerald-700">Layanan cepat</p>
                    <h2 class="mt-2 text-3xl font-semibold text-stone-950">Ruang baca yang siap dipakai</h2>
                </div>
                <p class="max-w-xl text-sm leading-6 text-stone-500">
                    Halaman depan ini membantu siswa menemukan buku, melihat status koleksi, dan membaca informasi perpustakaan tanpa masuk ke panel admin.
                </p>
            </div>

            <div class="mt-8 grid gap-4 md:grid-cols-3">
                <article class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:border-emerald-300 hover:shadow-lg">
                    <div class="flex h-11 w-11 items-center justify-center rounded-md bg-emerald-50 text-emerald-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h10" />
                        </svg>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold">Katalog ringkas</h3>
                    <p class="mt-2 text-sm leading-6 text-stone-500">Cari koleksi berdasarkan judul, penulis, atau kategori dari daftar buku unggulan.</p>
                </article>

                <article class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:border-amber-300 hover:shadow-lg">
                    <div class="flex h-11 w-11 items-center justify-center rounded-md bg-amber-50 text-amber-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                            <circle cx="12" cy="12" r="9" />
                        </svg>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold">Info operasional</h3>
                    <p class="mt-2 text-sm leading-6 text-stone-500">Pengumuman jam layanan, koleksi baru, dan pengingat pengembalian tampil jelas.</p>
                </article>

                <article class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:border-cyan-300 hover:shadow-lg">
                    <div class="flex h-11 w-11 items-center justify-center rounded-md bg-cyan-50 text-cyan-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12 11 14 15 10" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold">Status buku</h3>
                    <p class="mt-2 text-sm leading-6 text-stone-500">Label tersedia dan dipinjam membuat siswa cepat memilih buku yang bisa dibaca.</p>
                </article>
            </div>
        </section>

        <section id="koleksi" class="bg-stone-100/80 py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                    <div>
                        <p class="text-sm font-semibold text-emerald-700">Koleksi pilihan</p>
                        <h2 class="mt-2 text-3xl font-semibold text-stone-950">Temukan buku yang kamu butuhkan</h2>
                    </div>
                    <div class="flex flex-wrap gap-2" id="kategoriChips">
                        <button type="button" class="category-chip inline-flex h-10 items-center rounded-full border px-4 text-sm font-semibold transition bg-emerald-950 text-white border-emerald-950" onclick="filterKategori('semua', this)">Semua</button>
                        <button type="button" class="category-chip inline-flex h-10 items-center rounded-full border px-4 text-sm font-semibold transition bg-white text-stone-600 border-stone-200 hover:border-emerald-300" onclick="filterKategori('Pelajaran', this)">Pelajaran</button>
                        <button type="button" class="category-chip inline-flex h-10 items-center rounded-full border px-4 text-sm font-semibold transition bg-white text-stone-600 border-stone-200 hover:border-emerald-300" onclick="filterKategori('Fiksi', this)">Fiksi</button>
                        <button type="button" class="category-chip inline-flex h-10 items-center rounded-full border px-4 text-sm font-semibold transition bg-white text-stone-600 border-stone-200 hover:border-emerald-300" onclick="filterKategori('Sains', this)">Sains</button>
                        <button type="button" class="category-chip inline-flex h-10 items-center rounded-full border px-4 text-sm font-semibold transition bg-white text-stone-600 border-stone-200 hover:border-emerald-300" onclick="filterKategori('Sejarah', this)">Sejarah</button>
                        <button type="button" class="category-chip inline-flex h-10 items-center rounded-full border px-4 text-sm font-semibold transition bg-white text-stone-600 border-stone-200 hover:border-emerald-300" onclick="filterKategori('Agama', this)">Agama</button>
                        <button type="button" class="category-chip inline-flex h-10 items-center rounded-full border px-4 text-sm font-semibold transition bg-white text-stone-600 border-stone-200 hover:border-emerald-300" onclick="filterKategori('Bahasa', this)">Bahasa</button>
                    </div>
                </div>

                <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5" id="bukuGrid"></div>
            </div>
        </section>

        <section id="pengumuman" class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-[360px_minmax(0,1fr)]">
                <div>
                    <p class="text-sm font-semibold text-emerald-700">Pengumuman</p>
                    <h2 class="mt-2 text-3xl font-semibold text-stone-950">Informasi perpustakaan</h2>
                    <p class="mt-4 text-sm leading-6 text-stone-500">Pantau jadwal layanan, koleksi baru, dan pengingat pengembalian buku dari perpustakaan sekolah.</p>
                </div>

                <div class="grid gap-3">
                    <article class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
                        <div class="flex gap-4">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-md bg-emerald-50 text-emerald-700">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                                    <circle cx="12" cy="12" r="9" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-stone-950">Jam Operasional Perpustakaan</h3>
                                <p class="mt-1 text-sm leading-6 text-stone-500">Senin-Jumat pukul 07.00-15.00 WIB. Sabtu pukul 08.00-12.00 WIB.</p>
                                <p class="mt-2 text-xs text-stone-400">Diperbarui: 20 April 2026</p>
                            </div>
                        </div>
                    </article>

                    <article class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
                        <div class="flex gap-4">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-md bg-amber-50 text-amber-700">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-stone-950">Koleksi Buku Baru Telah Tiba</h3>
                                <p class="mt-1 text-sm leading-6 text-stone-500">Sebanyak 85 judul buku baru telah ditambahkan ke koleksi perpustakaan.</p>
                                <p class="mt-2 text-xs text-stone-400">24 April 2026</p>
                            </div>
                        </div>
                    </article>

                    <article class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
                        <div class="flex gap-4">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-md bg-rose-50 text-rose-700">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 17h.01" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-stone-950">Pengingat Pengembalian Buku</h3>
                                <p class="mt-1 text-sm leading-6 text-stone-500">Batas pengembalian buku semester ini adalah 30 Mei 2026. Denda keterlambatan Rp500/hari/buku.</p>
                                <p class="mt-2 text-xs text-stone-400">25 April 2026</p>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </section>
    </main>

    <div class="fixed inset-0 z-[60] hidden items-center justify-center bg-stone-950/70 p-4 backdrop-blur-sm" id="modalOverlay" onclick="tutupModal(event)" aria-hidden="true">
        <div class="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-lg bg-white shadow-2xl">
            <div class="flex items-start justify-between gap-4 border-b border-stone-200 p-5">
                <div>
                    <p class="text-sm font-semibold text-emerald-700" id="modalKategori">Kategori</p>
                    <h2 class="mt-1 text-2xl font-semibold text-stone-950" id="modalJudul">Judul buku</h2>
                </div>
                <button type="button" onclick="tutupModalBtn()" class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-stone-200 text-stone-500 transition hover:bg-stone-100 hover:text-stone-900" aria-label="Tutup detail buku">
                    &times;
                </button>
            </div>
            <div class="grid gap-6 p-5 md:grid-cols-[180px_minmax(0,1fr)]">
                <div class="flex h-64 items-center justify-center rounded-lg p-5 text-white" id="modalCover">
                    <span class="text-center text-5xl font-semibold" id="modalInitial">BK</span>
                </div>
                <div>
                    <p class="text-sm text-stone-500" id="modalPenulis">oleh Penulis</p>
                    <div class="mt-5 grid grid-cols-2 gap-3">
                        <div class="rounded-lg border border-stone-200 p-3">
                            <p class="text-xs font-semibold text-stone-400">Tahun</p>
                            <p class="mt-1 font-semibold text-stone-900" id="modalTahun">-</p>
                        </div>
                        <div class="rounded-lg border border-stone-200 p-3">
                            <p class="text-xs font-semibold text-stone-400">Status</p>
                            <p class="mt-1 font-semibold text-stone-900" id="modalStatus">-</p>
                        </div>
                        <div class="col-span-2 rounded-lg border border-stone-200 p-3">
                            <p class="text-xs font-semibold text-stone-400">Penerbit</p>
                            <p class="mt-1 font-semibold text-stone-900" id="modalPenerbit">-</p>
                        </div>
                    </div>
                    <p class="mt-5 text-sm leading-7 text-stone-600" id="modalDeskripsi">-</p>
                </div>
            </div>
        </div>
    </div>

    <footer class="border-t border-stone-200 bg-white py-8">
        <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 text-sm text-stone-500 sm:px-6 md:flex-row md:items-center md:justify-between lg:px-8">
            <p><strong class="font-semibold text-stone-950">SMP Negeri 4 Jember</strong> - Jl. Nusa Indah, Jember, Jawa Timur</p>
            <p>&copy; 2026 Perpustakaan Digital SMPN 4 Jember.</p>
        </div>
    </footer>

    <script>
        const dataBuku = [
            { id: 1, judul: 'Matematika untuk SMP Kelas VII', penulis: 'Abdur Rahman Asari', kategori: 'Pelajaran', tahun: 2022, penerbit: 'Kemendikbud', status: 'tersedia', kode: 'MT', warna: 'linear-gradient(135deg, #14532d, #14b8a6)', deskripsi: 'Buku teks Matematika kurikulum Merdeka untuk siswa SMP kelas VII. Dilengkapi latihan soal dan pembahasan.' },
            { id: 2, judul: 'Laskar Pelangi', penulis: 'Andrea Hirata', kategori: 'Fiksi', tahun: 2005, penerbit: 'Bentang Pustaka', status: 'tersedia', kode: 'LP', warna: 'linear-gradient(135deg, #92400e, #f59e0b)', deskripsi: 'Novel tentang perjuangan anak-anak Belitung menggapai mimpi lewat pendidikan, persahabatan, dan keberanian.' },
            { id: 3, judul: 'IPA Terpadu Kelas VIII', penulis: 'Tim Penyusun', kategori: 'Sains', tahun: 2023, penerbit: 'Kemendikbud', status: 'dipinjam', kode: 'IP', warna: 'linear-gradient(135deg, #155e75, #38bdf8)', deskripsi: 'Buku pelajaran Ilmu Pengetahuan Alam terpadu untuk SMP kelas VIII kurikulum Merdeka.' },
            { id: 4, judul: 'Sejarah Kebudayaan Islam', penulis: 'Ahmad Syalaby', kategori: 'Agama', tahun: 2020, penerbit: 'Pustaka Al-Kautsar', status: 'tersedia', kode: 'SK', warna: 'linear-gradient(135deg, #166534, #84cc16)', deskripsi: 'Mengulas perjalanan kebudayaan Islam dari masa Rasulullah hingga era modern dengan bahasa yang mudah diikuti.' },
            { id: 5, judul: 'Bahasa Indonesia Kelas IX', penulis: 'Agus Trianto', kategori: 'Bahasa', tahun: 2021, penerbit: 'Kemendikbud', status: 'tersedia', kode: 'BI', warna: 'linear-gradient(135deg, #be123c, #fb7185)', deskripsi: 'Buku teks Bahasa Indonesia untuk kelas IX yang mencakup membaca, menulis, berbicara, dan menyimak.' },
            { id: 6, judul: 'Bumi Manusia', penulis: 'Pramoedya Ananta Toer', kategori: 'Fiksi', tahun: 1980, penerbit: 'Hasta Mitra', status: 'tersedia', kode: 'BM', warna: 'linear-gradient(135deg, #1e3a8a, #60a5fa)', deskripsi: 'Novel sejarah yang mengisahkan kehidupan di Hindia Belanda awal abad ke-20.' },
            { id: 7, judul: 'Sejarah Indonesia Modern', penulis: 'M.C. Ricklefs', kategori: 'Sejarah', tahun: 2008, penerbit: 'Serambi', status: 'dipinjam', kode: 'SI', warna: 'linear-gradient(135deg, #7c2d12, #fdba74)', deskripsi: 'Buku sejarah Indonesia dari abad ke-16 hingga abad ke-21 yang ditulis secara komprehensif.' },
            { id: 8, judul: 'Fisika Dasar', penulis: 'Halliday & Resnick', kategori: 'Sains', tahun: 2019, penerbit: 'Erlangga', status: 'tersedia', kode: 'FD', warna: 'linear-gradient(135deg, #0f172a, #22d3ee)', deskripsi: 'Referensi Fisika Dasar yang membahas mekanika, termodinamika, optika, listrik, dan magnet.' },
            { id: 9, judul: 'Al-Quran Hadis Kelas VII', penulis: 'Tim Kemenag', kategori: 'Agama', tahun: 2022, penerbit: 'Kemenag RI', status: 'tersedia', kode: 'AH', warna: 'linear-gradient(135deg, #365314, #a3e635)', deskripsi: 'Buku pelajaran Al-Quran Hadis untuk siswa kelas VII dengan materi dan latihan terstruktur.' },
            { id: 10, judul: 'Grammar in Use', penulis: 'Raymond Murphy', kategori: 'Bahasa', tahun: 2019, penerbit: 'Cambridge', status: 'tersedia', kode: 'GU', warna: 'linear-gradient(135deg, #312e81, #818cf8)', deskripsi: 'Buku referensi tata bahasa Inggris tingkat menengah yang dilengkapi contoh dan latihan.' },
        ];

        let kategoriFilter = 'semua';
        let pencarianTeks = '';

        const activeChipClasses = ['bg-emerald-950', 'text-white', 'border-emerald-950'];
        const inactiveChipClasses = ['bg-white', 'text-stone-600', 'border-stone-200', 'hover:border-emerald-300'];

        function renderBuku() {
            const grid = document.getElementById('bukuGrid');
            const filtered = dataBuku.filter((buku) => {
                const cocokKategori = kategoriFilter === 'semua' || buku.kategori === kategoriFilter;
                const kataKunci = `${buku.judul} ${buku.penulis} ${buku.kategori}`.toLowerCase();

                return cocokKategori && kataKunci.includes(pencarianTeks);
            });

            if (filtered.length === 0) {
                grid.innerHTML = `
                    <div class="col-span-full rounded-lg border border-dashed border-stone-300 bg-white p-10 text-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-stone-100 text-stone-500">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path stroke-linecap="round" d="m21 21-4.35-4.35"></path>
                            </svg>
                        </div>
                        <p class="mt-4 font-semibold text-stone-950">Buku tidak ditemukan</p>
                        <p class="mt-1 text-sm text-stone-500">Coba kata kunci atau kategori lain.</p>
                    </div>
                `;

                return;
            }

            grid.innerHTML = filtered.map((buku) => {
                const statusClass = buku.status === 'tersedia'
                    ? 'border-emerald-200 bg-emerald-50 text-emerald-700'
                    : 'border-rose-200 bg-rose-50 text-rose-700';
                const statusLabel = buku.status === 'tersedia' ? 'Tersedia' : 'Dipinjam';

                return `
                    <article class="group overflow-hidden rounded-lg border border-stone-200 bg-white shadow-sm transition hover:-translate-y-1 hover:border-emerald-300 hover:shadow-xl">
                        <button type="button" class="block h-full w-full text-left" onclick="bukaModal(${buku.id})">
                            <div class="relative flex h-56 items-center justify-center p-5 text-white" style="background: ${buku.warna}">
                                <span class="absolute left-3 top-3 rounded-full border ${statusClass} px-3 py-1 text-xs font-semibold">${statusLabel}</span>
                                <div class="flex h-24 w-20 items-center justify-center rounded-lg border border-white/30 bg-white/20 text-3xl font-semibold shadow-lg backdrop-blur">
                                    ${buku.kode}
                                </div>
                                <div class="absolute bottom-0 left-5 right-5 h-2 rounded-t-lg bg-white/25"></div>
                            </div>
                            <div class="p-4">
                                <p class="book-title min-h-11 text-sm font-semibold leading-6 text-stone-950">${buku.judul}</p>
                                <p class="mt-2 truncate text-sm text-stone-500">${buku.penulis}</p>
                                <div class="mt-4 flex items-center justify-between gap-3">
                                    <span class="rounded-full bg-stone-100 px-3 py-1 text-xs font-semibold text-stone-600">${buku.kategori}</span>
                                    <span class="text-xs font-semibold text-emerald-700">Detail</span>
                                </div>
                            </div>
                        </button>
                    </article>
                `;
            }).join('');
        }

        function filterKategori(kategori, element) {
            kategoriFilter = kategori;

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
            renderBuku();
        }

        function scrollToCollection() {
            cariBuku();
            document.getElementById('koleksi').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function bukaModal(id) {
            const buku = dataBuku.find((item) => item.id === id);

            if (! buku) {
                return;
            }

            document.getElementById('modalCover').style.background = buku.warna;
            document.getElementById('modalInitial').textContent = buku.kode;
            document.getElementById('modalJudul').textContent = buku.judul;
            document.getElementById('modalPenulis').textContent = `oleh ${buku.penulis}`;
            document.getElementById('modalKategori').textContent = buku.kategori;
            document.getElementById('modalTahun').textContent = buku.tahun;
            document.getElementById('modalPenerbit').textContent = buku.penerbit;
            document.getElementById('modalStatus').textContent = buku.status === 'tersedia' ? 'Tersedia' : 'Dipinjam';
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

        renderBuku();
    </script>
</body>
</html>