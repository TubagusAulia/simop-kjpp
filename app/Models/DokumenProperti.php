<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DokumenProperti extends Model
{
    use HasFactory;

    protected $table = 'dokumen_properti';

    protected $fillable = [
        'properti_id',
        'uploaded_by',
        'tipe_dokumen',
        'nama_dokumen',
        'deskripsi',
        'file_path',
        'status',
        'catatan',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function properti()
    {
        return $this->belongsTo(Properti::class, 'properti_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
