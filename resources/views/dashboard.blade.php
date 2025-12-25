@extends('layouts.app')

@section('content')
<div class="p-6 bg-white rounded-lg shadow-md">
    <h1 class="text-2xl font-bold text-gray-800">Welcome to the Dashboard</h1>
    <p class="mt-4 text-gray-600">This is a dummy dashboard page. Customize it as needed!</p>

    <div class="mt-6">
        <h2 class="text-xl font-semibold text-gray-700">Quick Links</h2>
        <ul class="mt-4 space-y-2">
            <li><a href="#" class="text-blue-500 hover:underline">Manage Users</a></li>
            <li><a href="#" class="text-blue-500 hover:underline">View Tasks</a></li>
            <li><a href="#" class="text-blue-500 hover:underline">Settings</a></li>
        </ul>
    </div>
</div>
@endsection