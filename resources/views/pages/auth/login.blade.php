<x-layouts::auth :title="__('Log in')">
    <div class="relative left-1/2 w-[min(94vw,960px)] -translate-x-1/2 overflow-hidden rounded-lg border border-stone-200 bg-white text-stone-950 shadow-2xl shadow-stone-950/10 dark:border-white/10 dark:bg-stone-950 dark:text-white">
        <div class="grid lg:grid-cols-[minmax(0,1fr)_430px]">
            <aside class="relative hidden min-h-[620px] overflow-hidden bg-stone-950 p-8 text-white lg:flex lg:flex-col lg:justify-between">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_18%_18%,rgba(16,185,129,0.34),transparent_28%),radial-gradient(circle_at_82%_12%,rgba(245,158,11,0.24),transparent_26%),linear-gradient(135deg,#0c0a09,#1c1917_48%,#064e3b)]"></div>
                <div class="absolute inset-x-8 top-28 h-px bg-gradient-to-r from-transparent via-amber-300/60 to-transparent"></div>
                <div class="absolute -right-20 bottom-16 h-48 w-48 rounded-full border border-white/10"></div>
                <div class="absolute -right-8 bottom-28 h-24 w-24 rounded-full border border-emerald-300/20"></div>

                <div class="relative z-10">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-3" wire:navigate>
                        <img src="{{ asset('images/smp.png') }}" alt="Logo SMP Negeri 4 Jember" class="h-12 w-12 rounded-lg bg-white object-contain p-1.5">
                        <div>
                            <p class="text-sm font-semibold text-white">SMP Negeri 4 Jember</p>
                            <p class="text-xs text-white/55">Perpustakaan Digital</p>
                        </div>
                    </a>

                    <div class="mt-16 max-w-md">
                        <span class="inline-flex rounded-full border border-emerald-300/25 bg-emerald-300/10 px-3 py-1 text-xs font-semibold text-emerald-100">
                            Panel admin perpustakaan
                        </span>
                        <h1 class="mt-5 text-4xl font-semibold leading-tight">
                            Masuk dan kelola literasi sekolah dengan lebih cepat.
                        </h1>
                        <p class="mt-4 text-sm leading-7 text-white/65">
                            Pantau katalog, peminjaman, anggota, kunjungan, dan laporan dalam satu ruang kerja yang rapi.
                        </p>
                    </div>
                </div>

                <div class="relative z-10 grid grid-cols-3 gap-3">
                    <div class="rounded-lg border border-white/10 bg-white/10 p-4 backdrop-blur">
                        <p class="text-2xl font-semibold">1.2K</p>
                        <p class="mt-1 text-xs text-white/55">Eksemplar</p>
                    </div>
                    <div class="rounded-lg border border-white/10 bg-white/10 p-4 backdrop-blur">
                        <p class="text-2xl font-semibold">48</p>
                        <p class="mt-1 text-xs text-white/55">Dipinjam</p>
                    </div>
                    <div class="rounded-lg border border-white/10 bg-white/10 p-4 backdrop-blur">
                        <p class="text-2xl font-semibold">98%</p>
                        <p class="mt-1 text-xs text-white/55">Tersedia</p>
                    </div>
                </div>
            </aside>

            <section class="p-6 sm:p-8 lg:p-10">
                <div class="mb-8 flex items-center justify-between gap-4 lg:hidden">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-3" wire:navigate>
                        <img src="{{ asset('images/smp.png') }}" alt="Logo SMP Negeri 4 Jember" class="h-11 w-11 rounded-lg bg-white object-contain p-1.5 shadow-sm dark:bg-white">
                        <div>
                            <p class="text-sm font-semibold text-stone-950 dark:text-white">SMPN 4 Jember</p>
                            <p class="text-xs text-stone-500 dark:text-stone-400">Perpustakaan Digital</p>
                        </div>
                    </a>
                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-200">
                        Admin
                    </span>
                </div>

                <div class="mb-8">
                    <flux:heading size="xl">{{ __('Selamat datang kembali') }}</flux:heading>
                    <flux:subheading class="mt-2">
                        {{ __('Masuk ke dashboard perpustakaan SMP Negeri 4 Jember.') }}
                    </flux:subheading>
                </div>

                <x-auth-session-status class="mb-6 text-center" :status="session('status')" />

                <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-5">
                    @csrf

                    <flux:input
                        name="email"
                        :label="__('Alamat email')"
                        :value="old('email')"
                        type="email"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="admin@smpn4jember.sch.id"
                        icon="envelope"
                    />

                    <div class="relative">
                        <flux:input
                            name="password"
                            :label="__('Password')"
                            type="password"
                            required
                            autocomplete="current-password"
                            :placeholder="__('Masukkan password')"
                            icon="lock-closed"
                            viewable
                        />

                        @if (Route::has('password.request'))
                            <flux:link class="absolute end-0 top-0 text-sm" :href="route('password.request')" wire:navigate>
                                {{ __('Lupa password?') }}
                            </flux:link>
                        @endif
                    </div>

                    <div class="flex items-center justify-between gap-4 rounded-lg border border-stone-200 bg-stone-50 px-4 py-3 dark:border-white/10 dark:bg-white/[0.03]">
                        <flux:checkbox name="remember" :label="__('Ingat saya')" :checked="old('remember')" />
                        <span class="hidden text-xs text-stone-500 dark:text-stone-400 sm:inline">
                            {{ __('Sesi tetap aktif di perangkat ini') }}
                        </span>
                    </div>

                    <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                        {{ __('Masuk ke Dashboard') }}
                    </flux:button>
                </form>

                @if (Route::has('register'))
                    <div class="mt-8 rounded-lg border border-dashed border-stone-200 p-4 text-center text-sm text-stone-600 dark:border-white/10 dark:text-stone-400">
                        <span>{{ __('Belum punya akun?') }}</span>
                        <flux:link :href="route('register')" wire:navigate>{{ __('Daftar') }}</flux:link>
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-layouts::auth>

login