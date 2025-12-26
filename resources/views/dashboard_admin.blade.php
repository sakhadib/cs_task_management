@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8 animate-fade-in">
            <h1 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                Dashboard Overview
            </h1>
            <p class="mt-3 text-lg text-gray-600">Welcome back, {{ auth()->user()->name }} 👋</p>
            @if($currentPanel)
                <div class="mt-2 inline-flex items-center px-4 py-2 bg-blue-100 rounded-full">
                    <svg class="w-4 h-4 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"></path>
                    </svg>
                    <span class="text-sm font-semibold text-blue-700">Current Panel: {{ $currentPanel->name }}</span>
                </div>
            @else
                <div class="mt-2 inline-flex items-center px-4 py-2 bg-red-100 rounded-full">
                    <svg class="w-4 h-4 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm font-semibold text-red-700">No current panel set</span>
                </div>
            @endif
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Tasks -->
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Tasks</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $taskStats['total'] }}</p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 text-sm text-green-600 font-medium">
                    ✓ {{ $taskStats['completed'] }} completed
                </div>
            </div>

            <!-- Tasks in Review -->
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Needs Review</p>
                        <p class="text-3xl font-bold text-orange-600 mt-2">{{ $taskStats['review'] }}</p>
                    </div>
                    <div class="bg-orange-100 rounded-full p-4">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 text-sm text-gray-600">
                    Requires attention
                </div>
            </div>

            <!-- Total Teams -->
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Active Teams</p>
                        <p class="text-3xl font-bold text-purple-600 mt-2">{{ $teamStats['total'] }}</p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-4">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 text-sm text-gray-600">
                    {{ $teamStats['with_tasks'] }} with tasks
                </div>
            </div>

            <!-- Total Users -->
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 text-white hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-100 uppercase tracking-wider">Total Members</p>
                        <p class="text-3xl font-bold mt-2">{{ $userStats['total'] }}</p>
                    </div>
                    <div class="bg-white/20 rounded-full p-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 text-sm text-blue-100">
                    {{ $userStats['members'] }} members · {{ $userStats['advisors'] }} advisors
                </div>
            </div>
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column (2/3 width) -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Task Status Breakdown -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-2xl font-bold text-gray-900">Task Status Overview</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-5">
                            <div class="flex items-center gap-4">
                                <div class="w-32 text-sm font-medium text-gray-700">Pending</div>
                                <div class="flex-1 bg-gray-200 rounded-full h-8 relative overflow-hidden">
                                    <div class="bg-gradient-to-r from-gray-400 to-gray-500 h-8 rounded-full transition-all duration-500" style="width: {{ $taskStats['total'] > 0 ? ($taskStats['pending'] / $taskStats['total'] * 100) : 0 }}%"></div>
                                    <span class="absolute inset-0 flex items-center justify-center text-sm font-bold text-gray-800">{{ $taskStats['pending'] }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-32 text-sm font-medium text-gray-700">Assigned</div>
                                <div class="flex-1 bg-gray-200 rounded-full h-8 relative overflow-hidden">
                                    <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-8 rounded-full transition-all duration-500" style="width: {{ $taskStats['total'] > 0 ? ($taskStats['assigned'] / $taskStats['total'] * 100) : 0 }}%"></div>
                                    <span class="absolute inset-0 flex items-center justify-center text-sm font-bold text-white">{{ $taskStats['assigned'] }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-32 text-sm font-medium text-gray-700">Working</div>
                                <div class="flex-1 bg-gray-200 rounded-full h-8 relative overflow-hidden">
                                    <div class="bg-gradient-to-r from-yellow-400 to-yellow-600 h-8 rounded-full transition-all duration-500" style="width: {{ $taskStats['total'] > 0 ? ($taskStats['working'] / $taskStats['total'] * 100) : 0 }}%"></div>
                                    <span class="absolute inset-0 flex items-center justify-center text-sm font-bold text-gray-800">{{ $taskStats['working'] }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-32 text-sm font-medium text-gray-700">In Review</div>
                                <div class="flex-1 bg-gray-200 rounded-full h-8 relative overflow-hidden">
                                    <div class="bg-gradient-to-r from-orange-400 to-orange-600 h-8 rounded-full transition-all duration-500" style="width: {{ $taskStats['total'] > 0 ? ($taskStats['review'] / $taskStats['total'] * 100) : 0 }}%"></div>
                                    <span class="absolute inset-0 flex items-center justify-center text-sm font-bold text-white">{{ $taskStats['review'] }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-32 text-sm font-medium text-gray-700">Completed</div>
                                <div class="flex-1 bg-gray-200 rounded-full h-8 relative overflow-hidden">
                                    <div class="bg-gradient-to-r from-green-400 to-green-600 h-8 rounded-full transition-all duration-500" style="width: {{ $taskStats['total'] > 0 ? ($taskStats['completed'] / $taskStats['total'] * 100) : 0 }}%"></div>
                                    <span class="absolute inset-0 flex items-center justify-center text-sm font-bold text-white">{{ $taskStats['completed'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tasks Needing Review -->
                @if($tasksInReview->isNotEmpty())
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-5">
                        <div class="flex items-center justify-between">
                            <h2 class="text-2xl font-bold text-white">Tasks Needing Review</h2>
                            <span class="bg-white/20 text-white px-4 py-1.5 rounded-full text-sm font-bold">{{ $tasksInReview->count() }}</span>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @foreach($tasksInReview as $task)
                        <div class="p-5 hover:bg-orange-50 transition-colors duration-200 group">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <a href="{{ route('tasks.show', $task) }}" class="text-gray-900 font-semibold text-lg group-hover:text-orange-600 transition-colors">{{ $task->title }}</a>
                                    <div class="flex items-center gap-3 mt-2">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                                            </svg>
                                            {{ optional($task->team)->name }}
                                        </span>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ optional($task->user)->name }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-500 ml-4">{{ $task->updated_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="p-5 border-t border-gray-200 bg-gray-50">
                        <a href="{{ route('tasks.index') }}?state=submitted+to+review" class="flex items-center justify-center text-orange-600 hover:text-orange-700 font-semibold group">
                            <span>View all tasks in review</span>
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                @endif

                <!-- Recent Tasks -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-5">
                        <h2 class="text-2xl font-bold text-white">Recent Tasks</h2>
                    </div>
                    <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto custom-scrollbar">
                        @forelse($recentTasks as $task)
                        <div class="p-5 hover:bg-blue-50 transition-colors duration-200 group">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <a href="{{ route('tasks.show', $task) }}" class="text-gray-900 font-semibold text-lg group-hover:text-blue-600 transition-colors">{{ $task->title }}</a>
                                    <div class="flex flex-wrap items-center gap-2 mt-2">
                                        @php
                                            $stateColors = [
                                                'completed' => 'bg-green-100 text-green-700',
                                                'submitted to review' => 'bg-orange-100 text-orange-700',
                                                'working' => 'bg-yellow-100 text-yellow-700',
                                            ];
                                            $colorClass = $stateColors[$task->state] ?? 'bg-gray-100 text-gray-700';
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $colorClass }}">
                                            <span class="w-2 h-2 rounded-full bg-current mr-1.5"></span>
                                            {{ ucfirst($task->state) }}
                                        </span>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                            {{ optional($task->team)->name }}
                                        </span>
                                        @if($task->user)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                                {{ $task->user->name }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-sm text-gray-500 ml-4">{{ $task->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        @empty
                        <div class="p-12 text-center">
                            <div class="inline-block bg-gray-100 rounded-full p-6 mb-4">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 font-medium">No tasks yet</p>
                        </div>
                        @endforelse
                    </div>
                    <div class="p-5 border-t border-gray-200 bg-gray-50">
                        <a href="{{ route('tasks.index') }}" class="flex items-center justify-center text-blue-600 hover:text-blue-700 font-semibold group">
                            <span>View all tasks</span>
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Column (1/3 width) -->
            <div class="space-y-8">
                <!-- Upcoming Meetings -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-5">
                        <h2 class="text-xl font-bold text-white">Upcoming Meetings</h2>
                    </div>
                    <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto custom-scrollbar">
                        @forelse($upcomingMeetings as $meeting)
                        <div class="p-5 hover:bg-purple-50 transition-colors duration-200 group">
                            <a href="{{ route('meeting_logs.show', $meeting) }}" class="block">
                                <div class="flex items-start gap-3">
                                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-purple-200 transition-colors">
                                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900 group-hover:text-purple-600 transition-colors">{{ ucfirst($meeting->type) }} Meeting</p>
                                        <p class="text-sm text-gray-600 mt-1">{{ \Carbon\Carbon::parse($meeting->scheduled_at)->format('M d, g:ia') }}</p>
                                        @if($meeting->location)
                                            <p class="text-xs text-gray-500 mt-1 flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $meeting->location }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                        @empty
                        <div class="p-12 text-center">
                            <div class="inline-block bg-gray-100 rounded-full p-6 mb-4">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 font-medium text-sm">No upcoming meetings</p>
                        </div>
                        @endforelse
                    </div>
                    <div class="p-5 border-t border-gray-200 bg-gray-50">
                        <a href="{{ route('meeting_logs.index') }}" class="flex items-center justify-center text-purple-600 hover:text-purple-700 font-semibold group">
                            <span>View all meetings</span>
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Teams Overview -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-5">
                        <h2 class="text-xl font-bold text-white">Active Teams</h2>
                    </div>
                    <div class="divide-y divide-gray-200 max-h-80 overflow-y-auto custom-scrollbar">
                        @forelse($teams as $team)
                        <div class="p-5 hover:bg-indigo-50 transition-colors duration-200 group">
                            <a href="{{ route('teams.show', $team) }}" class="block">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $team->name }}</p>
                                        <div class="flex items-center gap-3 mt-2">
                                            <span class="inline-flex items-center text-xs text-gray-600">
                                                <svg class="w-4 h-4 mr-1 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                                                </svg>
                                                {{ $team->users_count }} members
                                            </span>
                                            <span class="inline-flex items-center text-xs text-gray-600">
                                                <svg class="w-4 h-4 mr-1 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                </svg>
                                                {{ $team->tasks_count }} tasks
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @empty
                        <div class="p-12 text-center">
                            <div class="inline-block bg-gray-100 rounded-full p-6 mb-4">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 font-medium text-sm">No teams yet</p>
                        </div>
                        @endforelse
                    </div>
                    <div class="p-5 border-t border-gray-200 bg-gray-50">
                        <a href="{{ route('teams.index') }}" class="flex items-center justify-center text-indigo-600 hover:text-indigo-700 font-semibold group">
                            <span>View all teams</span>
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Panel Positions -->
                @if($currentPanel && $panelPositions->isNotEmpty())
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-5">
                        <h2 class="text-xl font-bold text-white">Panel Positions</h2>
                    </div>
                    <div class="divide-y divide-gray-200 max-h-80 overflow-y-auto custom-scrollbar">
                        @foreach($panelPositions as $position)
                        <div class="p-5 hover:bg-emerald-50 transition-colors duration-200">
                            <p class="font-semibold text-gray-900">{{ $position->position }}</p>
                            @if($position->user)
                                <p class="text-sm text-gray-600 mt-1 flex items-center">
                                    <svg class="w-4 h-4 mr-1.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $position->user->name }}
                                </p>
                            @else
                                <p class="text-sm text-gray-400 mt-1 italic flex items-center">
                                    <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                    </svg>
                                    Vacant
                                </p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    <div class="p-5 border-t border-gray-200 bg-gray-50">
                        <a href="{{ url('panels/'.$currentPanel->id.'/positions') }}" class="flex items-center justify-center text-emerald-600 hover:text-emerald-700 font-semibold group">
                            <span>Manage positions</span>
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fade-in 0.5s ease-out;
    }
    
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #6366f1, #8b5cf6);
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #4f46e5, #7c3aed);
    }
</style>
@endsection
