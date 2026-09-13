<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $fillable = ['name', 'type', 'location', 'capacity', 'description', 'amenities', 'price_per_hour', 'contact_phone', 'image', 'status'];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
