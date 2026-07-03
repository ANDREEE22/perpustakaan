<?php

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can borrow multiple books in one transaction', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);

    $kategori = Kategori::create(['nama' => 'Umum']);

    $anggota = Anggota::create([
        'nomor_induk' => 'A001',
        'nama_lengkap' => 'Test Anggota',
        'jenis_kelamin' => 'L',
        'kelas' => 'XIPA',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => now()->subYears(16)->format('Y-m-d'),
        'no_telepon' => '081234567890',
        'alamat' => 'Jl. Test No. 1',
    ]);

    $buku1 = Buku::create([
        'kode_buku' => 'B001',
        'judul' => 'Buku Satu',
        'isbn' => '9781234567890',
        'kategori_id' => $kategori->id,
        'pengarang' => 'Pengarang Satu',
        'penerbit' => 'Penerbit Satu',
        'tahun_terbit' => 2020,
        'stok' => 2,
    ]);

    $buku2 = Buku::create([
        'kode_buku' => 'B002',
        'judul' => 'Buku Dua',
        'isbn' => '9780987654321',
        'kategori_id' => $kategori->id,
        'pengarang' => 'Pengarang Dua',
        'penerbit' => 'Penerbit Dua',
        'tahun_terbit' => 2021,
        'stok' => 1,
    ]);

    $response = $this->actingAs($user)
        ->post(route('pinjam.store'), [
            'anggota_id' => $anggota->id,
            'buku_id' => [$buku1->id, $buku2->id],
            'tgl_pinjam' => now()->format('Y-m-d'),
            'tgl_harus_kembali' => now()->addDays(7)->format('Y-m-d'),
            'catatan' => 'Test pinjam beberapa buku',
        ]);

    $response->assertRedirect(route('pinjam.index'));
    $this->assertDatabaseHas('peminjaman', [
        'anggota_id' => $anggota->id,
        'buku_id' => $buku1->id,
        'status' => 'dipinjam',
    ]);
    $this->assertDatabaseHas('peminjaman', [
        'anggota_id' => $anggota->id,
        'buku_id' => $buku2->id,
        'status' => 'dipinjam',
    ]);
    $this->assertSame(1, Buku::find($buku1->id)->stok);
    $this->assertSame(0, Buku::find($buku2->id)->stok);
});
