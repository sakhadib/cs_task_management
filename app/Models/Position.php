<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'panel_id',
        'position',
        'level',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function panel()
    {
        return $this->belongsTo(Panel::class);
    }
}