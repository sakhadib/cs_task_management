<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\TeamController;

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
Route::get('/dashboard', [LoginController::class, 'dashboard'])->name('dashboard');

// User Management Routes
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');

// Edit / Update / Delete
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

// Panels
Route::get('/panels', [PanelController::class, 'index'])->name('panels.index');
Route::get('/panels/create', [PanelController::class, 'create'])->name('panels.create');
Route::post('/panels', [PanelController::class, 'store'])->name('panels.store');
Route::get('/panels/{panel}/edit', [PanelController::class, 'edit'])->name('panels.edit');
Route::put('/panels/{panel}', [PanelController::class, 'update'])->name('panels.update');
Route::delete('/panels/{panel}', [PanelController::class, 'destroy'])->name('panels.destroy');
Route::post('/panels/{panel}/make-current', [PanelController::class, 'makeCurrent'])->name('panels.makeCurrent');
Route::get('/panels/{panel}/positions', [PanelController::class, 'positions'])->name('panels.positions');
Route::post('/panels/{panel}/positions', [PanelController::class, 'storePosition'])->name('panels.positions.store');
Route::put('/positions/{position}', [\App\Http\Controllers\PositionController::class, 'update'])->name('positions.update');
Route::delete('/positions/{position}', [\App\Http\Controllers\PositionController::class, 'destroy'])->name('positions.destroy');

// Teams (only index needed for current panel view)
Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
Route::get('/teams/{team}', [TeamController::class, 'show'])->name('teams.show');
Route::post('/teams/{team}/users/{user}/make-lead', [TeamController::class, 'makeLead'])->name('teams.makeLead');
Route::delete('/teams/{team}/users/{user}', [TeamController::class, 'removeUser'])->name('teams.removeUser');
Route::post('/teams/{team}/users', [TeamController::class, 'addUser'])->name('teams.addUser');
Route::put('/teams/{team}', [TeamController::class, 'update'])->name('teams.update');
Route::delete('/teams/{team}', [TeamController::class, 'destroy'])->name('teams.destroy');
