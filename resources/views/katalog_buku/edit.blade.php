<x-layouts::app :title="__('Edit Buku')">
<div class="max-w-3xl mx-auto flex flex-col gap-6">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <flux:heading size="xl" level="1">Edit Buku</flux:heading>
            <flux:subheading>Perbarui informasi: <strong>{{ $buku->judul }}</strong></flux:subheading>
        </div>
        <flux:button href="{{ route('katalog') }}" variant="ghost" icon="chevron-left">
            Kembali
        </flux:button>
    </div>

    <flux:separator />

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
            <p class="text-sm font-semibold text-red-700 dark:text-red-400 mb-2">Terdapat kesalahan input:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li class="text-sm text-red-600 dark:text-red-300">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('katalog.update', $buku->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-6">
        @csrf
        @method('PUT')

        {{-- ── Identitas Buku ── --}}
        <div class="p-5 rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 flex flex-col gap-4">
            <flux:heading size="sm">📋 Identitas Buku</flux:heading>
            <flux:separator variant="subtle" />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input
                    label="Judul Buku"
                    name="judul"
                    value="{{ old('judul', $buku->judul) }}"
                    required
                />
                <flux:input
                    label="ISBN / Kode Buku"
                    name="isbn"
                    value="{{ old('isbn', $buku->isbn) }}"
                    required
                />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:select label="Kategori" name="kategori_id" required>
                    @foreach($kategoris as $k)
                        <flux:select.option
                            value="{{ $k->id }}"
                            :selected="old('kategori_id', $buku->kategori_id) == $k->id"
                        >{{ $k->nama }}</flux:select.option>
                    @endforeach
                </flux:select>

                <flux:input
                    label="Pengarang"
                    name="pengarang"
                    value="{{ old('pengarang', $buku->pengarang) }}"
                    required
                />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input
                    label="Penerbit"
                    name="penerbit"
                    value="{{ old('penerbit', $buku->penerbit) }}"
                />
                <flux:input
                    type="number"
                    label="Tahun Terbit"
                    name="tahun_terbit"
                    value="{{ old('tahun_terbit', $buku->tahun_terbit) }}"
                    min="1900"
                    max="{{ date('Y') }}"
                />
            </div>
        </div>

        {{-- ── Inventaris ── --}}
        <div class="p-5 rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 flex flex-col gap-4">
            <flux:heading size="sm">📦 Data Inventaris</flux:heading>
            <flux:separator variant="subtle" />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input
                    type="number"
                    label="Jumlah Stok (Eksemplar)"
                    name="stok"
                    value="{{ old('stok', $buku->stok) }}"
                    min="0"
                    required
                />
                <flux:input
                    label="Lokasi Rak"
                    name="lokasi_rak"
                    value="{{ old('lokasi_rak', $buku->lokasi_rak) }}"
                    placeholder="Contoh: Rak A1"
                />
            </div>

            <flux:textarea
                label="Deskripsi / Sinopsis"
                name="description"
                rows="4"
            >{{ old('description', $buku->description) }}</flux:textarea>
        </div>

        {{-- ── Upload Sampul ── --}}
        <div class="p-5 rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 flex flex-col gap-4">
            <flux:heading size="sm">🖼️ Sampul Buku</flux:heading>
            <flux:separator variant="subtle" />

            <div class="flex flex-col md:flex-row gap-4 items-start">
                {{-- Preview: tampilkan sampul lama jika ada --}}
                <div class="w-28 h-36 rounded-xl border-2 border-dashed border-zinc-300 dark:border-zinc-600 shrink-0 overflow-hidden flex items-center justify-center bg-zinc-50 dark:bg-zinc-800">
                    @if($buku->sampul)
                        <img id="preview-img"
                             src="{{ asset('storage/' . $buku->sampul) }}"
                             alt="Sampul {{ $buku->judul }}"
                             class="w-full h-full object-cover rounded-xl">
                        <span id="preview-icon" class="text-3xl hidden">📖</span>
                    @else
                        <img id="preview-img" src="" alt="" class="hidden w-full h-full object-cover rounded-xl">
                        <span id="preview-icon" class="text-3xl">📖</span>
                    @endif
                </div>

                <div class="flex-1">
                    <flux:input
                        type="file"
                        label="Ganti Foto Sampul"
                        name="sampul"
                        accept="image/jpg,image/jpeg,image/png,image/webp"
                        id="sampul-input"
                    />
                    <p class="text-xs text-zinc-400 mt-2">
                        Format: JPG, PNG, WebP. Ukuran maks: 2MB.
                        @if($buku->sampul)
                            Biarkan kosong untuk mempertahankan sampul saat ini.
                        @endif
                    </p>
                    @error('sampul')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Tombol --}}
        <div class="flex gap-3 justify-end pt-2">
            <flux:button href="{{ route('katalog') }}" variant="ghost">Batal</flux:button>
            <flux:button type="submit" variant="primary" icon="check">Update Data Buku</flux:button>
        </div>

    </form>
</div>

<script>
document.getElementById('sampul-input').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(ev) {
        const img  = document.getElementById('preview-img');
        const icon = document.getElementById('preview-icon');
        img.src = ev.target.result;
        img.classList.remove('hidden');
        icon.classList.add('hidden');
    };
    reader.readAsDataURL(file);
});
</script>
</x-layouts::app>