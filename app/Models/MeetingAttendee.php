<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MeetingLog;
use App\Models\User;

class MeetingAttendee extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'meeting_log_id',
        'user_id',
        'status',
    ];

    /**
     * The meeting this attendee belongs to.
     */
    public function meetingLog()
    {
        return $this->belongsTo(MeetingLog::class, 'meeting_log_id');
    }

    /**
     * The user who attended the meeting.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
