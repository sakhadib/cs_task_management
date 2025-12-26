<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\MeetingLogController;
use App\Http\Controllers\MeetingAttendeeController;

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
Route::get('/dashboard', [LoginController::class, 'dashboard'])->name('dashboard')->middleware('auth')->middleware('auth');

// User Management Routes
Route::get('/users', [UserController::class, 'index'])->name('users.index')->middleware('auth')->middleware('auth');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create')->middleware('auth')->middleware('auth');
Route::post('/users', [UserController::class, 'store'])->name('users.store')->middleware('auth')->middleware('auth');

// Edit / Update / Delete
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('auth')->middleware('auth');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update')->middleware('auth')->middleware('auth');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('auth')->middleware('auth');

// Panels
Route::get('/panels', [PanelController::class, 'index'])->name('panels.index')->middleware('auth');
Route::get('/panels/create', [PanelController::class, 'create'])->name('panels.create')->middleware('auth');
Route::post('/panels', [PanelController::class, 'store'])->name('panels.store')->middleware('auth');
Route::get('/panels/{panel}/edit', [PanelController::class, 'edit'])->name('panels.edit')->middleware('auth');
Route::put('/panels/{panel}', [PanelController::class, 'update'])->name('panels.update')->middleware('auth');
Route::delete('/panels/{panel}', [PanelController::class, 'destroy'])->name('panels.destroy')->middleware('auth');
Route::post('/panels/{panel}/make-current', [PanelController::class, 'makeCurrent'])->name('panels.makeCurrent')->middleware('auth');
Route::get('/panels/{panel}/positions', [PanelController::class, 'positions'])->name('panels.positions')->middleware('auth');
Route::post('/panels/{panel}/positions', [PanelController::class, 'storePosition'])->name('panels.positions.store')->middleware('auth');
Route::put('/positions/{position}', [\App\Http\Controllers\PositionController::class, 'update'])->name('positions.update')->middleware('auth')->middleware('auth');
Route::delete('/positions/{position}', [\App\Http\Controllers\PositionController::class, 'destroy'])->name('positions.destroy')->middleware('auth');

// Teams (only index needed for current panel view)
Route::get('/teams', [TeamController::class, 'index'])->name('teams.index')->middleware('auth');
Route::post('/teams', [TeamController::class, 'store'])->name('teams.store')->middleware('auth');
Route::get('/teams/{team}', [TeamController::class, 'show'])->name('teams.show')->middleware('auth');
Route::post('/teams/{team}/users/{user}/make-lead', [TeamController::class, 'makeLead'])->name('teams.makeLead')->middleware('auth');
Route::delete('/teams/{team}/users/{user}', [TeamController::class, 'removeUser'])->name('teams.removeUser')->middleware('auth');
Route::post('/teams/{team}/users', [TeamController::class, 'addUser'])->name('teams.addUser')->middleware('auth');
Route::put('/teams/{team}', [TeamController::class, 'update'])->name('teams.update')->middleware('auth');
Route::delete('/teams/{team}', [TeamController::class, 'destroy'])->name('teams.destroy')->middleware('auth');

// Tasks
Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index')->middleware('auth');
Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create')->middleware('auth');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store')->middleware('auth');
Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show')->middleware('auth');
Route::post('/tasks/{task}/state', [TaskController::class, 'changeState'])->name('tasks.changeState')->middleware('auth');
Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update')->middleware('auth');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy')->middleware('auth');
Route::post('/tasks/{task}/assign', [TaskController::class, 'assign'])->name('tasks.assign')->middleware('auth');

// Meeting logs
Route::get('/meeting-logs', [MeetingLogController::class, 'index'])->name('meeting_logs.index')->middleware('auth');
Route::get('/meeting-logs/{log}', [MeetingLogController::class, 'show'])->name('meeting_logs.show')->middleware('auth');
Route::post('/meeting-logs', [MeetingLogController::class, 'store'])->name('meeting_logs.store')->middleware('auth');
Route::patch('/meeting-logs/{log}/minutes', [MeetingLogController::class, 'updateMinutes'])->name('meeting_logs.update_minutes')->middleware('auth');
Route::put('/meeting-logs/{log}', [MeetingLogController::class, 'update'])->name('meeting_logs.update')->middleware('auth');
Route::delete('/meeting-logs/{log}', [MeetingLogController::class, 'destroy'])->name('meeting_logs.destroy')->middleware('auth');

// Meeting attendees
Route::get('/meeting-logs/{log}/attendees', [MeetingAttendeeController::class, 'index'])->name('meeting_logs.attendees.index')->middleware('auth');
Route::post('/meeting-logs/{log}/attendees', [MeetingAttendeeController::class, 'store'])->name('meeting_logs.attendees.store')->middleware('auth');
Route::delete('/meeting-logs/{log}/attendees/{attendee}', [MeetingAttendeeController::class, 'destroy'])->name('meeting_logs.attendees.destroy')->middleware('auth');
