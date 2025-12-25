<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Panel;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TeamController extends Controller
{
    /**
     * Display a listing of teams for the current panel.
     */
    public function index(Request $request)
    {
        $currentPanel = Panel::where('is_current', true)->first();

        $teams = collect();
        if ($currentPanel) {
            $teams = Team::where('panel_id', $currentPanel->id)->with('users')->get();
        }

        return view('teams.index', compact('teams', 'currentPanel'));
    }

    /**
     * Store a newly created team for the current panel.
     */
    public function store(Request $request)
    {
        $currentPanel = Panel::where('is_current', true)->first();
        if (!$currentPanel) {
            return redirect()->route('teams.index')->with('error', 'No current panel selected.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:191',
            'description' => 'nullable|string',
        ]);

        $team = Team::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'panel_id' => $currentPanel->id,
        ]);

        return redirect()->route('teams.index')->with('success', 'Team created.');
    }

    /**
     * Show a single team and its members.
     */
    public function show(Team $team)
    {
        // load users with pivot info and ensure leader is first
        $users = $team->users()->withPivot('is_team_lead')->get()->sortByDesc(function($u) {
            return $u->pivot->is_team_lead ? 1 : 0;
        });

        return view('teams.show', compact('team', 'users'));
    }

    /**
     * Make the given user the team lead (only one lead at a time).
     */
    public function makeLead(Team $team, User $user)
    {
        // ensure user belongs to team
        if (!$team->users()->where('user_id', $user->id)->exists()) {
            return redirect()->back()->with('error', 'User not part of this team.');
        }

        // transactionally unset others and set this user as lead
        \DB::transaction(function() use ($team, $user) {
            // unset all
            \DB::table('team_user')->where('team_id', $team->id)->update(['is_team_lead' => false]);
            // set selected
            \DB::table('team_user')->where('team_id', $team->id)->where('user_id', $user->id)->update(['is_team_lead' => true]);
        });

        return redirect()->route('teams.show', $team->id)->with('success', 'Team lead updated.');
    }

    /**
     * Remove a user from the team.
     */
    public function removeUser(Team $team, User $user)
    {
        // ensure user belongs to team
        if (!$team->users()->where('user_id', $user->id)->exists()) {
            return redirect()->back()->with('error', 'User not part of this team.');
        }

        $team->users()->detach($user->id);

        return redirect()->route('teams.show', $team->id)->with('success', 'User removed from team.');
    }

    /**
     * Add a user to the team (is_team_lead => false by default).
     */
    public function addUser(Request $request, Team $team)
    {
        $data = $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('team_user', 'user_id')->where(function ($q) use ($team) {
                    return $q->where('team_id', $team->id);
                }),
            ],
        ], [
            'user_id.unique' => 'User is already a member of this team.',
        ]);

        $userId = $data['user_id'];

        $team->users()->attach($userId, ['is_team_lead' => false]);

        return redirect()->route('teams.show', $team->id)->with('success', 'User assigned to team.');
    }

    /**
     * Update team name/description.
     */
    public function update(Request $request, Team $team)
    {
        $data = $request->validate([
            'name' => 'required|string|max:191',
            'description' => 'nullable|string',
        ]);

        $team->update($data);

        return redirect()->route('teams.index')->with('success', 'Team updated.');
    }

    /**
     * Destroy the team.
     */
    public function destroy(Team $team)
    {
        $team->delete();
        return redirect()->route('teams.index')->with('success', 'Team deleted.');
    }
}
