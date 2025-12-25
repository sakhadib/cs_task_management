<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PanelPosition extends Model
{
    use HasFactory;

    protected $table = 'panel_position';

    protected $fillable = [
        'panel_id',
        'position_id',
    ];

    public function panel()
    {
        return $this->belongsTo(Panel::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }
}