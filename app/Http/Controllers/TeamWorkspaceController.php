<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Task;
use App\Models\TeamUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TeamWorkspaceController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        // teams where user is team lead
        $leadTeamIds = TeamUser::where('user_id', $userId)
            ->where('is_team_lead', 1)
            ->pluck('team_id')
            ->toArray();

        // teams where user is a member (including leads)
        $memberTeamIds = TeamUser::where('user_id', $userId)
            ->pluck('team_id')
            ->toArray();

        // combine (members can view workspace; leads can assign)
        $teamIds = array_values(array_unique(array_merge($leadTeamIds, $memberTeamIds)));

        $teams = Team::whereIn('id', $teamIds)->get();

        $query = Task::query()->with(['team', 'user', 'creator']);
        $query->whereIn('team_id', $teamIds);

        // optional team filter
        if ($request->has('team') && $request->team) {
            $query->where('team_id', $request->team);
        }

        // state filter (important)
        if ($request->has('state') && $request->state !== 'all') {
            $query->where('state', $request->state);
        }

        $tasks = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        $states = ['all' => 'All', 'working' => 'Working', 'submitted to review' => 'Submitted to Review', 'completed' => 'Completed'];

        return view('team_workspace.index', compact('teams', 'tasks', 'states', 'leadTeamIds'));
    }

    public function teamMembers(Request $request, $teamId)
    {
        $q = $request->query('query', '');

        $userIds = TeamUser::where('team_id', $teamId)->pluck('user_id')->toArray();

        $users = User::whereIn('id', $userIds)
            ->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%")
                  ->orWhere('student_id', 'like', "%{$q}%");
            })
            ->limit(10)
            ->get(['id', 'name', 'email', 'student_id']);

        return response()->json($users);
    }

    public function assign(Request $request, Task $task)
    {
        $userId = Auth::id();

        // ensure current user is team lead for the task's team
        $isLead = TeamUser::where('team_id', $task->team_id)
            ->where('user_id', $userId)
            ->where('is_team_lead', 1)
            ->exists();

        if (! $isLead) {
            abort(403);
        }

        $data = $request->validate([
            'user_id' => 'required|integer',
        ]);

        // ensure selected user belongs to same team
        $member = TeamUser::where('team_id', $task->team_id)
            ->where('user_id', $data['user_id'])
            ->exists();

        if (! $member) {
            return redirect()->back()->withErrors(['user_id' => 'Selected user is not a member of the team.']);
        }

        $previousUserId = $task->user_id;
        $task->user_id = $data['user_id'];
        // set state depending on whether it was previously assigned
        if (is_null($previousUserId)) {
            $task->state = 'assigned to user';
        } else {
            $task->state = 'reassigned to user';
        }
        $task->save();

        return redirect()->back()->with('success', 'Task assigned successfully.');
    }
}
