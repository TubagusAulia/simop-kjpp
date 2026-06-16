<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilai';

    protected $fillable = [
        'properti_id',
        'nilai',
        'catatan',
        'created_by',
    ];

    public function properti()
    {
        return $this->belongsTo(Properti::class, 'properti_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
