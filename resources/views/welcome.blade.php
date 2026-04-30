<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perpustakaan Digital - SMP Negeri 4 Jember</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --hijau: #1a5c3a;
            --hijau-tua: #0e3d26;
            --hijau-muda: #2d7a52;
            --kuning: #f5c842;
            --krem: #fdf6e3;
            --krem-gelap: #f0e6c8;
            --teks: #1c1c1c;
            --abu: #6b7280;
            --putih: #ffffff;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--krem);
            color: var(--teks);
            min-height: 100vh;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            background: var(--hijau-tua);
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 20px rgba(0,0,0,0.3);
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-emblem {
            width: 40px;
            height: 40px;
            background: var(--kuning);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Playfair Display', serif;
            font-weight: 900;
            font-size: 16px;
            color: var(--hijau-tua);
            flex-shrink: 0;
        }

        .logo-text {
            display: flex;
            flex-direction: column;
        }

        .logo-text .sekolah {
            font-size: 11px;
            color: rgba(255,255,255,0.6);
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .logo-text .nama {
            font-size: 14px;
            font-weight: 600;
            color: var(--putih);
            line-height: 1.2;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-login {
            background: var(--kuning);
            color: var(--hijau-tua);
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }

        .btn-login:hover {
            background: #e8b800;
            transform: translateY(-1px);
        }

        /* ===== HERO ===== */
        .hero {
            background: linear-gradient(135deg, var(--hijau-tua) 0%, var(--hijau) 50%, var(--hijau-muda) 100%);
            padding: 60px 2rem 80px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -60px;
            right: -60px;
            width: 300px;
            height: 300px;
            border: 60px solid rgba(245,200,66,0.08);
            border-radius: 50%;
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -80px;
            width: 400px;
            height: 400px;
            border: 80px solid rgba(245,200,66,0.05);
            border-radius: 50%;
        }

        .hero-badge {
            display: inline-block;
            background: rgba(245,200,66,0.15);
            border: 1px solid rgba(245,200,66,0.3);
            color: var(--kuning);
            font-size: 12px;
            font-weight: 500;
            padding: 5px 16px;
            border-radius: 20px;
            margin-bottom: 20px;
            letter-spacing: 0.05em;
        }

        .hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 900;
            color: var(--putih);
            line-height: 1.15;
            margin-bottom: 16px;
        }

        .hero h1 span {
            color: var(--kuning);
        }

        .hero p {
            font-size: 1rem;
            color: rgba(255,255,255,0.75);
            max-width: 520px;
            margin: 0 auto 36px;
            line-height: 1.6;
        }

        .search-wrapper {
            max-width: 560px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .search-box {
            display: flex;
            background: var(--putih);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 40px rgba(0,0,0,0.3);
        }

        .search-icon {
            display: flex;
            align-items: center;
            padding: 0 16px;
            color: var(--abu);
        }

        .search-input {
            flex: 1;
            border: none;
            outline: none;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 15px;
            padding: 16px 0;
            color: var(--teks);
            background: transparent;
        }

        .search-input::placeholder { color: #aaa; }

        .search-btn {
            background: var(--hijau);
            color: white;
            border: none;
            padding: 0 24px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .search-btn:hover { background: var(--hijau-tua); }

        /* ===== STATS ===== */
        .stats-bar {
            background: var(--putih);
            border-bottom: 1px solid var(--krem-gelap);
            padding: 20px 2rem;
        }

        .stats-inner {
            max-width: 960px;
            margin: 0 auto;
            display: flex;
            justify-content: center;
            gap: 60px;
            flex-wrap: wrap;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--hijau);
        }

        .stat-label {
            font-size: 12px;
            color: var(--abu);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* ===== MAIN CONTENT ===== */
        .main {
            max-width: 1000px;
            margin: 0 auto;
            padding: 48px 2rem;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--hijau-tua);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title::before {
            content: '';
            display: block;
            width: 4px;
            height: 24px;
            background: var(--kuning);
            border-radius: 2px;
        }

        .lihat-semua {
            font-size: 13px;
            color: var(--hijau);
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 4px;
            transition: gap 0.2s;
        }

        .lihat-semua:hover { gap: 8px; }

        /* ===== KATEGORI CHIP ===== */
        .kategori-chips {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 40px;
        }

        .chip {
            padding: 8px 18px;
            border-radius: 24px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            border: 2px solid transparent;
            user-select: none;
        }

        .chip.aktif {
            background: var(--hijau);
            color: var(--putih);
            border-color: var(--hijau);
        }

        .chip:not(.aktif) {
            background: var(--putih);
            color: var(--abu);
            border-color: var(--krem-gelap);
        }

        .chip:not(.aktif):hover {
            border-color: var(--hijau);
            color: var(--hijau);
        }

        /* ===== BUKU GRID ===== */
        .buku-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 24px;
            margin-bottom: 56px;
        }

        .buku-card {
            background: var(--putih);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            transition: all 0.25s;
            cursor: pointer;
            position: relative;
        }

        .buku-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.15);
        }

        .buku-cover {
            width: 100%;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 56px;
            position: relative;
        }

        .buku-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: var(--kuning);
            color: var(--hijau-tua);
            font-size: 10px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 4px;
            text-transform: uppercase;
        }

        .buku-badge.tersedia {
            background: #d1fae5;
            color: #065f46;
        }

        .buku-badge.dipinjam {
            background: #fee2e2;
            color: #991b1b;
        }

        .buku-info {
            padding: 14px;
        }

        .buku-judul {
            font-size: 14px;
            font-weight: 600;
            color: var(--teks);
            line-height: 1.4;
            margin-bottom: 4px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .buku-penulis {
            font-size: 12px;
            color: var(--abu);
            margin-bottom: 8px;
        }

        .buku-kategori-tag {
            display: inline-block;
            background: rgba(26,92,58,0.08);
            color: var(--hijau);
            font-size: 11px;
            font-weight: 500;
            padding: 2px 8px;
            border-radius: 4px;
        }

        /* ===== PENGUMUMAN ===== */
        .pengumuman-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin-bottom: 56px;
        }

        .pengumuman-item {
            background: var(--putih);
            border-radius: 10px;
            padding: 18px 22px;
            display: flex;
            align-items: flex-start;
            gap: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            border-left: 4px solid var(--hijau);
            transition: box-shadow 0.2s;
        }

        .pengumuman-item:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        }

        .pengumuman-ikon {
            font-size: 24px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .pengumuman-konten h4 {
            font-size: 15px;
            font-weight: 600;
            color: var(--teks);
            margin-bottom: 4px;
        }

        .pengumuman-konten p {
            font-size: 13px;
            color: var(--abu);
            line-height: 1.5;
        }

        .pengumuman-tanggal {
            font-size: 11px;
            color: #aaa;
            margin-top: 6px;
        }

        /* ===== MODAL DETAIL BUKU ===== */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.55);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-overlay.aktif {
            display: flex;
            animation: fadeIn 0.2s ease;
        }

        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        .modal {
            background: var(--putih);
            border-radius: 16px;
            max-width: 480px;
            width: 100%;
            overflow: hidden;
            animation: slideUp 0.25s ease;
        }

        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .modal-header {
            padding: 24px 24px 0;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .modal-tutup {
            background: var(--krem-gelap);
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            font-size: 18px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--abu);
            transition: background 0.2s;
            flex-shrink: 0;
        }

        .modal-tutup:hover { background: #e0d4b0; }

        .modal-body { padding: 20px 24px 28px; }

        .modal-cover {
            font-size: 80px;
            text-align: center;
            padding: 20px;
            background: var(--krem);
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .modal-judul {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--teks);
            margin-bottom: 4px;
        }

        .modal-penulis {
            font-size: 14px;
            color: var(--abu);
            margin-bottom: 16px;
        }

        .modal-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 20px;
        }

        .meta-item label {
            font-size: 11px;
            color: #aaa;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: block;
            margin-bottom: 2px;
        }

        .meta-item span {
            font-size: 14px;
            font-weight: 500;
            color: var(--teks);
        }

        .modal-deskripsi {
            font-size: 13px;
            color: var(--abu);
            line-height: 1.6;
            padding-top: 16px;
            border-top: 1px solid var(--krem-gelap);
        }

        /* ===== FOOTER ===== */
        .footer {
            background: var(--hijau-tua);
            color: rgba(255,255,255,0.6);
            text-align: center;
            padding: 32px 2rem;
            font-size: 13px;
        }

        .footer strong { color: var(--putih); }
        .footer .divider { margin: 0 10px; opacity: 0.4; }

        /* ===== NO RESULT ===== */
        .no-result {
            text-align: center;
            padding: 40px 20px;
            color: var(--abu);
            display: none;
            grid-column: 1 / -1;
        }

        .no-result.tampil { display: block; }
        .no-result-ikon { font-size: 48px; margin-bottom: 12px; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 600px) {
            .stats-inner { gap: 30px; }
            .buku-grid { grid-template-columns: repeat(2, 1fr); gap: 14px; }
            .nav-actions .btn-login span { display: none; }
            .logo-text .sekolah { display: none; }
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="logo-area">
            <div class="logo-emblem">S4</div>
            <div class="logo-text">
                <span class="sekolah">Perpustakaan Digital</span>
                <span class="nama">SMP Negeri 4 Jember</span>
            </div>
        </div>
        <div class="nav-actions">
            <a href="{{ route('login') }}" class="btn-login">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                <span>Login Admin</span>
            </a>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <div class="hero-badge">📚 Perpustakaan Digital Sekolah</div>
        <h1>Temukan Buku <span>Favoritmu</span><br>di Sini</h1>
        <p>Akses ribuan koleksi buku pelajaran, fiksi, dan non-fiksi perpustakaan SMP Negeri 4 Jember kapan saja dan di mana saja.</p>
        <div class="search-wrapper">
            <div class="search-box">
                <span class="search-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </span>
                <input type="text" class="search-input" id="searchInput" placeholder="Cari judul buku, penulis, atau kategori..." oninput="cariБuku()">
                <button class="search-btn" onclick="cariБuku()">Cari</button>
            </div>
        </div>
    </section>

    <!-- STATS BAR -->
    <div class="stats-bar">
        <div class="stats-inner">
            <div class="stat-item">
                <div class="stat-number">1.240</div>
                <div class="stat-label">Total Koleksi</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">48</div>
                <div class="stat-label">Dipinjam Bulan Ini</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">12</div>
                <div class="stat-label">Kategori</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">98%</div>
                <div class="stat-label">Tersedia</div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <main class="main">

        <!-- KATEGORI -->
        <div class="kategori-chips" id="kategoriChips">
            <div class="chip aktif" onclick="filterKategori('semua', this)">Semua</div>
            <div class="chip" onclick="filterKategori('Pelajaran', this)">📖 Pelajaran</div>
            <div class="chip" onclick="filterKategori('Fiksi', this)">🌟 Fiksi</div>
            <div class="chip" onclick="filterKategori('Sains', this)">🔬 Sains</div>
            <div class="chip" onclick="filterKategori('Sejarah', this)">🏛️ Sejarah</div>
            <div class="chip" onclick="filterKategori('Agama', this)">🕌 Agama</div>
            <div class="chip" onclick="filterKategori('Bahasa', this)">✏️ Bahasa</div>
        </div>

        <!-- KOLEKSI TERBARU -->
        <div class="section-header">
            <h2 class="section-title">Koleksi Buku</h2>
            <a href="#" class="lihat-semua">Lihat semua →</a>
        </div>

        <div class="buku-grid" id="bukuGrid">
            <!-- Diisi JS -->
        </div>

        <!-- PENGUMUMAN -->
        <div class="section-header">
            <h2 class="section-title">Pengumuman Perpustakaan</h2>
        </div>

        <div class="pengumuman-list">
            <div class="pengumuman-item">
                <div class="pengumuman-ikon">📢</div>
                <div class="pengumuman-konten">
                    <h4>Jam Operasional Perpustakaan</h4>
                    <p>Perpustakaan buka Senin–Jumat pukul 07.00–15.00 WIB. Sabtu buka pukul 08.00–12.00 WIB.</p>
                    <div class="pengumuman-tanggal">Diperbarui: 20 April 2026</div>
                </div>
            </div>
            <div class="pengumuman-item" style="border-color: var(--kuning);">
                <div class="pengumuman-ikon">📚</div>
                <div class="pengumuman-konten">
                    <h4>Koleksi Buku Baru Telah Tiba!</h4>
                    <p>Sebanyak 85 judul buku baru telah ditambahkan ke koleksi perpustakaan. Segera kunjungi untuk membacanya.</p>
                    <div class="pengumuman-tanggal">24 April 2026</div>
                </div>
            </div>
            <div class="pengumuman-item" style="border-color: #ef4444;">
                <div class="pengumuman-ikon">⚠️</div>
                <div class="pengumuman-konten">
                    <h4>Pengingat Pengembalian Buku</h4>
                    <p>Batas waktu pengembalian buku semester ini adalah 30 Mei 2026. Denda keterlambatan Rp500/hari/buku.</p>
                    <div class="pengumuman-tanggal">25 April 2026</div>
                </div>
            </div>
        </div>

    </main>

    <!-- MODAL DETAIL BUKU -->
    <div class="modal-overlay" id="modalOverlay" onclick="tutupModal(event)">
        <div class="modal">
            <div class="modal-header">
                <div></div>
                <button class="modal-tutup" onclick="tutupModalBtn()">✕</button>
            </div>
            <div class="modal-body">
                <div class="modal-cover" id="modalCover">📖</div>
                <div class="modal-judul" id="modalJudul">-</div>
                <div class="modal-penulis" id="modalPenulis">-</div>
                <div class="modal-meta">
                    <div class="meta-item">
                        <label>Kategori</label>
                        <span id="modalKategori">-</span>
                    </div>
                    <div class="meta-item">
                        <label>Tahun Terbit</label>
                        <span id="modalTahun">-</span>
                    </div>
                    <div class="meta-item">
                        <label>Penerbit</label>
                        <span id="modalPenerbit">-</span>
                    </div>
                    <div class="meta-item">
                        <label>Status</label>
                        <span id="modalStatus">-</span>
                    </div>
                </div>
                <div class="modal-deskripsi" id="modalDeskripsi">-</div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="footer">
        <strong>SMP Negeri 4 Jember</strong>
        <span class="divider">|</span>
        Jl. Hayam Wuruk No. 56, Jember, Jawa Timur
        <br><br>
        &copy; 2026 Perpustakaan Digital SMPN 4 Jember. Seluruh hak cipta dilindungi.
    </footer>

    <script>
        const dataBuku = [
            { id:1, judul:"Matematika untuk SMP Kelas VII", penulis:"Abdur Rahman As'ari", kategori:"Pelajaran", tahun:2022, penerbit:"Kemendikbud", status:"tersedia", ikon:"📐", warna:"#e8f5e9", deskripsi:"Buku teks Matematika kurikulum Merdeka untuk siswa SMP kelas VII. Dilengkapi latihan soal dan pembahasan." },
            { id:2, judul:"Laskar Pelangi", penulis:"Andrea Hirata", kategori:"Fiksi", tahun:2005, penerbit:"Bentang Pustaka", status:"tersedia", ikon:"🌈", warna:"#fff8e1", deskripsi:"Novel karya Andrea Hirata tentang perjuangan anak-anak Belitung menggapai mimpi lewat pendidikan." },
            { id:3, judul:"IPA Terpadu Kelas VIII", penulis:"Tim Penyusun", kategori:"Sains", tahun:2023, penerbit:"Kemendikbud", status:"dipinjam", ikon:"🔬", warna:"#e3f2fd", deskripsi:"Buku pelajaran Ilmu Pengetahuan Alam terpadu untuk SMP kelas VIII kurikulum Merdeka." },
            { id:4, judul:"Sejarah Kebudayaan Islam", penulis:"Ahmad Syalaby", kategori:"Agama", tahun:2020, penerbit:"Pustaka Al-Kautsar", status:"tersedia", ikon:"🕌", warna:"#f3e5f5", deskripsi:"Mengulas perjalanan panjang kebudayaan Islam dari masa Rasulullah hingga era modern." },
            { id:5, judul:"Bahasa Indonesia Kelas IX", penulis:"Agus Trianto", kategori:"Bahasa", tahun:2021, penerbit:"Kemendikbud", status:"tersedia", ikon:"✏️", warna:"#fce4ec", deskripsi:"Buku teks Bahasa Indonesia untuk siswa SMP kelas IX. Mencakup keterampilan membaca, menulis, berbicara, dan menyimak." },
            { id:6, judul:"Bumi Manusia", penulis:"Pramoedya Ananta Toer", kategori:"Fiksi", tahun:1980, penerbit:"Hasta Mitra", status:"tersedia", ikon:"🌍", warna:"#e8f5e9", deskripsi:"Novel sejarah karya Pramoedya Ananta Toer, mengisahkan kehidupan di Hindia Belanda awal abad ke-20." },
            { id:7, judul:"Sejarah Indonesia Modern", penulis:"M.C. Ricklefs", kategori:"Sejarah", tahun:2008, penerbit:"Serambi", status:"dipinjam", ikon:"🏛️", warna:"#fff3e0", deskripsi:"Buku sejarah Indonesia dari abad ke-16 hingga abad ke-21 yang ditulis secara komprehensif." },
            { id:8, judul:"Fisika Dasar", penulis:"Halliday & Resnick", kategori:"Sains", tahun:2019, penerbit:"Erlangga", status:"tersedia", ikon:"⚡", warna:"#e3f2fd", deskripsi:"Buku referensi Fisika Dasar yang membahas mekanika, termodinamika, optika, dan listrik magnet." },
            { id:9, judul:"Al-Quran Hadis Kelas VII", penulis:"Tim Kemenag", kategori:"Agama", tahun:2022, penerbit:"Kemenag RI", status:"tersedia", ikon:"📖", warna:"#f3e5f5", deskripsi:"Buku pelajaran Al-Quran Hadis untuk siswa MTs / SMP Islam kelas VII." },
            { id:10, judul:"Grammar in Use", penulis:"Raymond Murphy", kategori:"Bahasa", tahun:2019, penerbit:"Cambridge", status:"tersedia", ikon:"🔤", warna:"#fce4ec", deskripsi:"Buku referensi tata bahasa Inggris yang sangat populer untuk tingkat menengah. Dilengkapi latihan soal." },
        ];

        let kategoriFiltir = 'semua';
        let pencarianTeks = '';

        function renderBuku() {
            const grid = document.getElementById('bukuGrid');
            const filtered = dataBuku.filter(b => {
                const cocokkategori = kategoriFiltir === 'semua' || b.kategori === kategoriFiltir;
                const cocokCari = b.judul.toLowerCase().includes(pencarianTeks) ||
                                  b.penulis.toLowerCase().includes(pencarianTeks) ||
                                  b.kategori.toLowerCase().includes(pencarianTeks);
                return cocokkategori && cocokCari;
            });

            if (filtered.length === 0) {
                grid.innerHTML = `<div class="no-result tampil"><div class="no-result-ikon">🔍</div><p>Buku tidak ditemukan.<br>Coba kata kunci lain.</p></div>`;
                return;
            }

            grid.innerHTML = filtered.map(b => `
                <div class="buku-card" onclick="bukaNModal(${b.id})">
                    <div class="buku-cover" style="background:${b.warna}">
                        <span>${b.ikon}</span>
                        <span class="buku-badge ${b.status}">${b.status === 'tersedia' ? 'Tersedia' : 'Dipinjam'}</span>
                    </div>
                    <div class="buku-info">
                        <div class="buku-judul">${b.judul}</div>
                        <div class="buku-penulis">${b.penulis}</div>
                        <span class="buku-kategori-tag">${b.kategori}</span>
                    </div>
                </div>
            `).join('');
        }

        function filterKategori(kat, el) {
            kategoriFiltir = kat;
            document.querySelectorAll('.chip').forEach(c => c.classList.remove('aktif'));
            el.classList.add('aktif');
            renderBuku();
        }

        function cariБuku() {
            pencarianTeks = document.getElementById('searchInput').value.toLowerCase();
            renderBuku();
        }

        function bukaNModal(id) {
            const b = dataBuku.find(x => x.id === id);
            if (!b) return;
            document.getElementById('modalCover').textContent = b.ikon;
            document.getElementById('modalCover').style.background = b.warna;
            document.getElementById('modalJudul').textContent = b.judul;
            document.getElementById('modalPenulis').textContent = 'oleh ' + b.penulis;
            document.getElementById('modalKategori').textContent = b.kategori;
            document.getElementById('modalTahun').textContent = b.tahun;
            document.getElementById('modalPenerbit').textContent = b.penerbit;
            document.getElementById('modalStatus').textContent = b.status === 'tersedia' ? '✅ Tersedia' : '❌ Dipinjam';
            document.getElementById('modalDeskripsi').textContent = b.deskripsi;
            document.getElementById('modalOverlay').classList.add('aktif');
            document.body.style.overflow = 'hidden';
        }

        function tutupModal(e) {
            if (e.target === document.getElementById('modalOverlay')) tutupModalBtn();
        }

        function tutupModalBtn() {
            document.getElementById('modalOverlay').classList.remove('aktif');
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', e => { if (e.key === 'Escape') tutupModalBtn(); });

        renderBuku();
    </script>
</body>
</html>