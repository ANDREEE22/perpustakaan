<x-layouts::app :title="__('Tambah Kategori')">
<div class="max-w-xl mx-auto flex flex-col gap-6">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <flux:heading size="xl" level="1">Tambah Kategori</flux:heading>
            <flux:subheading>Buat kategori baru untuk mengelompokkan buku.</flux:subheading>
        </div>
        <flux:button href="{{ route('kategori.index') }}" variant="ghost" icon="chevron-left">
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
    <form action="{{ route('kategori.store') }}" method="POST" class="flex flex-col gap-6">
        @csrf

        <div class="p-5 rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 flex flex-col gap-4">
            <flux:heading size="sm">🏷️ Informasi Kategori</flux:heading>
            <flux:separator variant="subtle" />

            <flux:input
                label="Nama Kategori"
                name="nama"
                value="{{ old('nama') }}"
                placeholder="Contoh: Pelajaran, Fiksi, Sains, Sejarah..."
                required
                autofocus
            />

            <div class="p-4 rounded-xl bg-blue-50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-800">
                <div class="flex gap-3">
                    <span class="text-blue-400 text-lg shrink-0">💡</span>
                    <p class="text-xs text-blue-600 dark:text-blue-400 leading-relaxed">
                        Gunakan nama kategori yang jelas dan mudah dipahami. Contoh: <strong>Pelajaran</strong>, <strong>Fiksi</strong>, <strong>Sains</strong>, <strong>Agama</strong>, <strong>Bahasa</strong>, <strong>Sejarah</strong>.
                    </p>
                </div>
            </div>
        </div>

        {{-- Tombol --}}
        <div class="flex gap-3 justify-end pt-2">
            <flux:button href="{{ route('kategori.index') }}" variant="ghost">Batal</flux:button>
            <flux:button type="submit" variant="primary" icon="check">Simpan Kategori</flux:button>
        </div>

    </form>

    {{-- Contoh kategori populer --}}
    <div class="p-5 rounded-2xl border border-dashed border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900/50">
        <p class="text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-3">💡 Saran Kategori Populer</p>
        <div class="flex flex-wrap gap-2">
            @foreach(['Pelajaran', 'Fiksi', 'Non-Fiksi', 'Sains', 'Sejarah', 'Agama', 'Bahasa', 'Matematika', 'Ensiklopedia', 'Biografi', 'Referensi', 'Majalah'] as $saran)
                <button type="button"
                    onclick="document.querySelector('[name=nama]').value = '{{ $saran }}'"
                    class="px-3 py-1.5 rounded-full text-xs font-medium bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-600 dark:text-zinc-300 hover:border-blue-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors cursor-pointer">
                    {{ $saran }}
                </button>
            @endforeach
        </div>
        <p class="text-xs text-zinc-400 mt-3">Klik nama di atas untuk mengisi otomatis.</p>
    </div>

</div>
</x-layouts::app>