<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model {
    protected $fillable = ['kategori_id','nama','slug','deskripsi','harga','stok','gambar'];
    public function kategori() { return $this->belongsTo(Kategori::class); }
    public function orderItems() { return $this->hasMany(OrderItem::class); }
}
