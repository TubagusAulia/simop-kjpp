<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Properti extends Model
{
    use HasFactory;

    protected $table = 'properti';

    protected $fillable = [
        'proyek_id',
        'tipe_properti',
        'nama_properti',
        'lokasi',
        'kategori',
    ];

    protected $casts = [];

    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'proyek_id');
    }

    public function dokumens()
    {
        return $this->hasMany(DokumenProperti::class, 'properti_id');
    }

    public function aspekFisiks()
    {
        return $this->hasMany(AspekFisik::class, 'properti_id');
    }

    public function checklistFisiks()
    {
        return $this->hasMany(ChecklistFisik::class, 'properti_id');
    }

    public function nilai()
    {
        return $this->hasOne(Nilai::class, 'properti_id');
    }
}
