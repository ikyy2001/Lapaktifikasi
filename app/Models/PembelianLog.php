<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembelianLog extends Model
{
    use HasFactory;

    protected $table = 'tbl_pembelian_log';
    protected $primaryKey = 'id_log';

    protected $fillable = [
        'id_pembelian',
        'status_lama',
        'status_baru',
        'sumber_perubahan',
        'keterangan',
    ];

    public function pembelian(): BelongsTo
    {
        return $this->belongsTo(Pembelian::class, 'id_pembelian', 'id_pembelian');
    }
}
