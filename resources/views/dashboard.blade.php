@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Welcome Header -->
        <div class="mb-8 animate-fade-in">
            <h1 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                Welcome back, {{ auth()->user()->name }}! 👋
            </h1>
            <p class="mt-3 text-lg text-gray-600">Here's what's happening with your tasks and meetings</p>
        </div>

        <!-- Quick Stats Cards -->
        @if(auth()->check() && auth()->user()->role === 'member')
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Tasks Card -->
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Tasks</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $assignedTasks->count() ?? 0 }}</p>
                    </div>
                    <div class="bg-indigo-100 rounded-full p-4">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Upcoming Meetings Card -->
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Upcoming Meetings</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $upcomingMeetings->count() ?? 0 }}</p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-4">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Status Card -->
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 text-white hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-indigo-100 uppercase tracking-wider">Status</p>
                        <p class="text-3xl font-bold mt-2">Active</p>
                    </div>
                    <div class="bg-white/20 rounded-full p-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Tasks Section -->
            @if(auth()->check() && auth()->user()->role === 'member')
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="bg-white/20 rounded-lg p-2">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-white">My Tasks</h2>
                        </div>
                        <span class="bg-white/20 text-white px-3 py-1 rounded-full text-sm font-semibold">{{ $assignedTasks->count() ?? 0 }}</span>
                    </div>
                </div>

                <div class="p-6">
                    @if(empty($assignedTasks) || $assignedTasks->isEmpty())
                        <div class="text-center py-12">
                            <div class="inline-block bg-gray-100 rounded-full p-6 mb-4">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 text-lg font-medium">No tasks assigned yet</p>
                            <p class="text-gray-400 text-sm mt-2">You're all caught up! 🎉</p>
                        </div>
                    @else
                        <div class="space-y-4 max-h-96 overflow-y-auto pr-2 custom-scrollbar">
                            @foreach($assignedTasks as $task)
                                <div class="group bg-gradient-to-r from-gray-50 to-white hover:from-indigo-50 hover:to-purple-50 rounded-xl p-4 border border-gray-200 hover:border-indigo-300 transition-all duration-300 hover:shadow-md">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <a href="{{ route('tasks.show', $task) }}" class="font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors duration-200 text-lg">
                                                {{ $task->title }}
                                            </a>
                                            <div class="flex flex-wrap items-center gap-2 mt-2">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                                                    </svg>
                                                    {{ optional($task->team)->name ?? 'No team' }}
                                                </span>
                                                @php
                                                    $stateColors = [
                                                        'pending assignment' => 'bg-yellow-100 text-yellow-700',
                                                        'team assigned' => 'bg-blue-100 text-blue-700',
                                                        'assigned to user' => 'bg-indigo-100 text-indigo-700',
                                                        'reassigned to user' => 'bg-purple-100 text-purple-700',
                                                        'working' => 'bg-orange-100 text-orange-700',
                                                        'submitted to review' => 'bg-teal-100 text-teal-700',
                                                        'completed' => 'bg-green-100 text-green-700'
                                                    ];
                                                    $colorClass = $stateColors[$task->state] ?? 'bg-gray-100 text-gray-700';
                                                @endphp
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $colorClass }}">
                                                    <span class="w-2 h-2 rounded-full bg-current mr-1.5"></span>
                                                    {{ ucfirst($task->state) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4 flex-shrink-0">
                                            <div class="text-xs text-gray-500 text-right">{{ $task->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <a href="{{ route('tasks.index') }}" class="flex items-center justify-center px-4 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all duration-300 font-semibold shadow-md hover:shadow-lg group">
                                <span>View All Tasks</span>
                                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            @endif
            
            <!-- Meetings Section -->
            @if(auth()->check())
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="bg-white/20 rounded-lg p-2">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-white">Upcoming Meetings</h2>
                        </div>
                        <span class="bg-white/20 text-white px-3 py-1 rounded-full text-sm font-semibold">{{ $upcomingMeetings->count() ?? 0 }}</span>
                    </div>
                </div>

                <div class="p-6">
                    @if(empty($upcomingMeetings) || $upcomingMeetings->isEmpty())
                        <div class="text-center py-12">
                            <div class="inline-block bg-gray-100 rounded-full p-6 mb-4">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 text-lg font-medium">No upcoming meetings</p>
                            <p class="text-gray-400 text-sm mt-2">Your calendar is clear 📅</p>
                        </div>
                    @else
                        <div class="space-y-4 max-h-96 overflow-y-auto pr-2 custom-scrollbar">
                            @foreach($upcomingMeetings as $meeting)
                                <div class="group bg-gradient-to-r from-gray-50 to-white hover:from-purple-50 hover:to-pink-50 rounded-xl p-4 border border-gray-200 hover:border-purple-300 transition-all duration-300 hover:shadow-md">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <a href="{{ route('meeting_logs.show', $meeting) }}" class="font-semibold text-gray-900 group-hover:text-purple-600 transition-colors duration-200 text-lg flex items-center">
                                                @if($meeting->type === 'online')
                                                    <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                @endif
                                                {{ ucfirst($meeting->type) }} Meeting
                                            </a>
                                            <div class="flex flex-wrap items-center gap-2 mt-2">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    {{ \Carbon\Carbon::parse($meeting->scheduled_at)->format('M d, Y g:ia') }}
                                                </span>
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                                    @if($meeting->type === 'online')
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Online
                                                    @else
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        {{ $meeting->location ?? 'TBD' }}
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4 flex-shrink-0">
                                            <div class="text-xs text-gray-500 text-right">{{ \Carbon\Carbon::parse($meeting->scheduled_at)->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <a href="{{ route('meeting_logs.index') }}" class="flex items-center justify-center px-4 py-3 bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-xl hover:from-purple-600 hover:to-pink-700 transition-all duration-300 font-semibold shadow-md hover:shadow-lg group">
                                <span>View All Meetings</span>
                                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            @endif
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
        background: linear-gradient(to bottom, #6366f1, #a855f7);
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #4f46e5, #9333ea);
    }
</style>
@endsection