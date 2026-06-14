<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KoleksiDokumen extends Model
{
    use HasFactory;

    protected $table = 'koleksi_dokumen';

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
     * Get the wajib document keys for this collection.
     */
    public function getWajibKeys(): array
    {
        return $this->wajib_list ?? [];
    }

    /**
     * Check if all wajib documents have been uploaded.
     */
    public function isWajibUploaded(): bool
    {
        $wajibKeys = $this->getWajibKeys();
        if (empty($wajibKeys)) return true;

        $uploadedTypes = $this->properti->dokumens->pluck('tipe_dokumen')->toArray();

        foreach ($wajibKeys as $key) {
            if (!in_array($key, $uploadedTypes)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check if all uploaded documents (wajib + opsional) are verified.
     */
    public function isAllVerified(): bool
    {
        $allDocs = $this->properti->dokumens;
        if ($allDocs->isEmpty()) return true;

        return $allDocs->every(fn($d) => $d->status === 'terverifikasi');
    }

    /**
     * Get the number of wajib documents uploaded.
     */
    public function getWajibUploadedCount(): int
    {
        $wajibKeys = $this->getWajibKeys();
        if (empty($wajibKeys)) return 0;

        $uploadedTypes = $this->properti->dokumens->pluck('tipe_dokumen')->toArray();
        return count(array_intersect($wajibKeys, $uploadedTypes));
    }

    /**
     * Get the number of verified documents (all).
     */
    public function getVerifiedCount(): int
    {
        return $this->properti->dokumens->where('status', 'terverifikasi')->count();
    }

    /**
     * Get total document count.
     */
    public function getTotalCount(): int
    {
        return $this->properti->dokumens->count();
    }

    /**
     * Get progression percentage.
     * 0-49%: wajib uploading phase
     * 50-99%: verification phase
     * 100%: all verified
     */
    public function getProgression(): int
    {
        $wajibKeys = $this->getWajibKeys();
        $totalWajib = count($wajibKeys);
        $totalDocs = $this->getTotalCount();
        $verifiedDocs = $this->getVerifiedCount();

        if ($totalWajib === 0 && $totalDocs === 0) return 0;

        // Phase 1: uploading wajib (0-50%)
        $uploadProgress = 0;
        if ($totalWajib > 0) {
            $uploadedWajib = $this->getWajibUploadedCount();
            $uploadProgress = ($uploadedWajib / $totalWajib) * 50;
        } else {
            $uploadProgress = 50; // no wajib = skip to verification
        }

        // Phase 2: verification (50-100%)
        $verifyProgress = 0;
        if ($totalDocs > 0) {
            $verifyProgress = ($verifiedDocs / $totalDocs) * 50;
        }

        return (int) round($uploadProgress + $verifyProgress);
    }

    /**
     * Get the current task for this collection.
     * Returns ['role' => string, 'message' => string] or null if complete.
     */
    public function getTask(): ?array
    {
        if ($this->status === 'selesai') return null;

        $wajibKeys = $this->getWajibKeys();

        // Check wajib upload
        if (!empty($wajibKeys)) {
            $uploadedTypes = $this->properti->dokumens->pluck('tipe_dokumen')->toArray();
            $missingWajib = array_filter($wajibKeys, fn($key) => !in_array($key, $uploadedTypes));

            if (!empty($missingWajib)) {
                // Build human-readable missing labels
                $missingLabels = array_map(function ($key) {
                    $globalReqs = \App\Services\DocumentRequirementService::getGlobalRequirements();
                    $typeReqs = \App\Services\DocumentRequirementService::getTypeRequirements($this->properti->tipe_properti);
                    $allLabels = array_merge($globalReqs, $typeReqs['mandatory'] ?? []);
                    return $allLabels[$key] ?? $key;
                }, $missingWajib);

                return [
                    'role' => 'Klien',
                    'message' => 'Klien perlu mengunggah dokumen wajib yang belum lengkap: ' . implode(', ', array_values($missingLabels)) . '.',
                ];
            }
        }

        // Check verification (all docs: wajib + opsional)
        $allDocs = $this->properti->dokumens;
        $unverified = $allDocs->where('status', 'menunggu');
        if ($unverified->isNotEmpty()) {
            return [
                'role' => 'Karyawan',
                'message' => 'Karyawan perlu memverifikasi dokumen yang belum diverifikasi (' . $unverified->count() . ' dokumen menunggu).',
            ];
        }

        // All done — ready for karyawan to mark complete
        return [
            'role' => 'Karyawan',
            'message' => 'Semua dokumen telah diverifikasi. Karyawan dapat memindahkan fase ke Verifikasi Fisik.',
        ];
    }
}
