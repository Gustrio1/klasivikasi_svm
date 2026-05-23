<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'username'     => ['required', 'string', 'max:100', 'unique:tb_users,username'],
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
            'role'         => ['required', 'in:admin,guru,siswa'],
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'email'        => ['required', 'email', 'unique:tb_users,email'],
            'is_active'    => ['boolean'],
        ];
    }
}
