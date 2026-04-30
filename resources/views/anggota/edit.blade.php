<x-layouts::app :title="__('Edit Anggota')">
<div class="max-w-2xl mx-auto flex flex-col gap-6">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <flux:heading size="xl" level="1">Edit Anggota</flux:heading>
            <flux:subheading>Perbarui data siswa atau guru perpustakaan.</flux:subheading>
        </div>
        <flux:button href="{{ route('anggota.index') }}" variant="ghost" icon="chevron-left">
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
    <form action="{{ route('anggota.update', $anggota->id) }}" method="POST"
          enctype="multipart/form-data" class="flex flex-col gap-5">
        @csrf
        @method('PUT')

        {{-- ── Data Identitas ── --}}
        <div class="p-5 rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 flex flex-col gap-4">
            <flux:heading size="sm">🪪 Data Identitas</flux:heading>
            <flux:separator variant="subtle" />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input
                    label="Nomor Induk (NISN/NIP)"
                    name="nomor_induk"
                    :value="old('nomor_induk', $anggota->nomor_induk)"
                    required
                />
                <flux:input
                    label="Nama Lengkap"
                    name="nama_lengkap"
                    :value="old('nama_lengkap', $anggota->nama_lengkap)"
                    required
                />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:select label="Jenis Kelamin" name="jenis_kelamin" required>
                    <flux:select.option value="L" :selected="old('jenis_kelamin', $anggota->jenis_kelamin) == 'L'">
                        Laki-laki
                    </flux:select.option>
                    <flux:select.option value="P" :selected="old('jenis_kelamin', $anggota->jenis_kelamin) == 'P'">
                        Perempuan
                    </flux:select.option>
                </flux:select>
                <flux:input
                    label="Kelas (kosongkan jika guru)"
                    name="kelas"
                    :value="old('kelas', $anggota->kelas)"
                    placeholder="Contoh: 8A, 9C"
                />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input
                    label="Tempat Lahir"
                    name="tempat_lahir"
                    :value="old('tempat_lahir', $anggota->tempat_lahir)"
                    placeholder="Contoh: Jember"
                />
                <flux:input
                    type="date"
                    label="Tanggal Lahir"
                    name="tanggal_lahir"
                    :value="old('tanggal_lahir', $anggota->tanggal_lahir?->format('Y-m-d'))"
                />
            </div>
        </div>

        {{-- ── Kontak & Alamat ── --}}
        <div class="p-5 rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 flex flex-col gap-4">
            <flux:heading size="sm">📞 Kontak & Alamat</flux:heading>
            <flux:separator variant="subtle" />

            <flux:input
                label="No Telepon / WhatsApp"
                name="no_telepon"
                :value="old('no_telepon', $anggota->no_telepon)"
                placeholder="Contoh: 0812-xxxx-xxxx"
            />
            <flux:textarea
                label="Alamat"
                name="alamat"
                rows="2"
                placeholder="Alamat lengkap anggota..."
            >{{ old('alamat', $anggota->alamat) }}</flux:textarea>
        </div>

        {{-- ── Foto Anggota ── --}}
        <div class="p-5 rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 flex flex-col gap-4">
            <flux:heading size="sm">🖼️ Foto Anggota</flux:heading>
            <flux:separator variant="subtle" />

            <div class="flex flex-col md:flex-row gap-4 items-start">

                {{-- Preview: w-16 h-16 (64x64px) — kecil & rapi --}}
                <div class="w-16 h-16 rounded-xl border-2 border-dashed border-zinc-300 dark:border-zinc-600 shrink-0 overflow-hidden flex items-center justify-center bg-zinc-50 dark:bg-zinc-800">
                    @if($anggota->foto)
                        <img id="preview-img"
                             src="{{ \Storage::url($anggota->foto) }}"
                             alt="Foto {{ $anggota->nama_lengkap }}"
                             class="w-16 h-16 object-cover">
                        <span id="preview-icon" class="text-2xl hidden">👤</span>
                    @else
                        <img id="preview-img" src="" alt="" class="hidden w-16 h-16 object-cover">
                        <span id="preview-icon" class="text-2xl">👤</span>
                    @endif
                </div>

                <div class="flex-1">
                    <flux:input
                        type="file"
                        label="Ganti Foto Anggota"
                        name="foto"
                        accept="image/jpg,image/jpeg,image/png,image/webp"
                        id="foto-input"
                    />
                    <p class="text-xs text-zinc-400 mt-2">
                        Format: JPG, PNG, WebP. Maks 2MB.
                        @if($anggota->foto)
                            Biarkan kosong untuk mempertahankan foto saat ini.
                        @endif
                    </p>
                    @error('foto')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex gap-3 justify-end pt-1">
            <flux:button href="{{ route('anggota.index') }}" variant="ghost">Batal</flux:button>
            <flux:button type="submit" variant="primary" icon="check">Update Anggota</flux:button>
        </div>

    </form>
</div>

<script>
document.getElementById('foto-input').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(ev) {
        const img  = document.getElementById('preview-img');
        const icon = document.getElementById('preview-icon');
        img.src = ev.target.result;
        img.classList.remove('hidden');
        if (icon) icon.classList.add('hidden');
    };
    reader.readAsDataURL(file);
});
</script>
</x-layouts::app>