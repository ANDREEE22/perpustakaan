<?php

use App\Http\Controllers\DashboardController;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
});

test('dashboard late-loan mapping tolerates missing anggota records', function () {
    $controller = new DashboardController;

    $peminjaman = new Peminjaman([
        'status' => 'dipinjam',
        'tgl_harus_kembali' => now()->subDays(3)->toDateString(),
    ]);
    $peminjaman->setRelation('buku', new Buku(['judul' => 'Pemrograman Laravel']));
    $peminjaman->setRelation('anggota', null);

    $result = $controller->mapDendaAktifItem($peminjaman, now());

    expect($result)->toMatchArray([
        'nama' => 'Anggota tidak tersedia',
        'kelas' => 'Guru',
        'buku' => 'Pemrograman Laravel',
        'hari' => 3,
        'inisial' => '?',
    ]);
});
