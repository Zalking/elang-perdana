<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kategori',
        'customer',
        'brand',
        'part',
        'description',
        'ytd',
        'january',
        'february',
        'march',
        'april',
        'may',
        'june',
        'july',
        'august',
        'september',
        'october',
        'mtd',
        'mtd_export',
        'mtd_domestic',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor untuk total penjualan bulanan
    public function getTotalBulananAttribute()
    {
        return $this->january + $this->february + $this->march + $this->april + 
               $this->may + $this->june + $this->july + $this->august + 
               $this->september + $this->october;
    }

    // Scope untuk filter
    public function scopeByKategori($query, $kategori)
    {
        if ($kategori && $kategori !== 'Semua Kategori') {
            return $query->where('kategori', $kategori);
        }
        return $query;
    }

    public function scopeByBrand($query, $brand)
    {
        if ($brand && $brand !== 'Semua Brand') {
            return $query->where('brand', $brand);
        }
        return $query;
    }

    public function scopeByCustomer($query, $customer)
    {
        if ($customer && $customer !== 'Semua Customer') {
            return $query->where('customer', 'like', '%' . $customer . '%');
        }
        return $query;
    }
}