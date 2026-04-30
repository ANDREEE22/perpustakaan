<x-layouts::app :title="__('Peminjaman Buku')">
<div class="flex flex-col gap-6">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <flux:heading size="xl" level="1">Peminjaman Buku</flux:heading>
            <flux:subheading>Kelola transaksi pinjam & kembali perpustakaan SMPN 4 Jember</flux:subheading>
        </div>
        <flux:button variant="primary" icon="plus" href="{{ route('pinjam.create') }}">
            Pinjam Buku Baru
        </flux:button>
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
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <a href="{{ route('pinjam.index') }}"
           class="p-4 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 hover:shadow-md transition-shadow">
            <div class="text-2xl font-bold text-zinc-800 dark:text-zinc-100" style="font-family:'Georgia',serif">{{ $stats['total'] }}</div>
            <div class="text-xs text-zinc-400 mt-1 font-medium">Total Transaksi</div>
        </a>
        <a href="{{ route('pinjam.index', ['status'=>'dipinjam']) }}"
           class="p-4 rounded-xl border border-blue-200 dark:border-blue-800 bg-blue-50 dark:bg-blue-900/20 hover:shadow-md transition-shadow">
            <div class="text-2xl font-bold text-blue-700 dark:text-blue-300" style="font-family:'Georgia',serif">{{ $stats['aktif'] }}</div>
            <div class="text-xs text-blue-500 mt-1 font-medium">Sedang Dipinjam</div>
        </a>
        <a href="{{ route('pinjam.index', ['filter'=>'terlambat']) }}"
           class="p-4 rounded-xl border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/20 hover:shadow-md transition-shadow">
            <div class="text-2xl font-bold text-red-600 dark:text-red-400" style="font-family:'Georgia',serif">{{ $stats['terlambat'] }}</div>
            <div class="text-xs text-red-500 mt-1 font-medium">Terlambat</div>
        </a>
        <a href="{{ route('pinjam.index', ['status'=>'kembali']) }}"
           class="p-4 rounded-xl border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/20 hover:shadow-md transition-shadow">
            <div class="text-2xl font-bold text-green-700 dark:text-green-300" style="font-family:'Georgia',serif">{{ $stats['kembali'] }}</div>
            <div class="text-xs text-green-500 mt-1 font-medium">Sudah Kembali</div>
        </a>
    </div>

    {{-- Filter & Search --}}
    <form method="GET" action="{{ route('pinjam.index') }}" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-[200px]">
            <flux:input
                name="search"
                icon="magnifying-glass"
                placeholder="Cari nama anggota atau judul buku..."
                value="{{ request('search') }}"
                clearable
            />
        </div>
        <div class="w-full md:w-44">
            <flux:select name="status" placeholder="Semua Status">
                <flux:select.option value="">Semua Status</flux:select.option>
                <flux:select.option value="dipinjam" :selected="request('status') == 'dipinjam'">Dipinjam</flux:select.option>
                <flux:select.option value="kembali"  :selected="request('status') == 'kembali'">Sudah Kembali</flux:select.option>
            </flux:select>
        </div>
        <div class="w-full md:w-44">
            <flux:select name="filter" placeholder="Semua">
                <flux:select.option value="">Semua</flux:select.option>
                <flux:select.option value="terlambat" :selected="request('filter') == 'terlambat'">⚠️ Terlambat</flux:select.option>
            </flux:select>
        </div>
        <flux:button type="submit" variant="primary" icon="magnifying-glass">Cari</flux:button>
        @if(request()->hasAny(['search','status','filter']))
            <flux:button href="{{ route('pinjam.index') }}" variant="ghost" icon="x-mark">Reset</flux:button>
        @endif
    </form>

    {{-- Tabel --}}
    <flux:table>
        <flux:table.columns>
            <flux:table.column class="w-10">No</flux:table.column>
            <flux:table.column>Anggota</flux:table.column>
            <flux:table.column>Buku</flux:table.column>
            <flux:table.column>Tgl Pinjam</flux:table.column>
            <flux:table.column>Harus Kembali</flux:table.column>
            <flux:table.column>Status</flux:table.column>
            <flux:table.column>Denda</flux:table.column>
            <flux:table.column align="end">Aksi</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse($peminjaman as $i => $p)
            @php
                $terlambat   = $p->isTerlambat();
                $hariTelat   = $p->hariTerlambat();
                $dendaPreview = $p->status === 'dipinjam' ? $p->hitungDenda() : $p->denda;
            @endphp
            <flux:table.row :key="$p->id">

                {{-- No --}}
                <flux:table.cell class="text-zinc-400 text-sm">
                    {{ $peminjaman->firstItem() + $i }}
                </flux:table.cell>

                {{-- Anggota --}}
                <flux:table.cell>
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full overflow-hidden shrink-0 border border-zinc-200 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-xs font-bold text-zinc-500">
                            @if($p->anggota->foto)
                                <img src="{{ Storage::url($p->anggota->foto) }}" class="w-full h-full object-cover">
                            @else
                                {{ strtoupper(substr($p->anggota->nama_lengkap, 0, 1)) }}
                            @endif
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-zinc-800 dark:text-zinc-100">{{ $p->anggota->nama_lengkap }}</div>
                            <div class="text-xs text-zinc-400">{{ $p->anggota->nomor_induk }}{{ $p->anggota->kelas ? ' · ' . $p->anggota->kelas : '' }}</div>
                        </div>
                    </div>
                </flux:table.cell>

                {{-- Buku --}}
                <flux:table.cell>
                    <div class="text-sm font-semibold text-zinc-800 dark:text-zinc-100">{{ Str::limit($p->buku->judul, 35) }}</div>
                    <div class="text-xs text-zinc-400">{{ $p->buku->pengarang }}</div>
                </flux:table.cell>

                {{-- Tgl Pinjam --}}
                <flux:table.cell>
                    <span class="text-sm text-zinc-600 dark:text-zinc-300">{{ $p->tgl_pinjam->format('d/m/Y') }}</span>
                </flux:table.cell>

                {{-- Harus Kembali --}}
                <flux:table.cell>
                    <span class="text-sm font-medium {{ $terlambat ? 'text-red-600 dark:text-red-400' : 'text-zinc-600 dark:text-zinc-300' }}">
                        {{ $p->tgl_harus_kembali->format('d/m/Y') }}
                        @if($terlambat && $p->status === 'dipinjam')
                            <span class="block text-xs font-semibold text-red-500">+{{ $hariTelat }} hari</span>
                        @endif
                    </span>
                </flux:table.cell>

                {{-- Status --}}
                <flux:table.cell>
                    @if($p->status === 'kembali')
                        <flux:badge color="green">✅ Kembali</flux:badge>
                    @elseif($terlambat)
                        <flux:badge color="red">⚠️ Terlambat</flux:badge>
                    @else
                        <flux:badge color="blue">📖 Dipinjam</flux:badge>
                    @endif
                </flux:table.cell>

                {{-- Denda --}}
                <flux:table.cell>
                    @if($dendaPreview > 0)
                        <span class="text-sm font-bold text-red-600 dark:text-red-400">
                            Rp {{ number_format($dendaPreview, 0, ',', '.') }}
                        </span>
                    @else
                        <span class="text-sm text-zinc-400">—</span>
                    @endif
                </flux:table.cell>

                {{-- Aksi --}}
                <flux:table.cell align="end">
                    <div class="flex justify-end gap-2">
                        <flux:button variant="ghost" size="sm" icon="eye"
                            href="{{ route('pinjam.show', $p->id) }}" title="Detail" />

                        @if($p->status === 'dipinjam')
                            <button
                                type="button"
                                onclick="konfirmasiKembali({{ $p->id }}, '{{ addslashes($p->anggota->nama_lengkap) }}', '{{ addslashes($p->buku->judul) }}', {{ $dendaPreview }}, {{ $hariTelat }})"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold border
                                       {{ $terlambat
                                            ? 'bg-red-50 border-red-200 text-red-700 hover:bg-red-100 dark:bg-red-900/20 dark:border-red-700 dark:text-red-400'
                                            : 'bg-green-50 border-green-200 text-green-700 hover:bg-green-100 dark:bg-green-900/20 dark:border-green-700 dark:text-green-400' }}
                                       transition-colors">
                                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Kembalikan
                            </button>
                        @else
                            <span class="text-xs text-zinc-400 px-2">
                                {{ $p->tgl_realisasi_kembali?->format('d/m/Y') }}
                            </span>
                        @endif
                    </div>
                </flux:table.cell>

            </flux:table.row>
            @empty
            <flux:table.row>
                <flux:table.cell colspan="8" class="text-center py-12">
                    <div class="flex flex-col items-center gap-2">
                        <span class="text-4xl">📚</span>
                        <p class="font-medium text-zinc-500 dark:text-zinc-400">Belum ada data peminjaman</p>
                        <p class="text-sm text-zinc-400">
                            @if(request()->hasAny(['search','status','filter']))
                                Tidak ada yang cocok. <a href="{{ route('pinjam.index') }}" class="text-blue-500 hover:underline">Reset filter</a>
                            @else
                                Mulai catat peminjaman buku pertama.
                            @endif
                        </p>
                    </div>
                </flux:table.cell>
            </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    {{-- Pagination --}}
    @if($peminjaman->hasPages())
        <div>{{ $peminjaman->links() }}</div>
    @endif
    <p class="text-xs text-zinc-400 text-right">
        Menampilkan {{ $peminjaman->firstItem() ?? 0 }}–{{ $peminjaman->lastItem() ?? 0 }}
        dari {{ $peminjaman->total() }} transaksi
    </p>

