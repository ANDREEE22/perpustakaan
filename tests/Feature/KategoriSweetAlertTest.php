<?php

use App\Models\User;
use Illuminate\Support\Str;

test('creating a category redirects with a sweet alert payload', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post(route('kategori.store'), [
        'nama' => 'Novel '.Str::random(4),
    ]);

    $response->assertRedirect(route('kategori.index'));
    $session = $response->getSession();
    expect($session->has('swal'))->toBeTrue();
    $swal = $session->get('swal');
    expect($swal['title'])->toBe('Berhasil');
    expect($swal['icon'])->toBe('success');
});
