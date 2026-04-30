<x-layouts::app :title="__('Pinjam Buku Baru')">
<div class="max-w-2xl mx-auto flex flex-col gap-6">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <flux:heading size="xl" level="1">Pinjam Buku Baru</flux:heading>
            <flux:subheading>Catat transaksi peminjaman buku perpustakaan.</flux:subheading>
        </div>
        <flux:button href="{{ route('pinjam.index') }}" variant="ghost" icon="chevron-left">
            Kembali
        </flux:button>
    </div>

    <flux:separator />

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
            <p class="text-sm font-semibold text-red-700 dark:text-red-400 mb-2">Terdapat kesalahan:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li class="text-sm text-red-600 dark:text-red-300">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pinjam.store') }}" method="POST" class="flex flex-col gap-5" id="form-pinjam">
        @csrf

        {{-- ══ PILIH ANGGOTA ══ --}}
        <div class="p-5 rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 flex flex-col gap-4">
            <flux:heading size="sm">👤 Pilih Anggota</flux:heading>
            <flux:separator variant="subtle" />

            {{-- Hidden input yang dikirim ke server --}}
            <input type="hidden" name="anggota_id" id="anggota_id" value="{{ old('anggota_id') }}">

            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 pointer-events-none w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
                <input
                    type="text"
                    id="anggota-search"
                    placeholder="Ketik nama atau NISN anggota..."
                    autocomplete="off"
                    class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 outline-none focus:border-blue-400 transition-colors"
                >
                {{-- Dropdown --}}
                <div id="anggota-dropdown"
                     class="hidden absolute z-40 left-0 right-0 mt-1 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-xl overflow-hidden max-h-56 overflow-y-auto">
                </div>
            </div>

            {{-- Card anggota terpilih --}}
            <div id="anggota-selected"
                 class="hidden p-3 rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-blue-500 flex items-center justify-center text-sm font-bold text-white shrink-0" id="anggota-avatar">?</div>
                <div class="flex-1 min-w-0">
                    <div class="font-semibold text-sm text-blue-800 dark:text-blue-200 truncate" id="anggota-nama">—</div>
                    <div class="text-xs text-blue-500 dark:text-blue-400" id="anggota-info">—</div>
                </div>
                <button type="button" onclick="clearAnggota()"
                        class="text-blue-400 hover:text-red-500 text-xl leading-none shrink-0 px-1 transition-colors">×</button>
            </div>

            @error('anggota_id')
                <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- ══ PILIH BUKU ══ --}}
        <div class="p-5 rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 flex flex-col gap-4">
            <flux:heading size="sm">📖 Pilih Buku</flux:heading>
            <flux:separator variant="subtle" />

            <input type="hidden" name="buku_id" id="buku_id" value="{{ old('buku_id') }}">

            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 pointer-events-none w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
                <input
                    type="text"
                    id="buku-search"
                    placeholder="Ketik judul buku atau ISBN..."
                    autocomplete="off"
                    class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 outline-none focus:border-amber-400 transition-colors"
                >
                <div id="buku-dropdown"
                     class="hidden absolute z-40 left-0 right-0 mt-1 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-xl overflow-hidden max-h-56 overflow-y-auto">
                </div>
            </div>

            {{-- Card buku terpilih --}}
            <div id="buku-selected"
                 class="hidden p-3 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 flex items-center gap-3">
                <div class="w-9 h-11 rounded-lg bg-amber-100 dark:bg-amber-800 flex items-center justify-center text-xl shrink-0">📖</div>
                <div class="flex-1 min-w-0">
                    <div class="font-semibold text-sm text-amber-800 dark:text-amber-200 truncate" id="buku-judul">—</div>
                    <div class="text-xs text-amber-600 dark:text-amber-400" id="buku-info">—</div>
                </div>
                <button type="button" onclick="clearBuku()"
                        class="text-amber-400 hover:text-red-500 text-xl leading-none shrink-0 px-1 transition-colors">×</button>
            </div>

            @error('buku_id')
                <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- ══ TANGGAL ══ --}}
        <div class="p-5 rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 flex flex-col gap-4">
            <flux:heading size="sm">📅 Tanggal Peminjaman</flux:heading>
            <flux:separator variant="subtle" />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input
                    type="date"
                    label="Tanggal Pinjam"
                    name="tgl_pinjam"
                    id="tgl_pinjam"
                    value="{{ old('tgl_pinjam', date('Y-m-d')) }}"
                    required
                />
                <flux:input
                    type="date"
                    label="Harus Kembali"
                    name="tgl_harus_kembali"
                    id="tgl_harus_kembali"
                    value="{{ old('tgl_harus_kembali', date('Y-m-d', strtotime('+7 days'))) }}"
                    required
                />
            </div>

            <div class="flex items-center gap-2 p-3 rounded-xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700">
                <svg class="w-4 h-4 text-zinc-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 6v6l4 2"/></svg>
                <span class="text-xs text-zinc-500" id="info-durasi">Durasi peminjaman: <strong>7 hari</strong></span>
            </div>

            <flux:textarea
                label="Catatan (Opsional)"
                name="catatan"
                rows="2"
                placeholder="Catatan tambahan tentang kondisi buku, dll."
            >{{ old('catatan') }}</flux:textarea>
        </div>

        {{-- Tombol --}}
        <div class="flex gap-3 justify-end pt-1">
            <flux:button href="{{ route('pinjam.index') }}" variant="ghost">Batal</flux:button>
            <flux:button type="submit" variant="primary" icon="check" id="btn-submit">
                Simpan Peminjaman
            </flux:button>
        </div>

    </form>
