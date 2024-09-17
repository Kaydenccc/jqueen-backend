<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Propertis extends Model
{
    use HasFactory;

    protected $table = 'propertis';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
            "nama",
            "alamat",
            "type",
            "harga",
            "baru", 
            'thumbnail',
            "sertifikat", 
            "jumlah_kamar_tidur", 
            "jumlah_kamar_mandi", 
            "jumlah_dapur",
            "kolam_renang", 
            "gudang",
            "garasi",
            "tingkat",
            "listrik",
            "luas_tanah",
            "luas_bangunan",
            "deskripsi",
            "lokasi",
            "vr"
    ];
    protected $casts = [
        'baru' => 'boolean',
    ];

    public function images(): HasMany {
        return $this->hasMany(Images::class, 'properti_id', 'id');
    }


}
