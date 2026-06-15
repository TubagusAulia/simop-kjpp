<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistFisik extends Model
{
    use HasFactory;

    protected $table = 'checklist_fisik';

    protected $fillable = [
        'properti_id',
        'nama_item',
        'tipe',
        'created_by',
    ];

    public function properti()
    {
        return $this->belongsTo(Properti::class, 'properti_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function aspekFisiks()
    {
        return $this->hasMany(AspekFisik::class, 'checklist_fisik_id');
    }

    /**
     * Get the latest verification status for this checklist item.
     * Returns: 'terverifikasi', 'ditolak', 'menunggu', or 'belum' (no submission yet)
     */
    public function verificationStatus(): string
    {
        $latest = $this->aspekFisiks()->latest()->first();

        return $latest ? $latest->status : 'belum';
    }
}
