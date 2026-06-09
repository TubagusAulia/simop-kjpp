<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
    ];

    protected static function booted()
    {
        static::created(function ($proyek) {
            $proyek->properti()->create([
                'nama_properti' => $proyek->nama_proyek,
            ]);
        });
    }

    // The admin who created this project
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // All user allocations for this project
    public function alokasi()
    {
        return $this->hasMany(AlokasiProyek::class, 'proyek_id');
    }

    // All users assigned to this project
    public function users()
    {
        return $this->belongsToMany(User::class, 'alokasi_proyek', 'proyek_id', 'user_id')
            ->withPivot('allocated_by', 'allocated_at');
    }

    // Specific role queries
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
}