</div>

{{-- ════════════════════════════════════════════════════════
     PENTING: Data di-inject langsung dari PHP ke JS.
     Tidak ada fetch/API — tidak ada masalah auth/middleware.
     ════════════════════════════════════════════════════════ --}}
<script>

// ── Data dari database, langsung lewat Blade ──────────────────
const DATA_ANGGOTA = {!! json_encode(
    \App\Models\Anggota::orderBy('nama_lengkap')
        ->get(['id', 'nama_lengkap', 'nomor_induk', 'kelas'])
        ->toArray()
) !!};

const DATA_BUKU = {!! json_encode(
    \App\Models\Buku::where('stok', '>', 0)
        ->orderBy('judul')
        ->get(['id', 'judul', 'pengarang', 'isbn', 'stok'])
        ->toArray()
) !!};

// ── Referensi elemen DOM ──────────────────────────────────────
const elAnggotaSearch   = document.getElementById('anggota-search');
const elAnggotaDrop     = document.getElementById('anggota-dropdown');
const elAnggotaSelected = document.getElementById('anggota-selected');
const elAnggotaId       = document.getElementById('anggota_id');

const elBukuSearch   = document.getElementById('buku-search');
const elBukuDrop     = document.getElementById('buku-dropdown');
const elBukuSelected = document.getElementById('buku-selected');
const elBukuId       = document.getElementById('buku_id');

// ── Escape HTML untuk innerHTML ───────────────────────────────
function esc(s) {
    return String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
}

