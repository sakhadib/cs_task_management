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
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
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

        // Generate random 6-character password
        $generatedPassword = $this->generatePassword(6);
        
        // Hash password and mark as not changed
        $validated['password'] = Hash::make($generatedPassword);
        $validated['is_password_changed'] = false;

        $user = User::create($validated);

        // Send welcome email with credentials
        try {
            \Mail::to($user->email)->send(new \App\Mail\WelcomeUser($user, $generatedPassword));
        } catch (\Exception $e) {
            \Log::error('Failed to send welcome email: ' . $e->getMessage());
        }

        return redirect()->route('users.index')->with('success', "User created successfully! Login credentials - Email: {$user->email}, Password: {$generatedPassword}");
    }

    /**
     * Generate a random password
     */
    private function generatePassword($length = 6)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password = '';
        $charactersLength = strlen($characters);
        
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, $charactersLength - 1)];
        }
        
        return $password;
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