<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Reservation;
use App\Models\Report;

#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Kolom yang diizinkan untuk disimpan (Mass Assignment).
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nim_nip',
        'unit',
        'role',
        'status',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // Otomatis meng-hash password saat disimpan
        ];
    }
}