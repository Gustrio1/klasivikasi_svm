<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDataHafalanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['guru']);
    }

    public function rules(): array
    {
        return [
            'id_siswa'         => ['required', 'integer', 'exists:tb_siswa,id'],
            'nama_surah'       => ['required', 'string', 'max:100'],
            'jumlah_ayat'      => ['required', 'integer', 'min:1', 'max:1000'],
            'id_media'         => ['required', 'integer', 'exists:tb_media_hafalan,id'],
            'periode_semester' => ['required', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_siswa.exists'           => 'Siswa tidak ditemukan.',
            'id_media.exists'           => 'Media hafalan tidak valid.',
        ];
    }
}
