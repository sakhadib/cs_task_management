<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\MeetingLogController;
use App\Http\Controllers\MeetingAttendeeController;
use App\Http\Controllers\TeamWorkspaceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Password change routes (must be before password.changed middleware)
Route::get('/change-password', [\App\Http\Controllers\PasswordController::class, 'showChangeForm'])->name('password.change')->middleware('auth');
Route::post('/change-password', [\App\Http\Controllers\PasswordController::class, 'update'])->name('password.update')->middleware('auth');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth')->middleware('password.changed');

// User Management Routes
Route::get('/users', [UserController::class, 'index'])->name('users.index')->middleware('auth')->middleware('password.changed');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create')->middleware('auth')->middleware('password.changed');
Route::post('/users', [UserController::class, 'store'])->name('users.store')->middleware('auth')->middleware('password.changed');

// Edit / Update / Delete
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('auth')->middleware('password.changed');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update')->middleware('auth')->middleware('password.changed');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('auth')->middleware('password.changed');

// Panels
Route::get('/panels', [PanelController::class, 'index'])->name('panels.index')->middleware('auth')->middleware('password.changed');
Route::get('/panels/create', [PanelController::class, 'create'])->name('panels.create')->middleware('auth')->middleware('password.changed');
Route::post('/panels', [PanelController::class, 'store'])->name('panels.store')->middleware('auth')->middleware('password.changed');
Route::get('/panels/{panel}/edit', [PanelController::class, 'edit'])->name('panels.edit')->middleware('auth')->middleware('password.changed');
Route::put('/panels/{panel}', [PanelController::class, 'update'])->name('panels.update')->middleware('auth')->middleware('password.changed');
Route::delete('/panels/{panel}', [PanelController::class, 'destroy'])->name('panels.destroy')->middleware('auth')->middleware('password.changed');
Route::post('/panels/{panel}/make-current', [PanelController::class, 'makeCurrent'])->name('panels.makeCurrent')->middleware('auth')->middleware('password.changed');
Route::get('/panels/{panel}/positions', [PanelController::class, 'positions'])->name('panels.positions')->middleware('auth')->middleware('password.changed');
Route::post('/panels/{panel}/positions', [PanelController::class, 'storePosition'])->name('panels.positions.store')->middleware('auth')->middleware('password.changed');
Route::put('/positions/{position}', [\App\Http\Controllers\PositionController::class, 'update'])->name('positions.update')->middleware('auth')->middleware('password.changed');
Route::delete('/positions/{position}', [\App\Http\Controllers\PositionController::class, 'destroy'])->name('positions.destroy')->middleware('auth')->middleware('password.changed');

// Teams (only index needed for current panel view)
Route::get('/teams', [TeamController::class, 'index'])->name('teams.index')->middleware('auth')->middleware('password.changed');
Route::post('/teams', [TeamController::class, 'store'])->name('teams.store')->middleware('auth')->middleware('password.changed');
Route::get('/teams/{team}', [TeamController::class, 'show'])->name('teams.show')->middleware('auth')->middleware('password.changed');
Route::post('/teams/{team}/users/{user}/make-lead', [TeamController::class, 'makeLead'])->name('teams.makeLead')->middleware('auth')->middleware('password.changed');
Route::delete('/teams/{team}/users/{user}', [TeamController::class, 'removeUser'])->name('teams.removeUser')->middleware('auth')->middleware('password.changed');
Route::post('/teams/{team}/users', [TeamController::class, 'addUser'])->name('teams.addUser')->middleware('auth')->middleware('password.changed');
Route::put('/teams/{team}', [TeamController::class, 'update'])->name('teams.update')->middleware('auth')->middleware('password.changed');
Route::delete('/teams/{team}', [TeamController::class, 'destroy'])->name('teams.destroy')->middleware('auth')->middleware('password.changed');

// Tasks
Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index')->middleware('auth')->middleware('password.changed');
Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create')->middleware('auth')->middleware('password.changed');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store')->middleware('auth')->middleware('password.changed');
Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show')->middleware('auth')->middleware('password.changed');
Route::post('/tasks/{task}/state', [TaskController::class, 'changeState'])->name('tasks.changeState')->middleware('auth')->middleware('password.changed');
Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update')->middleware('auth')->middleware('password.changed');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy')->middleware('auth')->middleware('password.changed');
Route::post('/tasks/{task}/assign', [TaskController::class, 'assign'])->name('tasks.assign')->middleware('auth')->middleware('password.changed');

// Team Workspace (for team leads)
Route::get('/team-workspace', [TeamWorkspaceController::class, 'index'])->name('team_workspace.index')->middleware('auth')->middleware('password.changed');
Route::get('/team-workspace/team/{team}/users', [TeamWorkspaceController::class, 'teamMembers'])->middleware('auth')->middleware('password.changed');
Route::post('/team-workspace/{task}/assign', [TeamWorkspaceController::class, 'assign'])->name('team_workspace.assign')->middleware('auth')->middleware('password.changed');

// Meeting logs
Route::get('/meeting-logs', [MeetingLogController::class, 'index'])->name('meeting_logs.index')->middleware('auth')->middleware('password.changed');
Route::get('/meeting-logs/{log}', [MeetingLogController::class, 'show'])->name('meeting_logs.show')->middleware('auth')->middleware('password.changed');
Route::post('/meeting-logs', [MeetingLogController::class, 'store'])->name('meeting_logs.store')->middleware('auth')->middleware('password.changed');
Route::patch('/meeting-logs/{log}/minutes', [MeetingLogController::class, 'updateMinutes'])->name('meeting_logs.update_minutes')->middleware('auth')->middleware('password.changed');
Route::put('/meeting-logs/{log}', [MeetingLogController::class, 'update'])->name('meeting_logs.update')->middleware('auth')->middleware('password.changed');
Route::delete('/meeting-logs/{log}', [MeetingLogController::class, 'destroy'])->name('meeting_logs.destroy')->middleware('auth')->middleware('password.changed');

// Meeting attendees
Route::get('/meeting-logs/{log}/attendees', [MeetingAttendeeController::class, 'index'])->name('meeting_logs.attendees.index')->middleware('auth')->middleware('password.changed');
Route::post('/meeting-logs/{log}/attendees', [MeetingAttendeeController::class, 'store'])->name('meeting_logs.attendees.store')->middleware('auth')->middleware('password.changed');
Route::delete('/meeting-logs/{log}/attendees/{attendee}', [MeetingAttendeeController::class, 'destroy'])->name('meeting_logs.attendees.destroy')->middleware('auth')->middleware('password.changed');
