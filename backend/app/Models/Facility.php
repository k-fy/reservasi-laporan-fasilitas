<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reservation;
use App\Models\Report;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'location',
        'capacity',
        'description',
        'amenities',        
        'price_per_hour',  
        'contact_phone',    
        'status',
        'image',
    ];

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}