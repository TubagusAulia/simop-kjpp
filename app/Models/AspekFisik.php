<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AspekFisik extends Model
{
    use HasFactory;

    protected $table = 'aspek_fisik';

    protected $fillable = [
        'properti_id',
        'checklist_fisik_id',
        'nama_aspek',
        'deskripsi',
        'foto_paths',
        'latitude',
        'longitude',
        'tipe',
        'status',
        'created_by',
        'verified_by',
        'catatan',
        'verified_at',
    ];

    protected $casts = [
        'foto_paths' => 'array',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'verified_at' => 'datetime',
    ];

    public function properti()
    {
        return $this->belongsTo(Properti::class, 'properti_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function checklistFisik()
    {
        return $this->belongsTo(ChecklistFisik::class, 'checklist_fisik_id');
    }
}
