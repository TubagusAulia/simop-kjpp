<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $nama_proyek
 * @property string|null $deskripsi
 * @property string $status
 * @property string|null $current_phase
 * @property int|null $created_by
 * @property bool $finish_requested
 * @property-read User|null $creator
 * @property-read Properti|null $properti
 * @property-read \Illuminate\Database\Eloquent\Collection $alokasi
 * @property-read \Illuminate\Database\Eloquent\Collection $users
 * @property-read \Illuminate\Database\Eloquent\Collection $karyawans
 * @property-read \Illuminate\Database\Eloquent\Collection $clients
 * @property-read \Illuminate\Database\Eloquent\Collection $mitras
 */
class Proyek extends Model
{
    use HasFactory;

    protected $table = 'proyek';

    protected $fillable = [
        'nama_proyek',
        'deskripsi',
        'start_date',
        'due_date',
        'status',
        'current_phase',
        'kontrak_file',
        'created_by',
        'finish_requested',
        'finish_requested_by',
        'finish_requested_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
        'finish_requested_at' => 'datetime',
        'finish_requested' => 'boolean',
    ];

    protected static function booted()
    {
        static::created(function ($proyek) {
            $proyek->properti()->create([
                'nama_properti' => $proyek->nama_proyek,
            ]);
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function alokasi()
    {
        return $this->hasMany(AlokasiProyek::class, 'proyek_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'alokasi_proyek', 'proyek_id', 'user_id')
            ->withPivot('allocated_by', 'allocated_at');
    }

    public function karyawans()
    {
        return $this->belongsToMany(User::class, 'alokasi_proyek', 'proyek_id', 'user_id')
            ->where('users.role', 'karyawan');
    }

    public function clients()
    {
        return $this->belongsToMany(User::class, 'alokasi_proyek', 'proyek_id', 'user_id')
            ->where('users.role', 'client');
    }

    public function mitras()
    {
        return $this->belongsToMany(User::class, 'alokasi_proyek', 'proyek_id', 'user_id')
            ->where('users.role', 'mitra');
    }

    public function properti()
    {
        return $this->hasOne(Properti::class, 'proyek_id');
    }

    /**
     * Get the current active collection based on phase.
     */
    public function getCurrentCollection()
    {
        $properti = $this->properti;
        if (! $properti) {
            return null;
        }

        $phase = $this->current_phase ?? 'dokumen';

        return match ($phase) {
            'dokumen' => $properti->koleksiDokumen,
            'fisik' => $properti->koleksiFisik,
            'dinilai' => $properti->koleksiNilai,
            default => null,
        };
    }

    /**
     * Get the current task from the active collection.
     * Returns ['role' => string, 'message' => string] or null.
     */
    public function getCurrentTask(): ?array
    {
        $phase = $this->current_phase ?? 'dokumen';

        // Project complete — no task
        if ($phase === 'selesai') {
            return null;
        }

        $collection = $this->getCurrentCollection();
        if (! $collection) {
            return null;
        }

        return $collection->getTask();
    }
}
