<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_current',
    ];

    public function positions()
    {
        return $this->hasMany(Position::class);
    }
}