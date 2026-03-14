<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreKategoriRequest extends FormRequest {
    public function authorize() { return true; }
    public function rules() {
        $id = $this->route('kategori') ? $this->route('kategori')->id : null;
        return [
            'nama' => 'required|string|max:100',
            'slug' => 'required|string|max:110|unique:kategoris,slug' . ($id ? ",$id" : ''),
        ];
    }
    public function messages() {
        return [
            'nama.required' => 'Nama kategori wajib diisi.',
            'slug.required' => 'Slug wajib diisi.',
            'slug.unique'   => 'Slug sudah digunakan.',
        ];
    }
}
