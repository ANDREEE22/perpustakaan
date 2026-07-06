<x-layouts::app :title="__('Info & Pengumuman')">
<style>
@import url('https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500;600;700&display=swap');
:root { --lib-teal: #0f766e; --lib-ink: #1c1917; --lib-border: #e7e2da; }
.lib-root { font-family: 'DM Sans', sans-serif; }
</style>

<div class="lib-root flex flex-col gap-6 w-full text-[var(--lib-ink)]">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <flux:heading size="xl" level="1" style="font-family:'Lora', serif;">Informasi & Pengumuman</flux:heading>
            <flux:subheading>Kelola berita dan info terbaru untuk ditampilkan di halaman depan</flux:subheading>
        </div>
        <flux:button variant="primary" icon="plus" href="{{ route('info.create') }}" style="background: var(--lib-teal); border:none; color: #fff;">
            Tambah Pengumuman
        </flux:button>
    </div>

    <flux:separator />


    <flux:table>
        <flux:table.columns>
            <flux:table.column>Judul Pengumuman</flux:table.column>
            <flux:table.column>Isi Singkat</flux:table.column>
            <flux:table.column>Tipe Warna</flux:table.column>
            <flux:table.column>Tanggal Publish</flux:table.column>
            <flux:table.column align="end">Aksi</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse($pengumumans as $item)
            <flux:table.row :key="$item->id">
                <flux:table.cell>
                    <div class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $item->judul_pengumuman }}</div>
                </flux:table.cell>

                <flux:table.cell>
                    <span class="text-zinc-500 text-sm">{{ Str::limit($item->isi_informasi, 50) }}</span>
                </flux:table.cell>

                <flux:table.cell>
                    @if($item->tipe_warna == 'amber')
                        <span class="inline-flex px-2.5 py-1 rounded-md text-xs font-bold bg-amber-100 text-amber-700">Kuning / Amber</span>
                    @elseif($item->tipe_warna == 'emerald')
                        <span class="inline-flex px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-100 text-emerald-700">Hijau / Emerald</span>
                    @else
                        <span class="inline-flex px-2.5 py-1 rounded-md text-xs font-bold bg-sky-100 text-sky-700">Biru / Sky</span>
                    @endif
                </flux:table.cell>

                <flux:table.cell>
                    <span class="font-mono text-sm text-zinc-600">{{ $item->tanggal_publish->format('d M Y') }}</span>
                </flux:table.cell>

                <flux:table.cell align="end">
                    <div class="flex justify-end items-center gap-2">
                        <flux:button variant="ghost" size="sm" icon="pencil-square" href="{{ route('info.edit', $item->id) }}" title="Edit" />
                        <form action="{{ route('info.destroy', $item->id) }}" method="POST" class="delete-info-form">
                            @csrf
                            @method('DELETE')
                            <flux:button type="submit" variant="ghost" size="sm" icon="trash" title="Hapus" />
                        </form>
                    </div>
                </flux:table.cell>
            </flux:table.row>
            @empty
            <flux:table.row>
                <flux:table.cell colspan="5" class="text-center py-12 text-zinc-400">
                    <p>Belum ada pengumuman yang diterbitkan.</p>
                </flux:table.cell>
            </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-info-form').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Hapus pengumuman?',
                    text: 'Tindakan ini tidak bisa dibatalkan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
</x-layouts::app>