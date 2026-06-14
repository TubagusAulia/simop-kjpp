<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KoleksiFisik extends Model
{
    use HasFactory;

    protected $table = 'koleksi_fisik';

    protected $fillable = [
        'properti_id',
        'status',
        'wajib_list',
        'completed_at',
        'completed_by',
    ];

    protected $casts = [
        'wajib_list' => 'array',
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
     * Get the wajib fisik item names for this collection.
     */
    public function getWajibItems(): array
    {
        return $this->wajib_list ?? [];
    }

    /**
     * Set the wajib list (when Karyawan creates it).
     */
    public function setWajibList(array $items): void
    {
        $this->update(['wajib_list' => $items]);
    }

    /**
     * Check if wajib list has been created.
     */
    public function hasWajibList(): bool
    {
        return !empty($this->wajib_list);
    }

    /**
     * Check if all wajib items have been fulfilled (have at least one aspek fisik submission).
     */
    public function isWajibFulfilled(): bool
    {
        $wajibItems = $this->getWajibItems();
        if (empty($wajibItems)) return true;

        // Wajib items are tracked via checklist_fisik table
        $checklistItems = $this->properti->checklistFisiks;
        if ($checklistItems->isEmpty()) return false;

        foreach ($checklistItems as $item) {
            if ($item->verificationStatus() === 'belum') {
                return false;
            }
        }
        return true;
    }

    /**
     * Check if all submitted aspek fisik (wajib + opsional) are verified.
     */
    public function isAllVerified(): bool
    {
        $allAspeks = $this->properti->aspekFisiks;
        if ($allAspeks->isEmpty()) return true;

        return $allAspeks->every(fn($a) => $a->status === 'terverifikasi');
    }

    /**
     * Get count of wajib items fulfilled.
     */
    public function getWajibFulfilledCount(): int
    {
        $checklistItems = $this->properti->checklistFisiks;
        if ($checklistItems->isEmpty()) return 0;

        return $checklistItems->filter(fn($c) => $c->verificationStatus() !== 'belum')->count();
    }

    /**
     * Get total wajib count.
     */
    public function getWajibTotalCount(): int
    {
        return count($this->getWajibItems());
    }

    /**
     * Get count of verified aspek fisik.
     */
    public function getVerifiedCount(): int
    {
        return $this->properti->aspekFisiks->where('status', 'terverifikasi')->count();
    }

    /**
     * Get total aspek fisik count.
     */
    public function getTotalCount(): int
    {
        return $this->properti->aspekFisiks->count();
    }

    /**
     * Check if project has mitra assigned.
     */
    public function hasMitra(): bool
    {
        $properti = $this->properti;
        if (!$properti || !$properti->proyek) return false;
        return $properti->proyek->mitras()->exists();
    }

    /**
     * Get the proyek through the properti relationship.
     * Note: This returns the Proyek model instance, not a query builder.
     * Use hasMitra() for query-based checks.
     */
    public function proyek(): ?\App\Models\Proyek
    {
        $properti = $this->properti;
        return $properti ? $properti->proyek : null;
    }

    /**
     * Get progression percentage.
     */
    public function getProgression(): int
    {
        $wajibItems = $this->getWajibItems();
        $totalWajib = count($wajibItems);
        $totalAspeks = $this->getTotalCount();
        $verifiedAspeks = $this->getVerifiedCount();

        // No wajib list yet
        if ($totalWajib === 0) return 0;

        // Phase 1: fulfilling wajib (0-50%)
        $fulfillProgress = 0;
        $fulfilledWajib = $this->getWajibFulfilledCount();
        $fulfillProgress = ($fulfilledWajib / $totalWajib) * 50;

        // Phase 2: verification (50-100%)
        $verifyProgress = 0;
        if ($totalAspeks > 0) {
            $verifyProgress = ($verifiedAspeks / $totalAspeks) * 50;
        }

        return (int) round($fulfillProgress + $verifyProgress);
    }

    /**
     * Get the current task for this collection.
     * Returns ['role' => string, 'message' => string] or null if complete.
     */
    public function getTask(): ?array
    {
        if ($this->status === 'selesai') return null;

        $wajibItems = $this->getWajibItems();

        // No wajib list created yet
        if (empty($wajibItems)) {
            return [
                'role' => 'Karyawan',
                'message' => 'Karyawan perlu membuat daftar aspek fisik wajib, atau langsung memindahkan fase ke Penilaian jika aspek fisik tidak diperlukan.',
            ];
        }

        // Check wajib fulfillment
        $checklistItems = $this->properti->checklistFisiks;
        if ($checklistItems->isNotEmpty()) {
            $unfilled = $checklistItems->filter(fn($c) => $c->verificationStatus() === 'belum');
            if ($unfilled->isNotEmpty()) {
                $mitraRole = $this->hasMitra() ? 'Karyawan / Mitra' : 'Karyawan';
                return [
                    'role' => $mitraRole,
                    'message' => $mitraRole . ' perlu menambahkan aspek fisik untuk item wajib yang belum terisi (' . $unfilled->count() . ' item belum terisi).',
                ];
            }
        }

        // Check verification
        $allAspeks = $this->properti->aspekFisiks;
        $pendingAspeks = $allAspeks->where('status', 'menunggu');
        if ($pendingAspeks->isNotEmpty()) {
            return [
                'role' => 'Karyawan',
                'message' => 'Karyawan perlu memverifikasi aspek fisik yang belum diverifikasi (' . $pendingAspeks->count() . ' aspek menunggu).',
            ];
        }

        // All done — ready for karyawan to mark complete
        return [
            'role' => 'Karyawan',
            'message' => 'Semua aspek fisik telah diverifikasi. Karyawan dapat memindahkan fase ke Penilaian Properti.',
        ];
    }
}
