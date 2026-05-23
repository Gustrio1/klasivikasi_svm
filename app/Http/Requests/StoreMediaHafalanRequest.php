<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMediaHafalanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'nama_media'        => ['required', 'string', 'max:150'],
            'jenis_media'       => ['required', 'in:cetak,digital'],
            'url_link'          => ['nullable', 'url', 'required_if:jenis_media,digital'],
        ];
    }

    public function messages(): array
    {
        return [
            'url_link.required_if' => 'URL wajib diisi jika jenis media adalah digital.',
            'jenis_media.in'       => 'Jenis media harus cetak atau digital.',
        ];
    }

    /**
     * Jika cetak, pastikan url_link di-null-kan
     */
    protected function prepareForValidation(): void
    {
        if ($this->jenis_media === 'cetak') {
            $this->merge(['url_link' => null]);
        }
    }
}
