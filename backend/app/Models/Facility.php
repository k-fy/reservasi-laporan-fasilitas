<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Reservation;
use App\Models\Report;

class Facility extends Model
{
    protected $fillable = [
        'name',
        'type',
        'location',
        'capacity',
        'description',
        'status',
        'image',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}