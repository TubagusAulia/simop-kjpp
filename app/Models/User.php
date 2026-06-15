<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
        'profile_photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo) {
            return asset('storage/'.$this->profile_photo);
        }

        return asset('images/profile-user.svg');
    }

    // Role check helpers
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isKaryawan(): bool
    {
        return $this->role === 'karyawan';
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    public function isMitra(): bool
    {
        return $this->role === 'mitra';
    }

    // Projects assigned to this user
    public function proyeks()
    {
        return $this->belongsToMany(Proyek::class, 'alokasi_proyek', 'user_id', 'proyek_id')
            ->withPivot('allocated_by', 'allocated_at');
    }
}
