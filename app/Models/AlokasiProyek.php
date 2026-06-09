<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AlokasiProyek extends Model
{
    use HasFactory;

    protected $table = 'alokasi_proyek';

    protected $fillable = [
        'proyek_id',
        'user_id',
        'allocated_by',
        'allocated_at',
    ];

    public $timestamps = false;

    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'proyek_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function allocator()
    {
        return $this->belongsTo(User::class, 'allocated_by');
    }
}
