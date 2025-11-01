<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal_penjualan',
        'no_faktur',
        'nama_pelanggan',
        'nama_barang',
        'jumlah',
        'harga_satuan',
        'total',
        'metode_pembayaran',
        'status',
        'keterangan',
        'user_id',
    ];

    protected $casts = [
        'tanggal_penjualan' => 'date',
        'harga_satuan' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->total = $model->jumlah * $model->harga_satuan;
        });
    }
}