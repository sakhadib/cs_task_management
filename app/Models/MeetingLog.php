<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'joining_url',
        'location',
        'scheduled_at',
        'duration',
        'meeting_minutes',
        'panel_id',
    ];
}