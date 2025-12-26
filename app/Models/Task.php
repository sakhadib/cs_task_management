<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'state',
        'team_id',
        'user_id',
        'created_by',
        'panel_id',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function panel()
    {
        return $this->belongsTo(Panel::class);
    }

    public function histories()
    {
        return $this->hasMany(TaskHistory::class);
    }
}