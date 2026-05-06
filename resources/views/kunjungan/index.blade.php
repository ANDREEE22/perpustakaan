<x-layouts::app :title="__('Buku Tamu Perpustakaan')">
<div class="flex flex-col gap-6">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <flux:heading size="xl" level="1">Buku Tamu</flux:heading>
            <flux:subheading>Catat kunjungan siswa & guru ke perpustakaan SMPN 4 Jember</flux:subheading>
        </div>
        {{-- Badge tanggal hari ini --}}
        <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="text-amber-600 shrink-0"><rect x="3" y="4" width="18" height="18" rx="2"/><path stroke-linecap="round" d="M16 2v4M8 2v4M3 10h18"/></svg>
            <span class="text-sm font-semibold text-amber-700 dark:text-amber-400" id="tanggal-sekarang"></span>
        </div>
    </div>

    <flux:separator />

    {{-- Flash --}}
    @if(session('success'))
        <div class="flex items-center gap-3 p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Statistik --}}
    <div class="grid grid-cols-3 gap-3">
        <div class="p-4 rounded-xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 text-center">
            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400" style="font-family:'Georgia',serif" id="stat-hari-ini">{{ $stats['hari_ini'] }}</div>
            <div class="text-xs text-zinc-400 mt-1 font-medium">Pengunjung Hari Ini</div>
        </div>
        <div class="p-4 rounded-xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 text-center">
            <div class="text-2xl font-bold text-amber-600 dark:text-amber-400" style="font-family:'Georgia',serif">{{ $stats['bulan_ini'] }}</div>
            <div class="text-xs text-zinc-400 mt-1 font-medium">Bulan Ini</div>
        </div>
        <div class="p-4 rounded-xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 text-center">
            <div class="text-2xl font-bold text-zinc-700 dark:text-zinc-300" style="font-family:'Georgia',serif">{{ $stats['total'] }}</div>
            <div class="text-xs text-zinc-400 mt-1 font-medium">Total Kunjungan</div>
        </div>
    </div>

    {{-- Layout 2 kolom: Form check-in kiri, daftar kanan --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 items-start">

        {{-- ── FORM CHECK-IN (2 kolom dari 5) ── --}}
        <div class="lg:col-span-2 flex flex-col gap-4">
            <div class="p-5 rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 flex flex-col gap-4 sticky top-4">

                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-base">✏️</div>
                    <flux:heading size="sm">Check-in Pengunjung</flux:heading>
                </div>
                <flux:separator variant="subtle" />

                {{-- Search anggota --}}
                <div>
                    <label class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-1.5 block">
                        Cari NISN / Nama Anggota
                    </label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 pointer-events-none shrink-0" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
                        <input
                            type="text"
                            id="kt-search"
                            placeholder="Ketik NISN atau nama..."
                            autocomplete="off"
                            class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 outline-none focus:border-amber-400 dark:focus:border-amber-500 transition-colors"
                        >
                        {{-- Dropdown --}}
                        <div id="kt-dropdown"
                             class="hidden absolute z-40 w-full mt-1 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-lg overflow-hidden max-h-56 overflow-y-auto">
                        </div>
                    </div>
                </div>

                {{-- Card anggota terpilih --}}
                <div id="kt-selected" class="hidden p-3 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full overflow-hidden shrink-0 border border-amber-200 dark:border-amber-700 bg-amber-100 dark:bg-amber-800 flex items-center justify-center">
                            <img id="kt-foto" src="" alt="" class="hidden w-full h-full object-cover">
                            <span id="kt-inisial" class="text-sm font-bold text-amber-700 dark:text-amber-300">A</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-semibold text-sm text-amber-800 dark:text-amber-200 truncate" id="kt-nama">—</div>
                            <div class="text-xs text-amber-500" id="kt-info">—</div>
                        </div>
                        <button type="button" onclick="clearPilihan()" class="text-amber-400 hover:text-amber-600 text-xl leading-none shrink-0">×</button>
                    </div>
                    <input type="hidden" id="kt-anggota-id" value="">
                </div>

                {{-- Keperluan --}}
                <div>
                    <label class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-1.5 block">
                        Keperluan
                    </label>
                    <div class="grid grid-cols-2 gap-2" id="keperluan-grid">
                        @foreach(['Membaca', 'Pinjam Buku', 'Tugas', 'Belajar', 'Kembali Buku', 'Lainnya'] as $k)
                        <button type="button"
                                onclick="pilihKeperluan(this, '{{ $k }}')"
                                class="keperluan-btn py-2 rounded-lg border border-zinc-200 dark:border-zinc-700 text-xs font-semibold text-zinc-500 dark:text-zinc-400 hover:border-amber-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/20 transition-all text-center">
                            {{ $k }}
                        </button>
                        @endforeach
                    </div>
                    <input type="hidden" id="kt-keperluan" value="Umum">
                </div>

                {{-- Tombol check-in --}}
                <button
                    id="btn-checkin"
                    type="button"
                    onclick="doCheckIn()"
                    class="w-full py-3 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-semibold text-sm transition-colors flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Check-in Sekarang
                </button>

                {{-- Pesan error/sukses inline --}}
                <div id="kt-msg" class="hidden text-sm text-center font-medium rounded-xl py-2.5 px-4"></div>

            </div>
        </div>

        {{-- ── DAFTAR KUNJUNGAN (3 kolom dari 5) ── --}}
        <div class="lg:col-span-3 flex flex-col gap-4">

            {{-- Filter tanggal --}}
            <form method="GET" action="{{ route('kunjungan.index') }}" class="flex gap-3 items-center">
                <div class="flex-1">
                    <flux:input
                        type="date"
                        name="tanggal"
                        value="{{ $tanggal->format('Y-m-d') }}"
                        label="Lihat Kunjungan Tanggal"
                    />
                </div>
                <div class="pt-5">
                    <flux:button type="submit" variant="primary" icon="magnifying-glass">Lihat</flux:button>
                </div>
                @if(request('tanggal') && request('tanggal') !== now()->format('Y-m-d'))
                    <div class="pt-5">
                        <flux:button href="{{ route('kunjungan.index') }}" variant="ghost">Hari Ini</flux:button>
                    </div>
                @endif
            </form>

            {{-- Header list --}}
            <div class="flex items-center justify-between">
                <div class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                    📋 Daftar Kunjungan —
                    <span class="text-amber-600">{{ $tanggal->isoFormat('dddd, D MMMM Y') }}</span>
                </div>
                <span class="text-xs text-zinc-400 bg-zinc-100 dark:bg-zinc-800 px-2.5 py-1 rounded-full font-medium" id="jumlah-label">
                    {{ $kunjungan->count() }} orang
                </span>
            </div>

            {{-- List kunjungan --}}
            <div class="flex flex-col gap-2" id="kt-list">
                @forelse($kunjungan as $kv)
                    <div class="kt-item flex items-center gap-3 p-3 rounded-xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 hover:shadow-sm transition-shadow"
                         id="ktitem-{{ $kv->id }}">

                        {{-- Nomor urut --}}
                        <div class="w-6 text-center text-xs font-bold text-zinc-300 dark:text-zinc-600 shrink-0">
                            {{ $loop->iteration }}
                        </div>

                        {{-- Avatar --}}
                        <div class="w-9 h-9 rounded-full overflow-hidden shrink-0 border border-zinc-200 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-xs font-bold text-zinc-500">
                            @if($kv->anggota->foto)
                                <img src="{{ asset('storage/' . $kv->anggota->foto) }}" class="w-full h-full object-cover" alt="">
                            @else
                                {{ strtoupper(substr($kv->anggota->nama_lengkap, 0, 1)) }}
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-semibold text-zinc-800 dark:text-zinc-100 truncate">
                                {{ $kv->anggota->nama_lengkap }}
                            </div>
                            <div class="text-xs text-zinc-400">
                                {{ $kv->anggota->nomor_induk }}
                                @if($kv->anggota->kelas) · {{ $kv->anggota->kelas }} @endif
                            </div>
                        </div>

                        {{-- Keperluan --}}
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full shrink-0
                            @php
                                $warna = match($kv->keperluan) {
                                    'Membaca'      => 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400',
                                    'Pinjam Buku'  => 'bg-amber-50 text-amber-600 dark:bg-amber-900/20 dark:text-amber-400',
                                    'Tugas'        => 'bg-purple-50 text-purple-600 dark:bg-purple-900/20 dark:text-purple-400',
                                    'Belajar'      => 'bg-green-50 text-green-600 dark:bg-green-900/20 dark:text-green-400',
                                    'Kembali Buku' => 'bg-teal-50 text-teal-600 dark:bg-teal-900/20 dark:text-teal-400',
                                    default        => 'bg-zinc-100 text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400',
                                };
                            @endphp
                            {{ $warna }}">
                            {{ $kv->keperluan ?? 'Umum' }}
                        </span>

                        {{-- Jam --}}
                        <div class="text-xs font-mono text-zinc-400 shrink-0 w-10 text-right">
                            {{ substr($kv->jam_masuk, 0, 5) }}
                        </div>

                        {{-- Hapus --}}
                        <form action="{{ route('kunjungan.destroy', $kv->id) }}" method="POST"
                              onsubmit="return confirm('Hapus data kunjungan ini?')" class="shrink-0">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="w-7 h-7 rounded-lg flex items-center justify-center text-zinc-300 dark:text-zinc-600 hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-900/20 transition-colors">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>

                    </div>
                @empty
                    <div class="text-center py-12 text-zinc-400" id="kt-empty">
                        <div class="text-4xl mb-3">📭</div>
                        <p class="font-medium text-zinc-500">Belum ada kunjungan</p>
                        <p class="text-sm mt-1">
                            @if($tanggal->isToday())
                                Check-in pengunjung pertama hari ini menggunakan form di sebelah kiri.
                            @else
                                Tidak ada kunjungan pada tanggal {{ $tanggal->isoFormat('D MMMM Y') }}.
                            @endif
                        </p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>

</div>

{{-- ── Modal sukses check-in ── --}}
<div id="modal-sukses"
     class="hidden fixed inset-0 z-50 bg-black/40 backdrop-blur-sm flex items-center justify-center p-4"
     onclick="if(event.target===this)document.getElementById('modal-sukses').classList.add('hidden')">
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-2xl p-6 w-full max-w-sm text-center shadow-2xl"
         style="animation: modalIn 0.25s ease">
        <div class="text-5xl mb-3">✅</div>
        <flux:heading size="lg" class="mb-1">Check-in Berhasil!</flux:heading>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4 leading-relaxed" id="ms-desc"></p>
        <button onclick="document.getElementById('modal-sukses').classList.add('hidden')"
            class="w-full py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold transition-colors">
            OK, Lanjut
        </button>
    </div>
</div>

<style>
@keyframes modalIn {
    from { opacity:0; transform: scale(0.94) translateY(10px); }
    to   { opacity:1; transform: scale(1) translateY(0); }
}
.keperluan-btn.aktif {
    border-color: #d97706;
    color: #d97706;
    background: #fffbeb;
}
.dark .keperluan-btn.aktif {
    border-color: #d97706;
    color: #fbbf24;
    background: rgba(217,119,6,0.15);
}
</style>

<script>
// ── Tanggal live ────────────────────────────────────────────────
(function() {
    const days   = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    const now    = new Date();
    const el     = document.getElementById('tanggal-sekarang');
    if (el) el.textContent = days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();
})();

// ── Data anggota langsung dari PHP — tidak perlu fetch ──────────
const DATA_ANGGOTA = {!! json_encode(
    \App\Models\Anggota::orderBy('nama_lengkap')
        ->get(['id', 'nomor_induk', 'nama_lengkap', 'kelas', 'foto'])
        ->toArray()
) !!};

// ── State ───────────────────────────────────────────────────────
let selectedAnggotaId   = null;
let selectedKeperluan   = 'Umum';
let urutan              = {{ $kunjungan->count() }};

// ── Debounce ────────────────────────────────────────────────────
function debounce(fn, d) { let t; return (...a) => { clearTimeout(t); t = setTimeout(() => fn(...a), d); }; }

// ── CSRF ────────────────────────────────────────────────────────
const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content ?? '';

// ── Search anggota ──────────────────────────────────────────────
const searchEl    = document.getElementById('kt-search');
const dropdownEl  = document.getElementById('kt-dropdown');

searchEl.addEventListener('input', function () {
    const q = this.value.trim().toLowerCase();
    if (q.length < 1) { dropdownEl.classList.add('hidden'); return; }

    const hasil = DATA_ANGGOTA.filter(a =>
        a.nama_lengkap.toLowerCase().includes(q) ||
        String(a.nomor_induk).toLowerCase().includes(q)
    ).slice(0, 8);

    if (!hasil.length) {
        dropdownEl.innerHTML = `
            <div class="px-4 py-3 text-center">
                <p class="text-sm text-zinc-400">Tidak ditemukan. Pastikan anggota sudah terdaftar.</p>
                <a href="{{ route('anggota.create') }}" class="text-xs text-blue-500 hover:underline mt-1 block">+ Daftar anggota baru</a>
            </div>`;
    } else {
        dropdownEl.innerHTML = hasil.map(a => {
            const avatarHtml = a.foto
                ? `<img src="/storage/${escHtml(a.foto)}" class="w-full h-full object-cover">`
                : escHtml(a.nama_lengkap.charAt(0).toUpperCase());
            return `
            <button type="button"
                    data-id="${a.id}"
                    data-nama="${escHtml(a.nama_lengkap)}"
                    data-nisn="${escHtml(a.nomor_induk)}"
                    data-kelas="${escHtml(a.kelas ?? '')}"
                    data-foto="${escHtml(a.foto ?? '')}"
                    class="anggota-item w-full text-left px-4 py-2.5 hover:bg-zinc-50 dark:hover:bg-zinc-700 flex items-center gap-3 border-b border-zinc-100 dark:border-zinc-700 last:border-0 transition-colors cursor-pointer">
                <div class="w-8 h-8 rounded-full overflow-hidden border border-zinc-200 bg-amber-100 flex items-center justify-center text-xs font-bold text-amber-700 shrink-0">
                    ${avatarHtml}
                </div>
                <div>
                    <div class="text-sm font-semibold text-zinc-800 dark:text-zinc-100">${escHtml(a.nama_lengkap)}</div>
                    <div class="text-xs text-zinc-400">${escHtml(a.nomor_induk)}${a.kelas ? ' · ' + escHtml(a.kelas) : ' · Guru/Staf'}</div>
                </div>
            </button>`;
        }).join('');

        // event delegation pakai mousedown agar tidak kalah dengan blur
        dropdownEl.querySelectorAll('.anggota-item').forEach(btn => {
            btn.addEventListener('mousedown', function(e) {
                e.preventDefault();
                pilihAnggota(
                    this.dataset.id,
                    this.dataset.nama,
                    this.dataset.nisn,
                    this.dataset.kelas,
                    this.dataset.foto || null
                );
            });
        });
    }
    dropdownEl.classList.remove('hidden');
});

function pilihAnggota(id, nama, nisn, kelas, foto) {
    selectedAnggotaId = id;
    document.getElementById('kt-anggota-id').value = id;
    document.getElementById('kt-nama').textContent  = nama;
    document.getElementById('kt-info').textContent  = nisn + (kelas ? ' · Kelas ' + kelas : ' · Guru/Staf');
    document.getElementById('kt-inisial').textContent = nama.charAt(0).toUpperCase();

    const fotoEl    = document.getElementById('kt-foto');
    const inisialEl = document.getElementById('kt-inisial');
    if (foto) {
        fotoEl.src = '/storage/' + foto;
        fotoEl.classList.remove('hidden');
        inisialEl.classList.add('hidden');
    } else {
        fotoEl.classList.add('hidden');
        inisialEl.classList.remove('hidden');
    }

    document.getElementById('kt-selected').classList.remove('hidden');
    searchEl.value = '';
    dropdownEl.classList.add('hidden');
    document.getElementById('btn-checkin').disabled = false;
    document.getElementById('kt-msg').classList.add('hidden');
}

function clearPilihan() {
    selectedAnggotaId = null;
    document.getElementById('kt-anggota-id').value = '';
    document.getElementById('kt-selected').classList.add('hidden');
    document.getElementById('btn-checkin').disabled = true;
    document.getElementById('kt-msg').classList.add('hidden');
}

// ── Keperluan ───────────────────────────────────────────────────
function pilihKeperluan(btn, nilai) {
    document.querySelectorAll('.keperluan-btn').forEach(b => b.classList.remove('aktif'));
    btn.classList.add('aktif');
    selectedKeperluan = nilai;
    document.getElementById('kt-keperluan').value = nilai;
}

// ── Check-in ────────────────────────────────────────────────────
async function doCheckIn() {
    if (!selectedAnggotaId) return;

    const btn = document.getElementById('btn-checkin');
    const msg = document.getElementById('kt-msg');
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg> Memproses...';
    msg.classList.add('hidden');

    try {
        const res  = await fetch('/api/check-in', {
            method: 'POST',
            headers: {
                'Content-Type':     'application/json',
                'Accept':           'application/json',
                'X-CSRF-TOKEN':     csrf(),
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                anggota_id: selectedAnggotaId,
                keperluan:  selectedKeperluan,
            }),
        });

        const data = await res.json();

        if (!res.ok) {
            // Duplikat atau error lain
            msg.textContent = data.message ?? 'Terjadi kesalahan.';
            msg.className   = 'text-sm text-center font-medium rounded-xl py-2.5 px-4 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400';
            msg.classList.remove('hidden');
            btn.disabled = false;
            btn.innerHTML = '<svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Check-in Sekarang';
            return;
        }

        // ── Sukses: tambah row ke list tanpa reload ──────────────
        urutan++;
        const warnaBadge = {
            'Membaca':      'bg-blue-50 text-blue-600',
            'Pinjam Buku':  'bg-amber-50 text-amber-600',
            'Tugas':        'bg-purple-50 text-purple-600',
            'Belajar':      'bg-green-50 text-green-600',
            'Kembali Buku': 'bg-teal-50 text-teal-600',
        }[data.keperluan] ?? 'bg-zinc-100 text-zinc-500';

        const avatarHtml = data.foto
            ? `<img src="${data.foto}" class="w-full h-full object-cover">`
            : `<span class="text-xs font-bold text-zinc-500">${data.nama.charAt(0).toUpperCase()}</span>`;

        const newRow = document.createElement('div');
        newRow.id        = `ktitem-${data.id}`;
        newRow.className = 'kt-item flex items-center gap-3 p-3 rounded-xl bg-white dark:bg-zinc-900 border border-amber-200 dark:border-amber-700 shadow-sm';
        newRow.style.animation = 'modalIn 0.3s ease';
        newRow.innerHTML = `
            <div class="w-6 text-center text-xs font-bold text-zinc-300 shrink-0">${urutan}</div>
            <div class="w-9 h-9 rounded-full overflow-hidden shrink-0 border border-zinc-200 bg-zinc-100 flex items-center justify-center">${avatarHtml}</div>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-semibold text-zinc-800 dark:text-zinc-100 truncate">${data.nama}</div>
                <div class="text-xs text-zinc-400">${data.nomor_induk}${data.kelas ? ' · ' + data.kelas : ''}</div>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-full ${warnaBadge}">${data.keperluan}</span>
            <div class="text-xs font-mono text-zinc-400 shrink-0 w-10 text-right">${data.jam_masuk}</div>
            <form action="/kunjungan/${data.id}" method="POST" onsubmit="return confirm('Hapus?')" class="shrink-0">
                <input type="hidden" name="_token" value="${csrf()}">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="w-7 h-7 rounded-lg flex items-center justify-center text-zinc-300 hover:bg-red-50 hover:text-red-500 transition-colors">
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </form>
        `;

        // Sembunyikan empty state jika ada
        const emptyEl = document.getElementById('kt-empty');
        if (emptyEl) emptyEl.remove();

        // Tambah di atas list
        const list = document.getElementById('kt-list');
        list.insertBefore(newRow, list.firstChild);

        // Update counter
        document.getElementById('jumlah-label').textContent = data.jumlah_hari_ini + ' orang';
        document.getElementById('stat-hari-ini').textContent = data.jumlah_hari_ini;

        // Reset form
        clearPilihan();
        document.querySelectorAll('.keperluan-btn').forEach(b => b.classList.remove('aktif'));
        selectedKeperluan = 'Umum';

        // Tampilkan modal sukses
        document.getElementById('ms-desc').innerHTML =
            `<strong>${data.nama}</strong> berhasil check-in pukul <strong>${data.jam_masuk}</strong><br>Keperluan: ${data.keperluan}`;
        document.getElementById('modal-sukses').classList.remove('hidden');

    } catch (e) {
        msg.textContent = 'Gagal terhubung ke server.';
        msg.className   = 'text-sm text-center font-medium rounded-xl py-2.5 px-4 bg-red-50 text-red-600';
        msg.classList.remove('hidden');
    }

    btn.disabled = false;
    btn.innerHTML = '<svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Check-in Sekarang';
}

// ── Tutup dropdown saat klik luar ─────────────────────────────
document.addEventListener('click', function(e) {
    if (!searchEl.contains(e.target) && !dropdownEl.contains(e.target)) {
        dropdownEl.classList.add('hidden');
    }
});

// ── Escape helper ─────────────────────────────────────────────
function escHtml(s) {
    return String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
}
// alias agar kode lama tidak error
function esc(s) { return escHtml(s); }
</script>
</x-layouts::app>