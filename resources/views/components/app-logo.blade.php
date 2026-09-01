@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="SMPN 4 JEMBER" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
            <img
    src="{{ asset('images/logo_sekolah.png') }}"
    alt="Logo SMPN 4 JEMBER"
    class="size-5 object-contain"
/>
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="SMPN 4 JEMBER" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
            <img
    src="{{ asset('images/logo_sekolah.png') }}"
    alt="Logo SMPN 4 JEMBER"
    class="size-5 object-contain"
/>
        </x-slot>
    </flux:brand>
@endif
