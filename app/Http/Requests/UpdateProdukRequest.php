<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProdukRequest extends FormRequest {
    public function authorize() { return true; }
    public function rules() {
        return [
            'nama'        => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'deskripsi'   => 'nullable|string',
            'harga'       => 'required|integer|min:0',
            'stok'        => 'required|integer|min:0',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }
    public function messages() {
        return [
            'nama.required'        => 'Nama produk wajib diisi.',
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'harga.required'       => 'Harga wajib diisi.',
            'stok.required'        => 'Stok wajib diisi.',
            'gambar.image'         => 'File harus berupa gambar.',
            'gambar.mimes'         => 'Format gambar: jpg, jpeg, png, webp.',
            'gambar.max'           => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
