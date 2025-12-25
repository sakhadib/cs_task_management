<?php

namespace App\Http\Controllers;

use App\Models\MeetingLog;
use App\Models\MeetingAttendee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MeetingAttendeeController extends Controller
{
    /**
     * Show attendee management page for a meeting.
     */
    public function index(MeetingLog $log)
    {
        $now = \Carbon\Carbon::now();
        $start = \Carbon\Carbon::parse($log->scheduled_at);
        $durationMinutes = $log->duration ?? 30;
        $end = (clone $start)->addMinutes($durationMinutes);
        if ($now->lt($start)) {
            $status = 'scheduled';
        } elseif ($now->between($start, $end)) {
            $status = 'running';
        } else {
            $status = 'completed';
        }

        $attendees = MeetingAttendee::with('user')->where('meeting_log_id', $log->id)->get();

        return view('meeting_logs.attendees', ['log' => $log, 'status' => $status, 'attendees' => $attendees]);
    }

    /**
     * Mark a user as attendee for the meeting.
     */
    public function store(Request $request, MeetingLog $log)
    {
        $data = $request->only(['user_id']);

        $validator = Validator::make($data, [
            'user_id' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // prevent duplicates
        $exists = MeetingAttendee::where('meeting_log_id', $log->id)->where('user_id', $data['user_id'])->first();
        if ($exists) {
            return redirect()->back()->with('error', 'User is already marked as attendee.');
        }

        MeetingAttendee::create([
            'meeting_log_id' => $log->id,
            'user_id' => $data['user_id'],
        ]);

        return redirect()->route('meeting_logs.attendees.index', $log->id)->with('success', 'Attendee added.');
    }

    /**
     * Unmark attendee.
     */
    public function destroy(MeetingLog $log, MeetingAttendee $attendee)
    {
        if ($attendee->meeting_log_id !== $log->id) {
            return redirect()->back()->with('error', 'Invalid attendee.');
        }
        $attendee->delete();
        return redirect()->route('meeting_logs.attendees.index', $log->id)->with('success', 'Attendee removed.');
    }
}
