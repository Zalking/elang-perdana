<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HutangRetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_invoice',
        'tanggal_hutang',
        'nama_retail',
        'kontak_retail',
        'stok_ban_id',
        'jumlah_ban',
        'dibayar',
        'tanggal_jatuh_tempo',
        'keterangan',
        'user_id',
    ];

    protected $casts = [
        'tanggal_hutang' => 'date',
        'tanggal_jatuh_tempo' => 'date',
        'jumlah_ban' => 'integer',
        'dibayar' => 'integer',
    ];

    // Accessor untuk menghitung total hutang (dalam jumlah ban)
    public function getTotalHutangAttribute()
    {
        return $this->jumlah_ban;
    }

    // Accessor untuk menghitung sisa hutang (dalam jumlah ban)
    public function getSisaHutangAttribute()
    {
        return $this->jumlah_ban - $this->dibayar;
    }

    // Accessor untuk status hutang
    public function getStatusAttribute()
    {
        if ($this->sisa_hutang <= 0) {
            return 'Lunas';
        } elseif ($this->tanggal_jatuh_tempo && now()->greaterThan($this->tanggal_jatuh_tempo)) {
            return 'Terlambat';
        } else {
            return 'Belum Lunas';
        }
    }

    // Accessor untuk progress pembayaran
    public function getProgressAttribute()
    {
        if ($this->jumlah_ban == 0) return 0;
        return ($this->dibayar / $this->jumlah_ban) * 100;
    }

    // Append accessor ke JSON output
    protected $appends = ['total_hutang', 'sisa_hutang', 'status', 'progress'];

    public function stokBan()
    {
        return $this->belongsTo(StokBan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        // Validasi sebelum menyimpan
        static::saving(function ($model) {
            // Pastikan jumlah yang dibayar tidak melebihi jumlah hutang
            if ($model->dibayar > $model->jumlah_ban) {
                throw new \Exception('Jumlah yang dibayar tidak boleh melebihi jumlah hutang');
            }

            // Pastikan dibayar tidak negatif
            if ($model->dibayar < 0) {
                throw new \Exception('Jumlah yang dibayar tidak boleh negatif');
            }

            // Validasi stok saat membuat baru
            if (!$model->exists && $model->stokBan && $model->stokBan->stok < $model->jumlah_ban) {
                throw new \Exception('Stok ban tidak mencukupi. Stok tersedia: ' . $model->stokBan->stok . ' pcs');
            }
        });

        // Setelah hutang dibuat, kurangi stok ban
        static::created(function ($model) {
            $stokBan = $model->stokBan;
            if ($stokBan) {
                $stokBan->decrement('stok', $model->jumlah_ban);
            }
        });

        // Saat hutang diupdate, sesuaikan stok
        static::updating(function ($model) {
            $original = $model->getOriginal();
            
            // Jika jumlah_ban berubah atau stok_ban_id berubah
            if ($model->isDirty('jumlah_ban') || $model->isDirty('stok_ban_id')) {
                $oldJumlahBan = $original['jumlah_ban'];
                $newJumlahBan = $model->jumlah_ban;
                
                // Handle perubahan stok_ban_id
                if ($model->isDirty('stok_ban_id')) {
                    $oldStokBan = StokBan::find($original['stok_ban_id']);
                    $newStokBan = $model->stokBan;
                    
                    // Kembalikan stok ke ban lama
                    if ($oldStokBan) {
                        $oldStokBan->increment('stok', $oldJumlahBan);
                    }
                    
                    // Kurangi stok dari ban baru
                    if ($newStokBan) {
                        if ($newStokBan->stok < $newJumlahBan) {
                            throw new \Exception('Stok ban tidak mencukupi. Stok tersedia: ' . $newStokBan->stok . ' pcs');
                        }
                        $newStokBan->decrement('stok', $newJumlahBan);
                    }
                } 
                // Hanya jumlah_ban yang berubah
                else {
                    $stokBan = $model->stokBan;
                    if ($stokBan) {
                        $selisih = $newJumlahBan - $oldJumlahBan;
                        
                        if ($selisih > 0) {
                            // Penambahan hutang - kurangi stok
                            if ($stokBan->stok < $selisih) {
                                throw new \Exception('Stok ban tidak mencukupi. Stok tersedia: ' . $stokBan->stok . ' pcs');
                            }
                            $stokBan->decrement('stok', $selisih);
                        } else {
                            // Pengurangan hutang - tambah stok
                            $stokBan->increment('stok', abs($selisih));
                        }
                    }
                }
            }
            
            // Jika dibayar berubah dan bertambah, tambah stok (ban dikembalikan)
            if ($model->isDirty('dibayar') && $model->dibayar > $original['dibayar']) {
                $jumlahKembali = $model->dibayar - $original['dibayar'];
                $stokBan = $model->stokBan;
                if ($stokBan) {
                    $stokBan->increment('stok', $jumlahKembali);
                }
            }

            // Jika dibayar berkurang (jarang terjadi), kurangi stok
            if ($model->isDirty('dibayar') && $model->dibayar < $original['dibayar']) {
                $jumlahKurang = $original['dibayar'] - $model->dibayar;
                $stokBan = $model->stokBan;
                if ($stokBan) {
                    if ($stokBan->stok < $jumlahKurang) {
                        throw new \Exception('Stok tidak mencukupi untuk mengurangkan pembayaran. Stok tersedia: ' . $stokBan->stok . ' pcs');
                    }
                    $stokBan->decrement('stok', $jumlahKurang);
                }
            }
        });

        // Saat hutang dihapus, kembalikan stok untuk hutang yang belum dikembalikan
        static::deleting(function ($model) {
            $stokBan = $model->stokBan;
            if ($stokBan) {
                // Kembalikan stok untuk hutang yang belum dikembalikan
                $sisaHutang = $model->jumlah_ban - $model->dibayar;
                if ($sisaHutang > 0) {
                    $stokBan->increment('stok', $sisaHutang);
                }
            }
        });
    }

    // Scope untuk filter berdasarkan status
    public function scopeStatus($query, $status)
    {
        if ($status === 'Lunas') {
            return $query->whereRaw('jumlah_ban <= dibayar');
        } elseif ($status === 'Terlambat') {
            return $query->where('tanggal_jatuh_tempo', '<', now())
                        ->whereRaw('jumlah_ban > dibayar');
        } elseif ($status === 'Belum Lunas') {
            return $query->whereRaw('jumlah_ban > dibayar')
                        ->where(function($q) {
                            $q->whereNull('tanggal_jatuh_tempo')
                              ->orWhere('tanggal_jatuh_tempo', '>=', now());
                        });
        }
        
        return $query;
    }

    // Scope untuk hutang belum lunas
    public function scopeBelumLunas($query)
    {
        return $query->whereRaw('jumlah_ban > dibayar');
    }

    // Scope untuk hutang terlambat
    public function scopeTerlambat($query)
    {
        return $query->where('tanggal_jatuh_tempo', '<', now())
                    ->whereRaw('jumlah_ban > dibayar');
    }

    // Scope untuk hutang lunas
    public function scopeLunas($query)
    {
        return $query->whereRaw('jumlah_ban <= dibayar');
    }

    // Method untuk mendapatkan total hutang belum lunas (dalam jumlah ban)
    public static function getTotalHutangBelumLunas()
    {
        return self::belumLunas()->sum(DB::raw('jumlah_ban - dibayar'));
    }

    // Method untuk mendapatkan statistik hutang
    public static function getStatistik()
    {
        $belumLunas = self::belumLunas();
        $terlambat = self::terlambat();
        $lunas = self::lunas();

        return [
            'total_hutang_ban' => self::sum('jumlah_ban'),
            'total_dibayar_ban' => self::sum('dibayar'),
            'total_sisa_hutang_ban' => self::getTotalHutangBelumLunas(),
            'belum_lunas_count' => $belumLunas->count(),
            'terlambat_count' => $terlambat->count(),
            'lunas_count' => $lunas->count(),
            'total_transaksi' => self::count(),
        ];
    }

    // Method untuk melakukan pembayaran/pengembalian ban
    public function bayar($jumlah)
    {
        if ($jumlah <= 0) {
            throw new \Exception('Jumlah pembayaran harus lebih dari 0');
        }

        if ($jumlah > $this->sisa_hutang) {
            throw new \Exception('Jumlah pembayaran tidak boleh melebihi sisa hutang');
        }

        $this->dibayar += $jumlah;
        $this->save();

        return $this;
    }

    // Method untuk mengecek apakah hutang sudah lunas
    public function isLunas()
    {
        return $this->sisa_hutang <= 0;
    }

    // Method untuk mengecek apakah hutang terlambat
    public function isTerlambat()
    {
        return $this->tanggal_jatuh_tempo && 
               now()->greaterThan($this->tanggal_jatuh_tempo) && 
               !$this->isLunas();
    }

    // Method untuk mendapatkan hari keterlambatan
    public function getHariKeterlambatanAttribute()
    {
        if (!$this->isTerlambat()) {
            return 0;
        }
        
        return now()->diffInDays($this->tanggal_jatuh_tempo);
    }
}