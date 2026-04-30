<x-layouts::app :title="__('Detail Peminjaman')">
<div class="max-w-3xl mx-auto flex flex-col gap-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <flux:heading size="xl" level="1">Detail Peminjaman</flux:heading>
            <flux:subheading>#{{ str_pad($p->id, 5, '0', STR_PAD_LEFT) }} &bull; {{ $p->tgl_pinjam->format('d M Y') }}</flux:subheading>
        </div>
        <div class="flex gap-2 flex-wrap">
            @if($p->status === 'dipinjam')
                <button type="button"
                    onclick="konfirmasiKembali({{ $p->id }}, '{{ addslashes($p->anggota->nama_lengkap) }}', '{{ addslashes($p->buku->judul) }}')"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold
                           {{ $p->isTerlambat()
                               ? 'bg-red-600 hover:bg-red-700 text-white'
                               : 'bg-green-600 hover:bg-green-700 text-white' }}
                           transition-colors">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Kembalikan Buku
                </button>
            @endif
            <flux:button variant="ghost" icon="chevron-left" href="{{ route('pinjam.index') }}">Kembali</flux:button>
        </div>
    </div>

    <flux:separator />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom Kiri: Info Lengkap --}}
        <div class="lg:col-span-2 flex flex-col gap-5">

            {{-- Anggota --}}
            <flux:card>
                <div class="space-y-4">
                    <flux:label class="text-xs font-bold uppercase tracking-wider text-zinc-400">Anggota Peminjam</flux:label>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl overflow-hidden shrink-0 border border-zinc-200 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-lg font-bold text-zinc-500">
                            @if($p->anggota->foto)
                                <img src="{{ Storage::url($p->anggota->foto) }}" class="w-full h-full object-cover" alt="">
                            @else
                                {{ strtoupper(substr($p->anggota->nama_lengkap, 0, 1)) }}
                            @endif
                        </div>
                        <div>
                            <div class="font-bold text-zinc-800 dark:text-zinc-100">{{ $p->anggota->nama_lengkap }}</div>
                            <div class="text-sm text-zinc-500">{{ $p->anggota->nomor_induk }}
                                @if($p->anggota->kelas) · Kelas {{ $p->anggota->kelas }} @endif
                            </div>
                        </div>
                        <a href="{{ route('anggota.show', $p->anggota->id) }}"
                           class="ml-auto text-xs text-blue-500 hover:underline">Lihat Profil →</a>
                    </div>
                </div>
            </flux:card>

            {{-- Buku --}}
            <flux:card>
                <div class="space-y-4">
                    <flux:label class="text-xs font-bold uppercase tracking-wider text-zinc-400">Buku Dipinjam</flux:label>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-14 rounded-xl shrink-0 bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800 flex items-center justify-center text-2xl">
                            @if($p->buku->sampul)
                                <img src="{{ asset('storage/' . $p->buku->sampul) }}" class="w-full h-full object-cover rounded-xl" alt="">
                            @else
                                📖
                            @endif
                        </div>
                        <div>
                            <div class="font-bold text-zinc-800 dark:text-zinc-100">{{ $p->buku->judul }}</div>
                            <div class="text-sm text-zinc-500">{{ $p->buku->pengarang }}
                                @if($p->buku->penerbit) · {{ $p->buku->penerbit }} @endif
                            </div>
                            <div class="text-xs text-zinc-400 mt-0.5">ISBN: {{ $p->buku->isbn ?? '—' }}</div>
                        </div>
                        <a href="{{ route('katalog.show', $p->buku->id) }}"
                           class="ml-auto text-xs text-blue-500 hover:underline">Lihat Buku →</a>
                    </div>
                </div>
            </flux:card>

            {{-- Catatan --}}
            @if($p->catatan)
            <flux:card>
                <flux:label class="text-xs font-bold uppercase tracking-wider text-zinc-400 mb-2">Catatan</flux:label>
                <p class="text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">{{ $p->catatan }}</p>
            </flux:card>
            @endif

        </div>

        {{-- Kolom Kanan: Status --}}
        <div class="flex flex-col gap-4">

            <flux:card class="bg-zinc-50 dark:bg-white/[0.02] border-dashed">
                <div class="space-y-5">
                    <flux:heading size="sm">Status Peminjaman</flux:heading>

                    <div class="flex items-center justify-between">
                        <flux:label>Status</flux:label>
                        @if($p->status === 'kembali')
                            <flux:badge color="green">✅ Sudah Kembali</flux:badge>
                        @elseif($p->isTerlambat())
                            <flux:badge color="red">⚠️ Terlambat</flux:badge>
                        @else
                            <flux:badge color="blue">📖 Dipinjam</flux:badge>
                        @endif
                    </div>

                    <div class="space-y-3 border-t border-zinc-200 dark:border-zinc-700 pt-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-zinc-500">Tgl Pinjam</span>
                            <span class="font-semibold text-zinc-700 dark:text-zinc-300">{{ $p->tgl_pinjam->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-zinc-500">Harus Kembali</span>
                            <span class="font-semibold {{ $p->isTerlambat() ? 'text-red-500' : 'text-zinc-700 dark:text-zinc-300' }}">
                                {{ $p->tgl_harus_kembali->format('d M Y') }}
                            </span>
                        </div>
                        @if($p->tgl_realisasi_kembali)
                        <div class="flex justify-between text-sm">
                            <span class="text-zinc-500">Tgl Kembali</span>
                            <span class="font-semibold text-green-600">{{ $p->tgl_realisasi_kembali->format('d M Y') }}</span>
                        </div>
                        @endif
                    </div>

                    @if($p->hariTerlambat() > 0)
                    <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-zinc-500">Keterlambatan</span>
                            <span class="font-bold text-red-500">{{ $p->hariTerlambat() }} hari</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-zinc-500 text-sm">Denda</span>
                            <span class="text-lg font-bold text-red-600 dark:text-red-400">
                                Rp {{ number_format($p->denda ?: $p->hitungDenda(), 0, ',', '.') }}
                            </span>
                        </div>
                        <p class="text-xs text-zinc-400 mt-1">@ Rp 500 / hari</p>
                    </div>
                    @endif
                </div>
            </flux:card>

            <div class="p-4 rounded-xl bg-blue-50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-800">
                <div class="flex gap-2">
                    <flux:icon.information-circle class="text-blue-400 shrink-0" />
                    <p class="text-xs text-blue-600 dark:text-blue-400 leading-relaxed">
                        Denda dihitung otomatis Rp 500 per hari keterlambatan saat tombol "Kembalikan" diklik.
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Modal Kembalikan --}}
<div id="modal-kembali"
     class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4"
     onclick="if(event.target===this)document.getElementById('modal-kembali').classList.add('hidden')">
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-2xl p-6 w-full max-w-md" style="animation:modalIn 0.25s ease">
        <div class="text-4xl text-center mb-3" id="mk-icon">📬</div>
        <flux:heading size="lg" class="text-center mb-2">Kembalikan Buku?</flux:heading>
        <p class="text-sm text-zinc-500 text-center mb-5" id="mk-desc"></p>
        <div class="flex gap-3">
            <button onclick="document.getElementById('modal-kembali').classList.add('hidden')"
                class="flex-1 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-600 text-sm font-semibold text-zinc-600 dark:text-zinc-300 hover:bg-zinc-50 transition-colors">
                Batal
            </button>
            <button id="btn-kembali" class="flex-1 py-2.5 rounded-xl bg-green-600 hover:bg-green-700 text-white text-sm font-semibold transition-colors">
                Ya, Kembalikan
            </button>
        </div>
    </div>
