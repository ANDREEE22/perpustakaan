<?php

namespace App\Http\Responses;

use Laravel\Fortify\Http\Responses\LoginResponse as BaseLoginResponse;

class LoginResponse extends BaseLoginResponse
{
    /**
     * Create an HTTP response that represents the object.
     */
    public function toResponse($request)
    {
        $request->session()->flash('swal', [
            'title' => 'Login berhasil',
            'text' => 'Selamat datang kembali!',
            'icon' => 'success',
            'timer' => 2500,
            'showConfirmButton' => false,
        ]);

        return parent::toResponse($request);
    }
}
