<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNilaiEvaluasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'guru';
    }

    public function rules(): array
    {
        return [
            'nilai_makhraj'  => ['sometimes', 'required', 'numeric', 'min:0', 'max:100'],
            'nilai_fashohah' => ['sometimes', 'required', 'numeric', 'min:0', 'max:100'],
            'catatan_guru'   => ['nullable', 'string'],
        ];
    }
}