</div>

{{-- ── Modal Konfirmasi Kembalikan ── --}}
<div id="modal-kembali"
     class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4"
     onclick="if(event.target===this)tutupModal()">
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-2xl p-6 w-full max-w-md shadow-2xl"
         style="animation: modalIn 0.25s ease">
        <div id="modal-icon" class="text-center text-4xl mb-3">📬</div>
        <flux:heading size="lg" class="text-center mb-2" id="modal-judul">Kembalikan Buku?</flux:heading>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 text-center mb-5 leading-relaxed" id="modal-desc"></p>

        {{-- Info denda --}}
        <div id="modal-denda-box" class="hidden mb-5 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
            <div class="flex items-center gap-2 mb-1">
                <span class="text-red-500 font-bold text-sm">⚠️ Ada Keterlambatan!</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-red-600 dark:text-red-400">Keterlambatan: <strong id="modal-hari">0</strong> hari</span>
                <span class="text-base font-bold text-red-600 dark:text-red-400" id="modal-denda-nominal">Rp 0</span>
            </div>
            <p class="text-xs text-red-500 mt-1">Rp 500 × jumlah hari terlambat</p>
        </div>

        {{-- Info tepat waktu --}}
        <div id="modal-ok-box" class="hidden mb-5 p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800">
            <div class="flex items-center gap-2">
                <span class="text-green-600 text-sm font-semibold">✅ Tepat Waktu — Tidak Ada Denda</span>
            </div>
        </div>

        <div class="flex gap-3">
            <button onclick="tutupModal()"
                class="flex-1 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-600 text-sm font-semibold text-zinc-600 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                Batal
            </button>
            <button id="btn-konfirmasi-kembali"
                class="flex-1 py-2.5 rounded-xl bg-green-600 hover:bg-green-700 text-white text-sm font-semibold transition-colors">
                Ya, Kembalikan
            </button>
        </div>
    </div>
