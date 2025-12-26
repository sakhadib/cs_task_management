<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Panel;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $states = [
            'pending assignment',
            'team assigned',
            'assigned to user',
            'reassigned to user',
            'working',
            'submitted to review',
            'completed',
        ];


        $currentState = $request->query('state');

        $currentPanel = Panel::where('is_current', true)->first();
        $teams = $currentPanel ? Team::where('panel_id', $currentPanel->id)->get() : collect();

        $query = Task::with(['team', 'user', 'creator', 'panel'])->orderBy('created_at', 'desc');

        // Only show tasks for the current panel. If none, return an empty result set.
        if ($currentPanel) {
            $query->where('panel_id', $currentPanel->id);
        } else {
            $query->whereRaw('0 = 1');
        }

        if ($currentState && in_array($currentState, $states)) {
            $query->where('state', $currentState);
        }

        $tasks = $query->paginate(20);

        // Counts per state (limit to current panel if set)
        $countQuery = Task::query();
        if ($currentPanel) {
            $countQuery->where('panel_id', $currentPanel->id);
        }
        $counts = $countQuery->select('state', DB::raw('count(*) as count'))->groupBy('state')->pluck('count', 'state')->toArray();
        $totalCount = ($currentPanel ? $countQuery->count() : Task::count());

        return view('tasks.index', compact('tasks', 'teams', 'currentPanel', 'states', 'currentState', 'counts', 'totalCount'));
    }

    public function create()
    {
        $currentPanel = Panel::where('is_current', true)->first();
        $teams = $currentPanel ? Team::where('panel_id', $currentPanel->id)->get() : collect();
        return view('tasks.create', compact('teams', 'currentPanel'));
    }

    public function store(Request $request)
    {
        $currentPanel = Panel::where('is_current', true)->first();

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'team_id' => 'nullable|integer',
        ]);

        $user = $request->user();
        if (! $user) {
            return redirect()->route('login')->with('error', 'Please login to create a task.');
        }

        if (!empty($data['team_id'])) {
            // ensure team belongs to current panel
            $teamOk = Team::where('id', $data['team_id'])
                ->when($currentPanel, function ($q) use ($currentPanel) { $q->where('panel_id', $currentPanel->id); })
                ->exists();
            if (! $teamOk) {
                return back()->withErrors(['team_id' => 'Selected team is invalid for the current panel.'])->withInput();
            }
        }

        $task = new Task();
        $task->title = $data['title'];
        $task->description = $data['description'] ?? null;
        $task->team_id = $data['team_id'] ?? null;
        $task->user_id = null;
        $task->created_by = $user->id;
        $task->panel_id = $currentPanel ? $currentPanel->id : null;
        // Set state: if team selected -> 'team assigned', else -> 'pending assignment'
        $task->state = !empty($data['team_id']) ? 'team assigned' : 'pending assignment';
        $task->save();

        return redirect()->route('tasks.index')->with('success', 'Task created.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted.');
    }

    public function show(Task $task)
    {
        $task->load(['team', 'user', 'creator', 'panel']);
        $histories = $task->histories()->with('user')->orderBy('created_at', 'desc')->get();
        return view('tasks.show', compact('task', 'histories'));
    }
    
    public function update(Request $request, Task $task)
    {
        $currentPanel = Panel::where('is_current', true)->first();

        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'team_id' => 'nullable|integer|exists:teams,id',
        ];

        $data = $request->validate($rules);

        // ensure team belongs to current panel if provided
        if (!empty($data['team_id']) && $currentPanel) {
            $belongs = $currentPanel->teams()->where('id', $data['team_id'])->exists();
            if (!$belongs) {
                return redirect()->back()->withErrors(['team_id' => 'Selected team is not part of the current panel.']);
            }
        }

        $task->title = $data['title'];
        $task->description = $data['description'] ?? $task->description;
        $task->team_id = $data['team_id'] ?? null;
        $task->save();

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function changeState(Request $request, Task $task)
    {
        $data = $request->validate([
            'state' => 'required|string',
        ]);

        $allowed = ['working', 'submitted to review', 'completed'];
        if (!in_array($data['state'], $allowed)) {
            return back()->withErrors(['state' => 'Invalid state selected.']);
        }

        $task->state = $data['state'];
        $task->save();

        return back()->with('success', 'Task state updated to "' . $task->state . '".');
    }

    public function assign(Request $request, Task $task)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $newUserId = (int) $data['user_id'];

        if ($task->user_id) {
            if ($task->user_id === $newUserId) {
                return back()->with('info', 'User already assigned to this task.');
            }
            $task->user_id = $newUserId;
            $task->state = 'reassigned to user';
        } else {
            $task->user_id = $newUserId;
            $task->state = 'assigned to user';
        }

        $task->save();

        return back()->with('success', 'Task assignee updated.');
    }
}
