<x-layouts::app :title="__('Edit Anggota Struktur')">
<style>
@import url('https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500;600;700&display=swap');
:root { --lib-teal: #0f766e; --lib-ink: #1c1917; --lib-border: #e7e2da; }
.lib-root { font-family: 'DM Sans', sans-serif; }
</style>

<div class="lib-root flex flex-col gap-6 w-full max-w-2xl text-[var(--lib-ink)]">
    
    <div>
        <flux:heading size="xl" level="1" style="font-family:'Lora', serif;">Edit Anggota Struktur</flux:heading>
        <flux:subheading>Perbarui informasi personil perpustakaan.</flux:subheading>
    </div>

    <flux:separator />

    <form action="{{ route('struktur.update', $struktur->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-5 bg-white dark:bg-zinc-900 p-6 rounded-2xl border" style="border-color: var(--lib-border);">
        @csrf
        @method('PUT')
        
        <flux:input name="nama" value="{{ $struktur->nama }}" label="Nama Lengkap & Gelar" required />
        
        <flux:input name="jabatan" value="{{ $struktur->jabatan }}" label="Jabatan" required />
        
        <flux:input type="number" name="level" value="{{ $struktur->level }}" min="1" label="Level Tingkatan Hirarki" required />
        
        <flux:field>
            <flux:label>Foto Profil Baru (Biarkan kosong jika tidak ingin mengubah)</flux:label>
            @if($struktur->foto)
                <div class="mt-2 mb-3">
                    <img src="{{ Storage::url($struktur->foto) }}" class="h-16 w-16 rounded-xl object-cover border border-zinc-200">
                </div>
            @endif
            <input type="file" name="foto" accept="image/*" class="block w-full text-sm text-zinc-500 mt-2
                file:mr-4 file:py-2 file:px-4
                file:rounded-xl file:border-0
                file:text-sm file:font-semibold
                file:bg-zinc-100 file:text-zinc-700
                hover:file:bg-zinc-200 cursor-pointer">
        </flux:field>

        <div class="flex gap-3 pt-4 border-t border-zinc-100 dark:border-zinc-800 mt-2">
            <flux:button href="{{ route('struktur.index') }}" variant="ghost" class="w-full">Batal</flux:button>
            <flux:button type="submit" variant="primary" class="w-full" style="background: var(--lib-teal); color:white; border:none;">Perbarui Data</flux:button>
        </div>
    </form>

</div>
</x-layouts::app>