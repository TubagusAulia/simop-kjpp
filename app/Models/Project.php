<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'nama_project',
        'contract_date',
        'contact_person',
        'deskripsi',
        'status',
        'kategori',
        'asal_instansi',
        'tanggal_mulai',
        'dokumen',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'contract_date' => 'date',
        'tanggal_mulai' => 'datetime',
        'created_at' => 'datetime',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function documents()
    {
        return $this->hasMany(ProjectDocument::class);
    }

    public function nilai()
    {
        return $this->hasOne(Nilai::class, 'project_id');
    }
}
