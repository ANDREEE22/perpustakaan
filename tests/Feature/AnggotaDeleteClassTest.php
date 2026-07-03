<?php

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('menghapus kelas 9 lalu mempromosikan kelas 7 dan 8 satu tingkat', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);
    $this->actingAs($user);

    Anggota::create([
        'nomor_induk' => '1001',
        'nama_lengkap' => 'Anggota 7A',
        'jenis_kelamin' => 'L',
        'kelas' => '7A',
    ]);

    Anggota::create([
        'nomor_induk' => '1002',
        'nama_lengkap' => 'Anggota 8A',
        'jenis_kelamin' => 'P',
        'kelas' => '8A',
    ]);

    Anggota::create([
        'nomor_induk' => '1003',
        'nama_lengkap' => 'Anggota 9A',
        'jenis_kelamin' => 'L',
        'kelas' => '9A',
    ]);

    Anggota::create([
        'nomor_induk' => '1004',
        'nama_lengkap' => 'Guru Contoh',
        'jenis_kelamin' => 'P',
        'kelas' => null,
    ]);

    $response = $this->post(route('anggota.hapus-kelas'), [
        'kelas_tahun' => '9',
    ]);

    $response->assertRedirect(route('anggota.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseMissing('anggotas', [
        'nomor_induk' => '1003',
        'kelas' => '9A',
    ]);

    $this->assertDatabaseHas('anggotas', [
        'nomor_induk' => '1001',
        'kelas' => '8A',
    ]);

    $this->assertDatabaseHas('anggotas', [
        'nomor_induk' => '1002',
        'kelas' => '9A',
    ]);

    $this->assertDatabaseHas('anggotas', [
        'nomor_induk' => '1004',
        'kelas' => null,
    ]);
});
