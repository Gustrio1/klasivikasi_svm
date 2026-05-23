<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNilaiEvaluasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'guru';
    }

    public function rules(): array
    {
        return [
            'id_hafalan'    => ['required', 'integer', 'exists:tb_data_hafalan,id', 'unique:tb_nilai_evaluasi,id_hafalan'],
            'nilai_makhraj' => ['required', 'numeric', 'min:0', 'max:100'],
            'nilai_fashohah' => ['required', 'numeric', 'min:0', 'max:100'],
            'catatan_guru'  => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_hafalan.unique' => 'Hafalan ini sudah memiliki nilai evaluasi.',
        ];
    }
}