</div>

{{-- ── Modal Hasil Pengembalian ── --}}
<div id="modal-hasil"
     class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-2xl p-6 w-full max-w-md shadow-2xl text-center"
         style="animation: modalIn 0.25s ease">
        <div class="text-5xl mb-3" id="hasil-icon">✅</div>
        <flux:heading size="lg" class="mb-2">Buku Berhasil Dikembalikan!</flux:heading>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4 leading-relaxed" id="hasil-desc"></p>
        <div id="hasil-denda-box" class="hidden mb-4 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
            <p class="text-sm text-red-600 dark:text-red-400 font-semibold mb-1">Denda yang harus dibayar:</p>
            <p class="text-2xl font-bold text-red-600 dark:text-red-400" id="hasil-denda-nominal">Rp 0</p>
        </div>
        <button onclick="location.reload()"
            class="w-full py-2.5 rounded-xl bg-zinc-800 dark:bg-zinc-100 text-white dark:text-zinc-900 text-sm font-semibold hover:opacity-90 transition-opacity">
            Tutup & Refresh
        </button>
    </div>
</div>

<style>
@keyframes modalIn {
    from { opacity:0; transform: scale(0.94) translateY(10px); }
    to   { opacity:1; transform: scale(1) translateY(0); }
}
</style>

