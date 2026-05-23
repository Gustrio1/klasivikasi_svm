<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        $userId = $this->route('user');

        return [
            'username'     => ['sometimes', 'required', 'string', 'max:100', Rule::unique('tb_users', 'username')->ignore($userId)],
            'password'     => ['nullable', 'string', 'min:8', 'confirmed'],
            'role'         => ['sometimes', 'required', 'in:admin,guru,siswa'],
            'nama_lengkap' => ['sometimes', 'required', 'string', 'max:150'],
            'email'        => ['sometimes', 'required', 'email', Rule::unique('tb_users', 'email')->ignore($userId)],
            'is_active'    => ['boolean'],
        ];
    }
}