</div>

<div id="modal-hasil" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-2xl p-6 w-full max-w-md text-center" style="animation:modalIn 0.25s ease">
        <div class="text-5xl mb-3" id="mh-icon">✅</div>
        <flux:heading size="lg" class="mb-2">Buku Berhasil Dikembalikan!</flux:heading>
        <p class="text-sm text-zinc-500 mb-4" id="mh-desc"></p>
        <div id="mh-denda" class="hidden mb-4 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200">
            <p class="text-sm text-red-600 font-semibold mb-1">Denda:</p>
            <p class="text-2xl font-bold text-red-600" id="mh-nominal">Rp 0</p>
        </div>
        <button onclick="location.href='{{ route('pinjam.index') }}'"
            class="w-full py-2.5 rounded-xl bg-zinc-800 dark:bg-zinc-100 text-white dark:text-zinc-900 text-sm font-semibold">
            Kembali ke Daftar
        </button>
    </div>
</div>

<style>
@keyframes modalIn { from{opacity:0;transform:scale(0.94) translateY(10px)} to{opacity:1;transform:scale(1) translateY(0)} }
</style>

<script>
let pinjamId = null;

function konfirmasiKembali(id, nama, judul) {
    pinjamId = id;
    document.getElementById('mk-desc').innerHTML = `<strong>${nama}</strong> mengembalikan<br><em>"${judul}"</em>`;
    document.getElementById('modal-kembali').classList.remove('hidden');
}

document.getElementById('btn-kembali').addEventListener('click', async function () {
    this.disabled = true; this.textContent = 'Memproses...';
    const res  = await fetch(`/pinjam/${pinjamId}/kembalikan`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    });
    const data = await res.json();
    document.getElementById('modal-kembali').classList.add('hidden');
    document.getElementById('mh-desc').innerHTML = `<em>"${data.judul_buku}"</em> dikembalikan pada ${data.tgl_kembali}`;
    if (data.denda > 0) {
        document.getElementById('mh-icon').textContent    = '⚠️';
        document.getElementById('mh-nominal').textContent = data.denda_format;
        document.getElementById('mh-denda').classList.remove('hidden');
    }
    document.getElementById('modal-hasil').classList.remove('hidden');
});
</script>
</x-layouts::app>