<x-layouts::auth :title="__('Register')">
    <div class="relative left-1/2 w-[min(94vw,1040px)] -translate-x-1/2 overflow-hidden rounded-lg border border-stone-200 bg-white text-stone-950 shadow-2xl shadow-stone-950/10 dark:border-white/10 dark:bg-stone-950 dark:text-white">
        <div class="grid lg:grid-cols-[minmax(0,1fr)_470px]">
            <aside class="relative hidden min-h-[700px] overflow-hidden bg-stone-950 p-8 text-white lg:flex lg:flex-col lg:justify-between">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_16%_18%,rgba(14,165,233,0.30),transparent_30%),radial-gradient(circle_at_78%_18%,rgba(16,185,129,0.30),transparent_28%),linear-gradient(135deg,#0c0a09,#1c1917_46%,#134e4a)]"></div>
                <div class="absolute inset-x-8 top-28 h-px bg-gradient-to-r from-transparent via-cyan-200/60 to-transparent"></div>
                <div class="absolute -bottom-16 left-10 h-44 w-44 rounded-full border border-white/10"></div>
                <div class="absolute -right-12 top-40 h-28 w-28 rounded-full border border-emerald-300/20"></div>

                <div class="relative z-10">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-3" wire:navigate>
                        <img src="{{ asset('images/smp.png') }}" alt="Logo SMP Negeri 4 Jember" class="h-12 w-12 rounded-lg bg-white object-contain p-1.5">
                        <div>
                            <p class="text-sm font-semibold text-white">SMP Negeri 4 Jember</p>
                            <p class="text-xs text-white/55">Perpustakaan Digital</p>
                        </div>
                    </a>

                    <div class="mt-16 max-w-md">
                        <span class="inline-flex rounded-full border border-cyan-200/25 bg-cyan-200/10 px-3 py-1 text-xs font-semibold text-cyan-100">
                            Registrasi pengelola
                        </span>
                        <h1 class="mt-5 text-4xl font-semibold leading-tight">
                            Siapkan akun admin untuk mengelola perpustakaan lebih rapi.
                        </h1>
                        <p class="mt-4 text-sm leading-7 text-white/65">
                            Akun admin dapat membantu tim mencatat koleksi, anggota, peminjaman, kunjungan, dan laporan harian sekolah.
                        </p>
                    </div>
                </div>

                <div class="relative z-10 grid gap-3">
                    <div class="rounded-lg border border-white/10 bg-white/10 p-4 backdrop-blur">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-300/15 text-emerald-100">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold">Kontrol katalog</p>
                                <p class="mt-1 text-xs text-white/55">Tambah, ubah, dan pantau koleksi buku.</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg border border-white/10 bg-white/10 p-4 backdrop-blur">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-cyan-300/15 text-cyan-100">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-3-3.87" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 21v-2a4 4 0 0 1 3-3.87" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold">Data anggota</p>
                                <p class="mt-1 text-xs text-white/55">Kelola siswa, guru, dan riwayat kunjungan.</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg border border-white/10 bg-white/10 p-4 backdrop-blur">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-300/15 text-amber-100">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 14v-4" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14V7" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 14v-2" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold">Laporan cepat</p>
                                <p class="mt-1 text-xs text-white/55">Pantau aktivitas perpustakaan dari dashboard.</p>
                            </div>
                        </div>
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
                    <span class="rounded-full bg-cyan-50 px-3 py-1 text-xs font-semibold text-cyan-700 dark:bg-cyan-400/10 dark:text-cyan-200">
                        Register
                    </span>
                </div>

                <div class="mb-8">
                    <flux:heading size="xl">{{ __('Buat akun admin') }}</flux:heading>
                    <flux:subheading class="mt-2">
                        {{ __('Isi data pengelola untuk mulai mengakses dashboard perpustakaan.') }}
                    </flux:subheading>
                </div>

                <x-auth-session-status class="mb-6 text-center" :status="session('status')" />

                <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-5">
                    @csrf

                    <flux:input
                        name="name"
                        :label="__('Nama lengkap')"
                        :value="old('name')"
                        type="text"
                        required
                        autofocus
                        autocomplete="name"
                        :placeholder="__('Nama pengelola')"
                        icon="user"
                    />

                    <flux:input
                        name="email"
                        :label="__('Alamat email')"
                        :value="old('email')"
                        type="email"
                        required
                        autocomplete="email"
                        placeholder="admin@smpn4jember.sch.id"
                        icon="envelope"
                    />

                    <div class="grid gap-5 sm:grid-cols-2">
                        <flux:input
                            name="password"
                            :label="__('Password')"
                            type="password"
                            required
                            autocomplete="new-password"
                            :placeholder="__('Minimal 8 karakter')"
                            icon="lock-closed"
                            viewable
                        />

                        <flux:input
                            name="password_confirmation"
                            :label="__('Konfirmasi password')"
                            type="password"
                            required
                            autocomplete="new-password"
                            :placeholder="__('Ulangi password')"
                            icon="lock-closed"
                            viewable
                        />
                    </div>

                    <div class="rounded-lg border border-cyan-100 bg-cyan-50 px-4 py-3 text-sm leading-6 text-cyan-800 dark:border-cyan-400/15 dark:bg-cyan-400/10 dark:text-cyan-100">
                        Akun ini digunakan untuk mengakses fitur administrasi. Pastikan email aktif dan password disimpan dengan aman.
                    </div>

                    <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button">
                        {{ __('Daftar Akun') }}
                    </flux:button>
                </form>

                <div class="mt-8 rounded-lg border border-dashed border-stone-200 p-4 text-center text-sm text-stone-600 dark:border-white/10 dark:text-stone-400">
                    <span>{{ __('Sudah punya akun?') }}</span>
                    <flux:link :href="route('login')" wire:navigate>{{ __('Masuk') }}</flux:link>
                </div>
            </section>
        </div>
    </div>
</x-layouts::auth>
register