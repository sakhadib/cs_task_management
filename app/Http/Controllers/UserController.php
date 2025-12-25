<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Display a listing of the users
    public function index()
    {
        $users = User::all();
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
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }
}