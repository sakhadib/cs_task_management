<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TeamUser;
use App\Models\Task;

class EnforceRolePermissions
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::check()) {
            return $next($request);
        }

        $role = Auth::user()->role;

        // Admin can do everything
        if ($role === 'admin') {
            return $next($request);
        }

        $routeName = optional($request->route())->getName() ?: '';

        // User management routes -> only admin
        if (str_starts_with($routeName, 'users.') || $routeName === 'users.index' || $routeName === 'users.create') {
            abort(403, 'Forbidden: user management restricted to admins.');
        }

        // Members cannot create/edit/delete tasks, teams, or panels
        if ($role === 'member') {
            $restrictedRoutes = [
                'tasks.create', 'tasks.store', 'tasks.update', 'tasks.destroy',
                'teams.index', 'teams.store', 'teams.show', 'teams.update', 'teams.destroy',
                'teams.makeLead', 'teams.removeUser', 'teams.addUser',
                'panels.index', 'panels.create', 'panels.store', 'panels.edit',
                'panels.update', 'panels.destroy', 'panels.makeCurrent',
                'panels.positions', 'panels.positions.store',
                'positions.update', 'positions.destroy',
                'meeting_logs.destroy', // members cannot delete meetings
            ];

            if (in_array($routeName, $restrictedRoutes)) {
                abort(403, 'Forbidden: members cannot perform this action.');
            }
        }

        // Task assignment routes: only admin/advisor or team lead (for members)
        if (in_array($routeName, ['tasks.assign', 'team_workspace.assign'])) {
            if ($role === 'advisor') {
                return $next($request);
            }

            // member: allow only if team lead for the task's team
            $task = $request->route('task');
            if (! $task instanceof Task) {
                $task = Task::find($task);
            }

            if (! $task) {
                abort(404);
            }

            $isLead = TeamUser::where('team_id', $task->team_id)
                ->where('user_id', Auth::id())
                ->where('is_team_lead', 1)
                ->exists();

            if (! $isLead) {
                abort(403, 'Forbidden: only team leads can assign for this team.');
            }

            return $next($request);
        }

        // Task state change route: members can only mark 'working' or 'submitted to review' if they are the assignee
        if ($routeName === 'tasks.changeState') {
            $state = $request->input('state');
            $taskParam = $request->route('task');
            if (! $taskParam instanceof Task) {
                $task = Task::find($taskParam);
            } else {
                $task = $taskParam;
            }

            if (! $task) {
                abort(404);
            }

            if ($role === 'advisor') {
                return $next($request);
            }

            // member
            if ($role === 'member') {
                if (in_array($state, ['working', 'submitted to review'])) {
                    // must be assignee
                    if ($task->user_id === Auth::id()) {
                        return $next($request);
                    }
                    abort(403, 'Forbidden: only assignee can change to this state.');
                }

                // other states not allowed for members
                abort(403, 'Forbidden: members cannot change to this state.');
            }
        }

        // Meeting logs: members may write meeting logs (create/update minutes) — advisors allowed too
        // No further restrictions here; advisors allowed except user management (already handled)

        return $next($request);
    }
}
