<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMediaHafalanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'nama_media'        => ['sometimes', 'required', 'string', 'max:150'],
            'jenis_media'       => ['sometimes', 'required', 'in:cetak,digital'],
            'url_link'          => ['nullable', 'url', 'required_if:jenis_media,digital'],
        ];
    }

    public function messages(): array
    {
        return [
            'url_link.required_if' => 'URL wajib diisi jika jenis media adalah digital.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->jenis_media === 'cetak') {
            $this->merge(['url_link' => null]);
        }
    }
}
