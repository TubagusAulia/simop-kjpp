<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $properti_id
 * @property string $status
 * @property-read Properti $properti
 * @property-read User|null $completer
 */
class KoleksiNilai extends Model
{
    use HasFactory;

    protected $table = 'koleksi_nilai';

    protected $fillable = [
        'properti_id',
        'status',
        'completed_at',
        'completed_by',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function properti()
    {
        return $this->belongsTo(Properti::class, 'properti_id');
    }

    public function completer()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    /**
     * Mark this collection as complete by a karyawan.
     */
    public function markComplete(int $userId): void
    {
        $this->update([
            'status' => 'selesai',
            'completed_at' => now(),
            'completed_by' => $userId,
        ]);
    }

    /**
     * Check if nilai exists.
     */
    public function hasNilai(): bool
    {
        return $this->properti->nilai !== null;
    }

    /**
     * Get progression percentage.
     */
    public function getProgression(): int
    {
        if ($this->status === 'selesai') {
            return 100;
        }

        return $this->hasNilai() ? 50 : 0;
    }

    /**
     * Get the current task for this collection.
     * Returns ['role' => string, 'message' => string] or null if complete.
     */
    public function getTask(): ?array
    {
        if ($this->status === 'selesai') {
            return null;
        }

        if (! $this->hasNilai()) {
            return [
                'role' => 'Karyawan',
                'message' => 'Karyawan perlu melakukan penilaian properti (mengisi nilai dan catatan).',
            ];
        }

        return [
            'role' => 'Karyawan',
            'message' => 'Penilaian telah diisi. Karyawan dapat menandai penilaian sebagai selesai.',
        ];
    }
}
