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
            'catatan_guru'   => ['nullable', 'string'],
        ];
    }
}
