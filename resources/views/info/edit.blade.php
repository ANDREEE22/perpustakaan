<x-layouts::app :title="__('Edit Pengumuman')">
<style>
@import url('https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500;600;700&display=swap');
:root { --lib-teal: #0f766e; --lib-ink: #1c1917; --lib-border: #e7e2da; }
.lib-root { font-family: 'DM Sans', sans-serif; }
</style>

<div class="lib-root flex flex-col gap-6 w-full max-w-2xl text-[var(--lib-ink)]">
    <div>
        <flux:heading size="xl" level="1" style="font-family:'Lora', serif;">Edit Pengumuman</flux:heading>
        <flux:subheading>Perbarui informasi berita atau pengumuman perpustakaan.</flux:subheading>
    </div>

    <flux:separator />

    <form action="{{ route('info.update', $info->id) }}" method="POST" class="flex flex-col gap-5 bg-white dark:bg-zinc-900 p-6 rounded-2xl border" style="border-color: var(--lib-border);">
        @csrf
        @method('PUT')
        
        <flux:input name="judul_pengumuman" value="{{ $info->judul_pengumuman }}" label="Judul Pengumuman" required />
        
        <flux:textarea name="isi_informasi" label="Isi Informasi" rows="4" required>{{ $info->isi_informasi }}</flux:textarea>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <flux:select name="tipe_warna" label="Tipe Warna Kartu" required>
                <flux:select.option value="amber" :selected="$info->tipe_warna == 'amber'">Kuning / Amber (Peringatan/Info Umum)</flux:select.option>
                <flux:select.option value="emerald" :selected="$info->tipe_warna == 'emerald'">Hijau / Emerald (Sukses/Baru)</flux:select.option>
                <flux:select.option value="sky" :selected="$info->tipe_warna == 'sky'">Biru / Sky (Jadwal/Waktu)</flux:select.option>
            </flux:select>

            <flux:input type="date" name="tanggal_publish" label="Tanggal Publish" value="{{ $info->tanggal_publish->format('Y-m-d') }}" required />
        </div>

        <div class="flex gap-3 pt-4 border-t border-zinc-100 dark:border-zinc-800 mt-2">
            <flux:button href="{{ route('info.index') }}" variant="ghost" class="w-full">Batal</flux:button>
            <flux:button type="submit" variant="primary" class="w-full" style="background: var(--lib-teal); color:white; border:none;">Perbarui Info</flux:button>
        </div>
    </form>
</div>
</x-layouts::app>