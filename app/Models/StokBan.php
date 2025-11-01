<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokBan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_ban',
        'nama_ban',
        'deskripsi',
        'brand',
        'ukuran',
        'type',
        'stok',
        'harga_beli',
        'harga_jual',
        'total_nilai_stok',
        'status',
        'minimum_stok',
    ];

    protected $casts = [
        'harga_beli' => 'decimal:2',
        'harga_jual' => 'decimal:2',
        'total_nilai_stok' => 'decimal:2',
        'stok' => 'integer',
        'minimum_stok' => 'integer',
    ];

    // Relasi dengan hutang retail
    public function hutangRetails()
    {
        return $this->hasMany(HutangRetail::class);
    }

    // Boot method untuk auto-calculate
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Hitung total nilai stok
            $model->total_nilai_stok = $model->stok * $model->harga_beli;

            // Update status berdasarkan stok
            $model->status = $model->calculateStatus();
        });

        // Juga hitung saat creating (untuk data baru)
        static::creating(function ($model) {
            $model->total_nilai_stok = $model->stok * $model->harga_beli;
            $model->status = $model->calculateStatus();
        });
    }

    // Method untuk menghitung status
    public function calculateStatus()
    {
        if ($this->stok == 0) {
            return 'Habis';
        } elseif ($this->stok <= $this->minimum_stok) {
            return 'Hampir Habis';
        } else {
            return 'Tersedia';
        }
    }

    // Accessor untuk memudahkan akses data
    public function getFormattedHargaBeliAttribute()
    {
        return 'Rp ' . number_format($this->harga_beli, 0, ',', '.');
    }

    public function getFormattedHargaJualAttribute()
    {
        return 'Rp ' . number_format($this->harga_jual, 0, ',', '.');
    }

    public function getFormattedTotalNilaiStokAttribute()
    {
        return 'Rp ' . number_format($this->total_nilai_stok, 0, ',', '.');
    }

    // Accessor untuk nilai stok per item (jika dibutuhkan)
    public function getNilaiStokPerItemAttribute()
    {
        return $this->harga_beli * $this->stok;
    }

    // Scope untuk query yang sering digunakan
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('kode_ban', 'like', '%' . $search . '%')
              ->orWhere('nama_ban', 'like', '%' . $search . '%')
              ->orWhere('brand', 'like', '%' . $search . '%')
              ->orWhere('ukuran', 'like', '%' . $search . '%');
        });
    }

    public function scopeByBrand($query, $brand)
    {
        if ($brand && $brand != 'Semua Brand') {
            return $query->where('brand', $brand);
        }
        return $query;
    }

    public function scopeByStatus($query, $status)
    {
        if ($status && $status != 'Semua Status') {
            return $query->where('status', $status);
        }
        return $query;
    }

    public function scopeByType($query, $type)
    {
        if ($type && $type != 'Semua Type') {
            return $query->where('type', $type);
        }
        return $query;
    }

    public function scopeLowStock($query)
    {
        return $query->where('status', 'Hampir Habis');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('status', 'Habis');
    }

    public function scopeInStock($query)
    {
        return $query->where('status', 'Tersedia');
    }

    // Static methods untuk dashboard dan reporting
    public static function getTotalStok()
    {
        return self::sum('stok');
    }

    public static function getTotalNilaiStok()
    {
        return self::sum('total_nilai_stok');
    }

    public static function getLowStockCount()
    {
        return self::where('status', 'Hampir Habis')->count();
    }

    public static function getOutOfStockCount()
    {
        return self::where('status', 'Habis')->count();
    }

    public static function getBrandStats()
    {
        return self::select('brand')
            ->selectRaw('COUNT(*) as total_items')
            ->selectRaw('SUM(stok) as total_stok')
            ->selectRaw('SUM(total_nilai_stok) as total_nilai')
            ->groupBy('brand')
            ->get();
    }

    public static function getRecentStock($limit = 5)
    {
        return self::latest()->take($limit)->get();
    }

    public static function getLowStockAlerts($limit = 5)
    {
        return self::where('status', 'Hampir Habis')
            ->orWhere('status', 'Habis')
            ->latest()
            ->take($limit)
            ->get();
    }

    // Method untuk generate kode ban otomatis
    public static function generateKodeBan()
    {
        $lastStok = self::latest()->first();
        $nextId = $lastStok ? $lastStok->id + 1 : 1;
        return 'BAN-' . date('Ym') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }

    // Validation rules untuk reuse di controller
    public static function getValidationRules($id = null)
    {
        return [
            'kode_ban' => 'required|string|unique:stok_bans,kode_ban,' . $id,
            'nama_ban' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'brand' => 'required|string|max:100',
            'ukuran' => 'required|string|max:50',
            'type' => 'required|string|max:50',
            'stok' => 'required|integer|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'minimum_stok' => 'required|integer|min:0',
        ];
    }

    // Method untuk update stok (untuk transaksi)
    public function updateStok($quantity)
    {
        $this->stok += $quantity;
        $this->save(); // Boot method akan otomatis hitung total_nilai_stok dan status
    }

    // Method untuk cek apakah stok mencukupi
    public function isStockSufficient($quantity)
    {
        return $this->stok >= $quantity;
    }

    // Method untuk mendapatkan margin keuntungan
    public function getMarginAttribute()
    {
        if ($this->harga_beli > 0) {
            return (($this->harga_jual - $this->harga_beli) / $this->harga_beli) * 100;
        }
        return 0;
    }

    public function getFormattedMarginAttribute()
    {
        return number_format($this->margin, 2) . '%';
    }
}