// ══════════════════════════════════════════════════════════════
// ANGGOTA — SEARCH & PILIH
// ══════════════════════════════════════════════════════════════
elAnggotaSearch.addEventListener('input', function () {
    const q = this.value.trim().toLowerCase();

    if (q.length < 1) {
        elAnggotaDrop.classList.add('hidden');
        return;
    }

    const hasil = DATA_ANGGOTA.filter(a =>
        a.nama_lengkap.toLowerCase().includes(q) ||
        String(a.nomor_induk).toLowerCase().includes(q)
    ).slice(0, 8);

    if (hasil.length === 0) {
        elAnggotaDrop.innerHTML =
            `<div class="px-4 py-3 text-sm text-center text-zinc-400">
                Anggota "<strong>${esc(q)}</strong>" tidak ditemukan.
             </div>`;
    } else {
        elAnggotaDrop.innerHTML = hasil.map(a => `
            <button type="button"
                data-id="${a.id}"
                data-nama="${esc(a.nama_lengkap)}"
                data-nisn="${esc(a.nomor_induk)}"
                data-kelas="${esc(a.kelas ?? '')}"
                class="anggota-item w-full text-left px-4 py-2.5
                       flex items-center gap-3
                       hover:bg-blue-50 dark:hover:bg-zinc-700
                       border-b border-zinc-100 dark:border-zinc-700 last:border-0
                       transition-colors cursor-pointer">
                <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center
                            text-xs font-bold text-white shrink-0">
                    ${esc(a.nama_lengkap.charAt(0).toUpperCase())}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-semibold text-zinc-800 dark:text-zinc-100 truncate">
                        ${esc(a.nama_lengkap)}
                    </div>
                    <div class="text-xs text-zinc-400">
                        NISN: ${esc(a.nomor_induk)}${a.kelas ? ' · Kelas ' + esc(a.kelas) : ' · Guru/Staf'}
                    </div>
                </div>
            </button>
        `).join('');

        // Pakai event delegation — tidak pakai onclick inline
        elAnggotaDrop.querySelectorAll('.anggota-item').forEach(btn => {
            btn.addEventListener('mousedown', function (e) {
                e.preventDefault(); // Cegah blur sebelum click teregister
                pilihAnggota(
                    this.dataset.id,
                    this.dataset.nama,
                    this.dataset.nisn,
                    this.dataset.kelas
                );
            });
        });
    }

    elAnggotaDrop.classList.remove('hidden');
});

function pilihAnggota(id, nama, nisn, kelas) {
    elAnggotaId.value = id;
    document.getElementById('anggota-avatar').textContent = nama.charAt(0).toUpperCase();
    document.getElementById('anggota-nama').textContent   = nama;
    document.getElementById('anggota-info').textContent   =
        'NISN: ' + nisn + (kelas ? ' · Kelas ' + kelas : ' · Guru/Staf');
    elAnggotaSelected.classList.remove('hidden');
    elAnggotaSearch.value = '';
    elAnggotaDrop.classList.add('hidden');
    elAnggotaSearch.style.borderColor = '';
}

function clearAnggota() {
    elAnggotaId.value = '';
    elAnggotaSelected.classList.add('hidden');
    elAnggotaSearch.value = '';
    elAnggotaSearch.focus();
}

// ══════════════════════════════════════════════════════════════
// BUKU — SEARCH & PILIH
// ══════════════════════════════════════════════════════════════
elBukuSearch.addEventListener('input', function () {
    const q = this.value.trim().toLowerCase();

    if (q.length < 1) {
        elBukuDrop.classList.add('hidden');
        return;
    }

    const hasil = DATA_BUKU.filter(b =>
        b.judul.toLowerCase().includes(q) ||
        b.pengarang.toLowerCase().includes(q) ||
        String(b.isbn ?? '').toLowerCase().includes(q)
    ).slice(0, 8);

    if (hasil.length === 0) {
        elBukuDrop.innerHTML =
            `<div class="px-4 py-3 text-sm text-center text-zinc-400">
                Buku "<strong>${esc(q)}</strong>" tidak ditemukan atau stok habis.
             </div>`;
    } else {
        elBukuDrop.innerHTML = hasil.map(b => `
            <button type="button"
                data-id="${b.id}"
                data-judul="${esc(b.judul)}"
                data-pengarang="${esc(b.pengarang)}"
                data-stok="${b.stok}"
                class="buku-item w-full text-left px-4 py-2.5
                       flex items-center gap-3
                       hover:bg-amber-50 dark:hover:bg-zinc-700
                       border-b border-zinc-100 dark:border-zinc-700 last:border-0
                       transition-colors cursor-pointer">
                <div class="w-8 h-10 rounded bg-amber-100 dark:bg-amber-900
                            flex items-center justify-center text-base shrink-0">📖</div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-semibold text-zinc-800 dark:text-zinc-100 truncate">
                        ${esc(b.judul)}
                    </div>
                    <div class="text-xs text-zinc-400">
                        ${esc(b.pengarang)} ·
                        Stok: <span class="text-green-600 font-bold">${b.stok}</span>
                    </div>
                </div>
            </button>
        `).join('');

        elBukuDrop.querySelectorAll('.buku-item').forEach(btn => {
            btn.addEventListener('mousedown', function (e) {
                e.preventDefault();
                pilihBuku(
                    this.dataset.id,
                    this.dataset.judul,
                    this.dataset.pengarang,
                    this.dataset.stok
                );
            });
        });
    }

    elBukuDrop.classList.remove('hidden');
});

