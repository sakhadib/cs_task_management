<?php

namespace App\Http\Controllers;

use App\Models\MeetingLog;
use App\Models\Panel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class MeetingLogController extends Controller
{
    /**
     * Display a listing of meeting logs.
     */
    public function index(Request $request)
    {
        $currentPanel = Panel::where('is_current', true)->first();
        $panelId = $currentPanel ? $currentPanel->id : 0;

        // get all logs for panel (or none) and compute status per item
        $all = MeetingLog::where('panel_id', $panelId)
            ->orderBy('scheduled_at', 'desc')
            ->get()
            ->map(function ($log) {
                $now = Carbon::now();
                $start = Carbon::parse($log->scheduled_at);
                $durationMinutes = $log->duration ?? 30;
                $end = (clone $start)->addMinutes($durationMinutes);
                if ($now->lt($start)) {
                    $log->status = 'scheduled';
                } elseif ($now->between($start, $end)) {
                    $log->status = 'running';
                } else {
                    $log->status = 'completed';
                }
                return $log;
            });

        $filter = $request->query('filter', 'scheduled');

        if ($filter === 'all') {
            $filtered = $all;
        } else {
            $filtered = $all->filter(function ($log) use ($filter) {
                return $log->status === $filter;
            })->values();
        }

        // optional date filter (YYYY-MM-DD) to show meetings only for that day
        $date = $request->query('date');
        $selectedDate = null;
        if ($date) {
            try {
                $selectedDate = Carbon::createFromFormat('Y-m-d', $date);
            } catch (\Exception $e) {
                $selectedDate = null;
            }
            if ($selectedDate) {
                $filtered = $filtered->filter(function ($log) use ($selectedDate) {
                    return Carbon::parse($log->scheduled_at)->isSameDay($selectedDate);
                })->values();
            }
        }

        // manual pagination of collection
        $perPage = 20;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $filtered->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $paginator = new LengthAwarePaginator($currentItems, $filtered->count(), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'query' => $request->query(),
        ]);

        return view('meeting_logs.index', ['logs' => $paginator, 'currentPanel' => $currentPanel, 'currentFilter' => $filter, 'selectedDate' => $selectedDate ? $selectedDate->toDateString() : null]);
    }

    /**
     * Display the specified meeting log details.
     */
    public function show(MeetingLog $log)
    {
        $now = Carbon::now();
        $start = Carbon::parse($log->scheduled_at);
        $durationMinutes = $log->duration ?? 30;
        $end = (clone $start)->addMinutes($durationMinutes);
        if ($now->lt($start)) {
            $status = 'scheduled';
        } elseif ($now->between($start, $end)) {
            $status = 'running';
        } else {
            $status = 'completed';
        }

        $panel = $log->panel_id ? Panel::find($log->panel_id) : null;

        // load attendees with user relation
        $attendees = \App\Models\MeetingAttendee::with('user')->where('meeting_log_id', $log->id)->get();

        return view('meeting_logs.show', ['log' => $log, 'status' => $status, 'panel' => $panel, 'attendees' => $attendees]);
    }

    /**
     * Update only the meeting minutes for a meeting log.
     */
    public function updateMinutes(Request $request, MeetingLog $log)
    {
        $data = $request->only(['meeting_minutes']);

        $validator = Validator::make($data, [
            'meeting_minutes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $log->meeting_minutes = $data['meeting_minutes'] ?? null;
        $log->save();

        return redirect()->route('meeting_logs.show', $log->id)->with('success', 'Meeting minutes updated.');
    }

    /**
     * Store a newly created meeting log.
     */
    public function store(Request $request)
    {
        // Do NOT accept panel_id from user; derive from current panel
        $data = $request->only(['type', 'joining_url', 'location', 'scheduled_at', 'duration', 'meeting_minutes']);
        $currentPanel = Panel::where('is_current', true)->first();
        $data['panel_id'] = $currentPanel ? $currentPanel->id : 0;

        $validator = Validator::make($data, [
            'type' => 'required|in:online,offline',
            'joining_url' => 'nullable|url',
            'location' => 'nullable|string|max:255',
            'scheduled_at' => 'required|date|after_or_equal:now',
            'duration' => 'nullable|integer',
            'meeting_minutes' => 'nullable|string',
            'panel_id' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // If no duration provided on create, default to 30 minutes.
        if (empty($data['duration'])) {
            $data['duration'] = 30;
        }

        MeetingLog::create($data);

        return redirect()->route('meeting_logs.index')->with('success', 'Meeting created.');
    }

    /**
     * Update the specified meeting log.
     */
    public function update(Request $request, MeetingLog $log)
    {
        $data = $request->only(['type', 'joining_url', 'location', 'scheduled_at', 'duration', 'meeting_minutes']);

        $validator = Validator::make($data, [
            'type' => 'required|in:online,offline',
            'joining_url' => 'nullable|url',
            'location' => 'nullable|string|max:255',
            'scheduled_at' => 'required|date|after_or_equal:now',
            'duration' => 'nullable|integer',
            'meeting_minutes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $log->update($data);

        return redirect()->route('meeting_logs.index')->with('success', 'Meeting updated.');
    }

    /**
     * Remove the specified meeting log.
     */
    public function destroy(MeetingLog $log)
    {
        $log->delete();
        return redirect()->route('meeting_logs.index')->with('success', 'Meeting deleted.');
    }
}
