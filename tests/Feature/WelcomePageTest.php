<?php

test('welcome page renders the digital library landing page', function () {
    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertSee('Perpustakaan Digital SMPN 4 Jember')
        ->assertSee('Koleksi pilihan')
        ->assertSee('Pengumuman');
});