function pilihBuku(id, judul, pengarang, stok) {
    elBukuId.value = id;
    document.getElementById('buku-judul').textContent = judul;
    document.getElementById('buku-info').textContent  =
        pengarang + ' · Stok tersedia: ' + stok;
    elBukuSelected.classList.remove('hidden');
    elBukuSearch.value = '';
    elBukuDrop.classList.add('hidden');
    elBukuSearch.style.borderColor = '';
}

function clearBuku() {
    elBukuId.value = '';
    elBukuSelected.classList.add('hidden');
    elBukuSearch.value = '';
    elBukuSearch.focus();
}

// ══════════════════════════════════════════════════════════════
// TUTUP DROPDOWN SAAT KLIK DI LUAR
// ══════════════════════════════════════════════════════════════
document.addEventListener('click', function (e) {
    if (!elAnggotaSearch.contains(e.target) && !elAnggotaDrop.contains(e.target)) {
        elAnggotaDrop.classList.add('hidden');
    }
    if (!elBukuSearch.contains(e.target) && !elBukuDrop.contains(e.target)) {
        elBukuDrop.classList.add('hidden');
    }
});

// ══════════════════════════════════════════════════════════════
// HITUNG DURASI OTOMATIS
// ══════════════════════════════════════════════════════════════
function updateDurasi() {
    const a = new Date(document.getElementById('tgl_pinjam').value);
    const b = new Date(document.getElementById('tgl_harus_kembali').value);
    if (isNaN(a) || isNaN(b)) return;
    const hari = Math.round((b - a) / 86400000);
    const el   = document.getElementById('info-durasi');
    el.innerHTML = hari > 0
        ? `Durasi peminjaman: <strong>${hari} hari</strong>`
        : `<span class="text-red-500">⚠️ Tanggal kembali harus setelah tanggal pinjam!</span>`;
}

document.getElementById('tgl_pinjam').addEventListener('change', function () {
    const d = new Date(this.value);
    d.setDate(d.getDate() + 7);
    document.getElementById('tgl_harus_kembali').value = d.toISOString().split('T')[0];
    updateDurasi();
});
document.getElementById('tgl_harus_kembali').addEventListener('change', updateDurasi);
updateDurasi();

// ══════════════════════════════════════════════════════════════
// VALIDASI CLIENT-SIDE SEBELUM SUBMIT
// ══════════════════════════════════════════════════════════════
document.getElementById('form-pinjam').addEventListener('submit', function (e) {
    let ok = true;

    if (!elAnggotaId.value) {
        ok = false;
        elAnggotaSearch.style.borderColor = '#ef4444';
        elAnggotaSearch.style.boxShadow   = '0 0 0 2px #fee2e2';
        elAnggotaSearch.placeholder       = '⚠️ Pilih anggota terlebih dahulu!';
        elAnggotaSearch.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    if (!elBukuId.value) {
        ok = false;
        elBukuSearch.style.borderColor = '#ef4444';
        elBukuSearch.style.boxShadow   = '0 0 0 2px #fee2e2';
        elBukuSearch.placeholder       = '⚠️ Pilih buku terlebih dahulu!';
        if (elAnggotaId.value) {
            elBukuSearch.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    if (!ok) e.preventDefault();
});

elAnggotaSearch.addEventListener('focus', () => {
    elAnggotaSearch.style.borderColor = '';
    elAnggotaSearch.style.boxShadow   = '';
    elAnggotaSearch.placeholder       = 'Ketik nama atau NISN anggota...';
});
elBukuSearch.addEventListener('focus', () => {
    elBukuSearch.style.borderColor = '';
    elBukuSearch.style.boxShadow   = '';
    elBukuSearch.placeholder       = 'Ketik judul buku atau ISBN...';
});

</script>
</x-layouts::app>