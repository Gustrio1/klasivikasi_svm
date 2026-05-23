<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHasilKlasifikasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'guru']);
    }

    public function rules(): array
    {
        return [
            'id_hafalan'     => ['required', 'integer', 'exists:tb_data_hafalan,id'],
            'fitur_tajwid'   => ['required', 'numeric', 'min:0', 'max:100'],
            'fitur_kelancaran' => ['required', 'numeric', 'min:0', 'max:100'],
            'fitur_makhraj'  => ['required', 'numeric', 'min:0', 'max:100'],
            'fitur_fashohah' => ['required', 'numeric', 'min:0', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_hafalan.exists'       => 'Data hafalan tidak ditemukan.',
            '*.numeric'               => 'Nilai fitur harus berupa angka.',
            '*.max'                   => 'Nilai fitur maksimal 100.',
        ];
    }
}
