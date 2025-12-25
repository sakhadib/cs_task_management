<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PanelUser extends Model
{
    use HasFactory;

    protected $table = 'panel_user';

    protected $fillable = [
        'panel_id',
        'user_id',
    ];

    public function panel()
    {
        return $this->belongsTo(Panel::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}