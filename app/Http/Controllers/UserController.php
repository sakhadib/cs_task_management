<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Display a listing of the users
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = User::query();
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $users = $query->orderBy('name')->get();

        // If AJAX/JSON requested, return JSON for frontend filtering
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($users->map(function ($u) {
                return [
                    'id' => $u->id,
                    'student_id' => $u->student_id,
                    'name' => $u->name,
                    'email' => $u->email,
                    'role' => $u->role,
                ];
            }));
        }

        return view('users.index', compact('users'));
    }

    // Show the form for creating a new user
    public function create()
    {
        return view('users.create');
    }

    // Store a newly created user in storage
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'student_id' => 'required|string|unique:users,student_id',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,advisor,allumni,member,guest',
        ]);

        // Assign default password for new users
        $validated['password'] = Hash::make('password');

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User created successfully. Default password: "password"');
    }

    // Show the form for editing the specified user
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // Update the specified user in storage
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'student_id' => 'required|string|unique:users,student_id,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,advisor,allumni,member,guest',
        ]);

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    // Remove the specified user from storage
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}