<script>
let currentPinjamId = null;

function konfirmasiKembali(id, namaAnggota, judulBuku, denda, hariTelat) {
    currentPinjamId = id;

    document.getElementById('modal-desc').innerHTML =
        `<strong>${namaAnggota}</strong> mengembalikan buku<br><em>"${judulBuku}"</em>`;

    const dendaBox = document.getElementById('modal-denda-box');
    const okBox    = document.getElementById('modal-ok-box');

    if (denda > 0) {
        document.getElementById('modal-hari').textContent   = hariTelat;
        document.getElementById('modal-denda-nominal').textContent = 'Rp ' + denda.toLocaleString('id-ID');
        dendaBox.classList.remove('hidden');
        okBox.classList.add('hidden');
        document.getElementById('modal-icon').textContent  = '⚠️';
        document.getElementById('modal-judul').textContent = 'Ada Denda Keterlambatan';
        document.getElementById('btn-konfirmasi-kembali').className =
            'flex-1 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-semibold transition-colors';
    } else {
        dendaBox.classList.add('hidden');
        okBox.classList.remove('hidden');
        document.getElementById('modal-icon').textContent  = '📬';
        document.getElementById('modal-judul').textContent = 'Kembalikan Buku?';
        document.getElementById('btn-konfirmasi-kembali').className =
            'flex-1 py-2.5 rounded-xl bg-green-600 hover:bg-green-700 text-white text-sm font-semibold transition-colors';
    }

    document.getElementById('modal-kembali').classList.remove('hidden');
}

function tutupModal() {
    document.getElementById('modal-kembali').classList.add('hidden');
    currentPinjamId = null;
}

document.getElementById('btn-konfirmasi-kembali').addEventListener('click', async function () {
    if (!currentPinjamId) return;

    this.disabled   = true;
    this.textContent = 'Memproses...';

    try {
        const res = await fetch(`/pinjam/${currentPinjamId}/kembalikan`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
        });

        const data = await res.json();

        if (!res.ok) {
            alert(data.error || 'Terjadi kesalahan.');
            this.disabled    = false;
            this.textContent = 'Ya, Kembalikan';
            return;
        }

        // Tutup modal konfirmasi, tampilkan modal hasil
        document.getElementById('modal-kembali').classList.add('hidden');

        document.getElementById('hasil-desc').innerHTML =
            `<strong>${data.nama_anggota}</strong> telah mengembalikan<br><em>"${data.judul_buku}"</em><br>pada ${data.tgl_kembali}`;

        const hasilDendaBox = document.getElementById('hasil-denda-box');
        if (data.denda > 0) {
            document.getElementById('hasil-icon').textContent          = '⚠️';
            document.getElementById('hasil-denda-nominal').textContent = data.denda_format;
            hasilDendaBox.classList.remove('hidden');
        } else {
            document.getElementById('hasil-icon').textContent = '✅';
            hasilDendaBox.classList.add('hidden');
        }

        document.getElementById('modal-hasil').classList.remove('hidden');

    } catch (e) {
        alert('Gagal terhubung ke server. Coba lagi.');
        this.disabled    = false;
        this.textContent = 'Ya, Kembalikan';
    }
});
</script>
</x-layouts::app>