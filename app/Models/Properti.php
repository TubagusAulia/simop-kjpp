<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $proyek_id
 * @property string|null $tipe_properti
 * @property string $nama_properti
 * @property-read Proyek $proyek
 * @property-read Collection $dokumens
 * @property-read Collection $aspekFisiks
 * @property-read Collection $checklistFisiks
 * @property-read Nilai|null $nilai
 * @property-read KoleksiDokumen|null $koleksiDokumen
 * @property-read KoleksiFisik|null $koleksiFisik
 * @property-read KoleksiNilai|null $koleksiNilai
 */
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

    // Note: Koleksi auto-creation is handled by the seeder for test data,
    // and by the application logic (via ProyekController) for real data.

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

    // Collection relationships
    public function koleksiDokumen()
    {
        return $this->hasOne(KoleksiDokumen::class, 'properti_id');
    }

    public function koleksiFisik()
    {
        return $this->hasOne(KoleksiFisik::class, 'properti_id');
    }

    public function koleksiNilai()
    {
        return $this->hasOne(KoleksiNilai::class, 'properti_id');
    }
}
