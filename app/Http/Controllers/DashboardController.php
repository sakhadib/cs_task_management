<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\MeetingLog;
use App\Models\Panel;
use App\Models\Team;
use App\Models\User;
use App\Models\Position;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role;

        // Member dashboard
        if ($role === 'member') {
            return $this->memberDashboard();
        }

        // Admin/Advisor dashboard
        return $this->adminAdvisorDashboard();
    }

    private function memberDashboard()
    {
        $assignedTasks = Task::with('team')
            ->where('user_id', Auth::id())
            ->where('state', '!=', 'completed')
            ->orderBy('created_at', 'asc')
            ->take(6)
            ->get();

        $currentPanel = Panel::where('is_current', true)->first();
        if ($currentPanel) {
            $upcomingMeetings = MeetingLog::where('panel_id', $currentPanel->id)
                ->where('scheduled_at', '>=', Carbon::now())
                ->orderBy('scheduled_at')
                ->get();
        } else {
            $upcomingMeetings = collect();
        }

        return view('dashboard', compact('assignedTasks', 'upcomingMeetings'));
    }

    private function adminAdvisorDashboard()
    {
        $currentPanel = Panel::where('is_current', true)->first();

        // Task statistics
        $taskStats = [
            'total' => Task::count(),
            'pending' => Task::whereIn('state', ['pending assignment', 'team assigned'])->count(),
            'assigned' => Task::whereIn('state', ['assigned to user', 'reassigned to user'])->count(),
            'working' => Task::where('state', 'working')->count(),
            'review' => Task::where('state', 'submitted to review')->count(),
            'completed' => Task::where('state', 'completed')->count(),
        ];

        // Recent tasks (last 5)
        $recentTasks = Task::with(['team', 'user', 'creator'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Tasks needing attention (submitted to review)
        $tasksInReview = Task::with(['team', 'user'])
            ->where('state', 'submitted to review')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        // Team statistics
        $teamStats = [
            'total' => Team::count(),
            'with_tasks' => Team::has('tasks')->count(),
        ];
        
        $teams = Team::withCount(['users', 'tasks'])->get();

        // User statistics
        $userStats = [
            'total' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'advisors' => User::where('role', 'advisor')->count(),
            'members' => User::where('role', 'member')->count(),
        ];

        // Meeting statistics
        if ($currentPanel) {
            $upcomingMeetings = MeetingLog::where('panel_id', $currentPanel->id)
                ->where('scheduled_at', '>=', Carbon::now())
                ->orderBy('scheduled_at')
                ->take(5)
                ->get();

            $recentMeetings = MeetingLog::where('panel_id', $currentPanel->id)
                ->where('scheduled_at', '<', Carbon::now())
                ->orderBy('scheduled_at', 'desc')
                ->take(3)
                ->get();
        } else {
            $upcomingMeetings = collect();
            $recentMeetings = collect();
        }

        // Panel positions (if current panel exists)
        $panelPositions = $currentPanel 
            ? Position::with('user')->where('panel_id', $currentPanel->id)->orderBy('level')->get()
            : collect();

        return view('dashboard_admin', compact(
            'currentPanel',
            'taskStats',
            'recentTasks',
            'tasksInReview',
            'teamStats',
            'teams',
            'userStats',
            'upcomingMeetings',
            'recentMeetings',
            'panelPositions'
        ));
    }
}
