<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Facility;

class Report extends Model
{
    use HasFactory;

    public const STATUS_NEW = 'New';
    public const STATUS_PROGRESS = 'Progress';
    public const STATUS_RESOLVED = 'Resolved';

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

    protected $attributes = [
        'status'           => self::STATUS_NEW,
        'category'         => 'General', 
        'photo'            => null,
        'resolution_notes' => null,
        'handled_by'       => null,
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