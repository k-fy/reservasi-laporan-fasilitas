<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Facility;

class Report extends Model
{
    protected $fillable = [
        'user_id',
        'facility_id',
        'category',
        'description',
        'photo',
        'status',
        'resolution_notes',
        'handled_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function handledBy()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }
}