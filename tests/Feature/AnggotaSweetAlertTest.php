<?php

use App\Models\User;
use Illuminate\Support\Str;

test('creating an anggota redirects with a sweet alert payload', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post(route('anggota.store'), [
        'nomor_induk' => 'NIS'.Str::random(8),
        'nama_lengkap' => 'Budi Santoso '.Str::random(4),
        'jenis_kelamin' => 'L',
        'kelas' => '7A',
    ]);

    $response->assertRedirect(route('anggota.index'));
    $session = $response->getSession();
    expect($session->has('swal'))->toBeTrue();
    $swal = $session->get('swal');
    expect($swal['title'])->toBe('Berhasil');
    expect($swal['icon'])->toBe('success');
});
