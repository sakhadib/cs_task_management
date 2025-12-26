@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-indigo-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8 animate-fade-in">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <a href="{{ route('teams.index') }}" class="inline-flex items-center text-purple-600 hover:text-purple-700 transition-colors">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Teams
                        </a>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                        {{ $team->name }}
                    </h1>
                    <p class="mt-3 text-lg text-gray-600">Manage team members and assign roles</p>
                    @if($team->description)
                        <p class="mt-2 text-sm text-gray-500 italic">{{ $team->description }}</p>
                    @endif
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="openAssignMemberModal()" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl group">
                        <svg class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Assign Member
                    </button>
                </div>
            </div>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-6 rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 p-4 border-l-4 border-green-500 shadow-lg animate-fade-in">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Error Message --}}
        @if(session('error'))
            <div class="mb-6 rounded-xl bg-gradient-to-r from-red-50 to-red-100 p-4 border-l-4 border-red-500 shadow-lg animate-fade-in">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-semibold text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="mb-6 rounded-xl bg-gradient-to-r from-red-50 to-red-100 p-4 border-l-4 border-red-500 shadow-lg animate-fade-in">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-semibold text-red-800 mb-2">Please fix the following errors:</h3>
                        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Team Members Section -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="px-6 py-5 bg-gray-800">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-xl font-bold text-white">Team Members</h2>
                            <p class="text-gray-300 text-sm">{{ count($users) }} {{ Str::plural('member', count($users)) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($users as $user)
                            @php $isLead = isset($user->pivot) && $user->pivot->is_team_lead; @endphp
                            <tr class="hover:bg-gray-50 transition-colors {{ $isLead ? 'bg-gray-50' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 {{ $isLead ? 'bg-gray-700' : 'bg-gray-200' }} rounded-full flex items-center justify-center">
                                                @if($isLead)
                                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="flex items-center">
                                                <span class="font-semibold text-gray-900">{{ $user->name }}</span>
                                                @if($isLead)
                                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-700 text-white">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                        </svg>
                                                        Team Lead
                                                    </span>
                                                @endif
                                            </div>
                                            @if($user->student_id)
                                                <div class="text-sm text-gray-500">ID: {{ $user->student_id }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                                <td class="px-6 py-4 text-sm text-right">
                                    <div class="inline-flex items-center justify-end space-x-2">
                                        @if(!$isLead)
                                        <form method="POST" action="{{ route('teams.makeLead', ['team' => $team->id, 'user' => $user->id]) }}">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-semibold text-sm">
                                                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                                Make Lead
                                            </button>
                                        </form>
                                        @endif

                                        <button type="button" onclick="openRemoveMemberModal({{ $team->id }}, {{ $user->id }}, '{{ addslashes($user->name) }}')" class="inline-flex items-center px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-semibold text-sm">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Remove
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if(count($users) == 0)
                <div class="text-center py-16">
                    <div class="inline-block bg-gradient-to-br from-gray-100 to-gray-200 rounded-full p-8 mb-4">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500 text-lg font-medium">No members yet</p>
                    <p class="text-gray-400 text-sm mt-2">Add team members to get started</p>
                    <button onclick="openAssignMemberModal()" class="mt-4 inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 font-semibold shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add First Member
                    </button>
                </div>
            @endif
        </div>

        <!-- Team Tasks Section -->
        <div class="mt-8 bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="px-6 py-5 bg-gray-800">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-xl font-bold text-white">Team Tasks</h2>
                            <p class="text-gray-300 text-sm">Manage and track team assignments</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- State Filter Tabs -->
            <div class="border-b border-gray-200 bg-gray-50">
                <div class="flex overflow-x-auto px-6">
                    <button onclick="filterTasks('all')" class="task-filter-btn px-4 py-3 text-sm font-semibold border-b-2 border-gray-800 text-gray-800 whitespace-nowrap transition-colors" data-state="all">
                        All
                        <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold bg-gray-200 text-gray-700 rounded-full" id="count-all">0</span>
                    </button>
                    <button onclick="filterTasks('pending assignment')" class="task-filter-btn px-4 py-3 text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap transition-colors" data-state="pending assignment">
                        Pending
                        <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold bg-gray-200 text-gray-700 rounded-full" id="count-pending assignment">0</span>
                    </button>
                    <button onclick="filterTasks('team assigned')" class="task-filter-btn px-4 py-3 text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap transition-colors" data-state="team assigned">
                        Team
                        <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold bg-gray-200 text-gray-700 rounded-full" id="count-team assigned">0</span>
                    </button>
                    <button onclick="filterTasks('assigned to user')" class="task-filter-btn px-4 py-3 text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap transition-colors" data-state="assigned to user">
                        Assigned
                        <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold bg-gray-200 text-gray-700 rounded-full" id="count-assigned to user">0</span>
                    </button>
                    <button onclick="filterTasks('reassigned to user')" class="task-filter-btn px-4 py-3 text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap transition-colors" data-state="reassigned to user">
                        Reassigned
                        <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold bg-gray-200 text-gray-700 rounded-full" id="count-reassigned to user">0</span>
                    </button>
                    <button onclick="filterTasks('working')" class="task-filter-btn px-4 py-3 text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap transition-colors" data-state="working">
                        In Progress
                        <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold bg-gray-200 text-gray-700 rounded-full" id="count-working">0</span>
                    </button>
                    <button onclick="filterTasks('submitted to review')" class="task-filter-btn px-4 py-3 text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap transition-colors" data-state="submitted to review">
                        Review
                        <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold bg-gray-200 text-gray-700 rounded-full" id="count-submitted to review">0</span>
                    </button>
                    <button onclick="filterTasks('completed')" class="task-filter-btn px-4 py-3 text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap transition-colors" data-state="completed">
                        Done
                        <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold bg-gray-200 text-gray-700 rounded-full" id="count-completed">0</span>
                    </button>
                </div>
            </div>

            <!-- Tasks List -->
            <div class="p-6">
                @php
                    $teamTasks = $team->tasks()->with(['user', 'creator'])->orderBy('created_at', 'desc')->get();
                @endphp

                @if($teamTasks->isEmpty())
                    <div class="text-center py-16">
                        <div class="inline-block bg-gradient-to-br from-gray-100 to-gray-200 rounded-full p-8 mb-4">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500 text-lg font-medium">No tasks yet</p>
                        <p class="text-gray-400 text-sm mt-2">Tasks assigned to this team will appear here</p>
                    </div>
                @else
                    <div id="tasksList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($teamTasks as $task)
                            <div class="task-card bg-white border-2 border-gray-200 rounded-xl p-4 hover:shadow-lg transition-all duration-200 hover:scale-105" data-state="{{ $task->state }}">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <h3 class="font-bold text-gray-900 mb-1 line-clamp-2">{{ $task->title }}</h3>
                                        @if($task->description)
                                            <p class="text-sm text-gray-600 line-clamp-2">{{ Str::limit($task->description, 80) }}</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- State Badge -->
                                <div class="mb-3">
                                    @php
                                        $stateColors = [
                                            'pending assignment' => 'bg-gray-100 text-gray-700 border-gray-300',
                                            'team assigned' => 'bg-gray-200 text-gray-800 border-gray-300',
                                            'assigned to user' => 'bg-gray-200 text-gray-800 border-gray-400',
                                            'reassigned to user' => 'bg-gray-200 text-gray-800 border-gray-400',
                                            'working' => 'bg-gray-700 text-white border-gray-700',
                                            'submitted to review' => 'bg-gray-300 text-gray-900 border-gray-400',
                                            'completed' => 'bg-gray-900 text-white border-gray-900',
                                        ];
                                        $stateLabels = [
                                            'pending assignment' => 'Pending',
                                            'team assigned' => 'Team',
                                            'assigned to user' => 'Assigned',
                                            'reassigned to user' => 'Reassigned',
                                            'working' => 'In Progress',
                                            'submitted to review' => 'Review',
                                            'completed' => 'Done',
                                        ];
                                        $stateColor = $stateColors[$task->state] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                        $stateLabel = $stateLabels[$task->state] ?? ucfirst($task->state);
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border-2 {{ $stateColor }}">
                                        @if($task->state === 'pending assignment')
                                            <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                        @elseif($task->state === 'team assigned')
                                            <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                                            </svg>
                                        @elseif(in_array($task->state, ['assigned to user', 'reassigned to user']))
                                            <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
                                            </svg>
                                        @elseif($task->state === 'working')
                                            <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        @elseif($task->state === 'submitted to review')
                                            <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        @elseif($task->state === 'completed')
                                            <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                        @endif
                                        {{ $stateLabel }}
                                    </span>
                                </div>

                                <!-- Task Meta -->
                                <div class="space-y-2 text-xs text-gray-500 mb-3">
                                    @if($task->user)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            <span class="font-medium text-gray-700">{{ $task->user->name }}</span>
                                        </div>
                                    @endif
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span>{{ $task->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>

                                <!-- View Button -->
                                <a href="{{ route('tasks.show', $task->id) }}" class="block w-full text-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-semibold text-sm">
                                    <svg class="w-4 h-4 inline mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View Details
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Remove Member Modal -->
        <div id="removeMemberModal" class="fixed inset-0 z-50 hidden items-center justify-center backdrop-blur-sm">
            <div class="fixed inset-0 bg-black/50 transition-opacity" aria-hidden="true" onclick="closeRemoveMemberModal()"></div>
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 z-10 overflow-hidden transform transition-all">
                <div class="px-6 py-5 bg-gradient-to-r from-red-600 to-red-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-bold text-white">Remove Member</h3>
                            <p class="text-red-100 text-sm mt-0.5">Remove member from team</p>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-5">
                    <div class="p-4 bg-red-50 rounded-xl border border-red-200">
                        <p class="text-sm text-gray-700">
                            Are you sure you want to remove <span id="removeMemberName" class="font-bold text-red-700"></span> from this team?
                        </p>
                        <p class="text-sm text-gray-600 mt-2">This action cannot be undone.</p>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-100">
                    <button type="button" onclick="closeRemoveMemberModal()" class="inline-flex items-center px-5 py-2.5 border-2 border-gray-300 text-sm font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
                        Cancel
                    </button>
                    <form id="removeMemberForm" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-semibold rounded-xl text-white bg-red-600 hover:bg-red-700 transition-all duration-200 shadow-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Remove Member
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Assign Member Modal -->
    <div id="assignMemberModal" class="fixed inset-0 z-50 hidden items-center justify-center backdrop-blur-sm">
        <div class="fixed inset-0 bg-black/50 transition-opacity" aria-hidden="true" onclick="closeAssignMemberModal()"></div>
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 z-10 overflow-hidden transform transition-all">
            <div class="px-6 py-5 bg-gradient-to-r from-green-600 to-emerald-600">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xl font-bold text-white">Assign Member</h3>
                        <p class="text-green-100 text-sm mt-0.5">Add a new member to {{ $team->name }}</p>
                    </div>
                </div>
            </div>
            <form id="assignMemberForm" method="POST" action="{{ route('teams.addUser', $team->id) }}">
                @csrf
                <div class="px-6 py-5 space-y-4">
                    <div>
                        <label for="assign_user_search" class="block text-sm font-semibold text-gray-700 mb-2">
                            Search Student <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input id="assign_user_search" type="search" class="block w-full pl-10 pr-3 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" placeholder="Search by name or student ID...">
                        </div>
                        <div id="assignSearchResults" class="mt-2 bg-white border-2 border-gray-200 rounded-xl max-h-64 overflow-auto hidden shadow-lg"></div>
                        <input type="hidden" name="user_id" id="assign_selected_user_id">
                        <div id="assignSelectedUser" class="mt-3 text-sm"></div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-100">
                    <button type="button" onclick="closeAssignMemberModal()" class="inline-flex items-center px-5 py-2.5 border-2 border-gray-300 text-sm font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Assign Member
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openRemoveMemberModal(teamId, userId, userName) {
        const modal = document.getElementById('removeMemberModal');
        document.getElementById('removeMemberName').textContent = userName;
        const form = document.getElementById('removeMemberForm');
        form.action = `/teams/${teamId}/users/${userId}`;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeRemoveMemberModal() {
        const modal = document.getElementById('removeMemberModal');
        if (!modal) return;
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Assign Member modal logic (search + select)
    const assignModal = document.getElementById('assignMemberModal');
    const assignUserSearch = document.getElementById('assign_user_search');
    const assignSearchResults = document.getElementById('assignSearchResults');
    const assignSelectedUserId = document.getElementById('assign_selected_user_id');
    const assignSelectedUser = document.getElementById('assignSelectedUser');
    let assignSearchTimeout = null;

    function openAssignMemberModal() {
        if (!assignModal) return;
        assignModal.classList.remove('hidden');
        assignModal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        setTimeout(() => { if (assignUserSearch) assignUserSearch.focus(); }, 50);
    }

    function closeAssignMemberModal() {
        if (!assignModal) return;
        assignModal.classList.remove('flex');
        assignModal.classList.add('hidden');
        document.body.style.overflow = '';
        if (assignSearchResults) { assignSearchResults.classList.add('hidden'); assignSearchResults.innerHTML = ''; }
        if (assignUserSearch) assignUserSearch.value = '';
        if (assignSelectedUserId) assignSelectedUserId.value = '';
        if (assignSelectedUser) assignSelectedUser.innerHTML = '';
    }

    function renderAssignSearchResults(items) {
        if (!items || items.length === 0) {
            assignSearchResults.innerHTML = '<div class="p-4 text-center text-sm text-gray-500">No students found</div>';
            assignSearchResults.classList.remove('hidden');
            return;
        }
        assignSearchResults.innerHTML = items.map(u => `
            <button type="button" class="w-full text-left px-4 py-3 hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 transition-all border-b border-gray-100 last:border-0" data-id="${u.id}" data-name="${u.name}" data-studentid="${u.student_id}" data-email="${u.email}">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-100 to-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-semibold text-gray-900">${u.name}</div>
                        <div class="text-xs text-gray-500">ID: ${u.student_id} • ${u.email}</div>
                    </div>
                </div>
            </button>
        `).join('');
        assignSearchResults.classList.remove('hidden');

        Array.from(assignSearchResults.children).forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const sid = this.getAttribute('data-studentid');
                const email = this.getAttribute('data-email');
                assignSelectedUserId.value = id;
                assignSelectedUser.innerHTML = `
                    <div class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border-2 border-green-200">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-bold text-gray-900">${name}</div>
                                <div class="text-xs text-gray-600">ID: ${sid} • ${email}</div>
                            </div>
                        </div>
                    </div>
                `;
                assignSearchResults.classList.add('hidden');
            });
        });
    }

    function doAssignUserSearch(q) {
        if (!q) { assignSearchResults.classList.add('hidden'); assignSearchResults.innerHTML = ''; return; }
        fetch(`{{ url('/users') }}?search=${encodeURIComponent(q)}`, { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => renderAssignSearchResults(data))
            .catch(err => console.error('User search error', err));
    }

    if (assignUserSearch) {
        assignUserSearch.addEventListener('input', function(e) {
            const q = e.target.value.trim();
            clearTimeout(assignSearchTimeout);
            assignSearchTimeout = setTimeout(() => doAssignUserSearch(q), 250);
        });
    }

    // Prevent assign form submit without select
    const assignForm = document.getElementById('assignMemberForm');
    if (assignForm) {
        assignForm.addEventListener('submit', function(e) {
            if (!assignSelectedUserId.value) {
                e.preventDefault();
                alert('Please select a student from search results before assigning.');
            }
        });
    }

    // ESC key handling for all modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeRemoveMemberModal();
            closeAssignMemberModal();
        }
    });

    // Task filtering functionality
    let currentTaskFilter = 'all';

    function filterTasks(state) {
        currentTaskFilter = state;
        const taskCards = document.querySelectorAll('.task-card');
        const filterBtns = document.querySelectorAll('.task-filter-btn');

        // Update button styles
        filterBtns.forEach(btn => {
            const btnState = btn.getAttribute('data-state');
            if (btnState === state) {
                btn.classList.remove('border-transparent', 'text-gray-500');
                btn.classList.add('border-gray-800', 'text-gray-800');
            } else {
                btn.classList.remove('border-gray-800', 'text-gray-800');
                btn.classList.add('border-transparent', 'text-gray-500');
            }
        });

        // Filter task cards
        taskCards.forEach(card => {
            const cardState = card.getAttribute('data-state');
            if (state === 'all' || cardState === state) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Calculate and display task counts
    function updateTaskCounts() {
        const taskCards = document.querySelectorAll('.task-card');
        const counts = {
            all: taskCards.length,
            'pending assignment': 0,
            'team assigned': 0,
            'assigned to user': 0,
            'reassigned to user': 0,
            'working': 0,
            'submitted to review': 0,
            'completed': 0
        };

        taskCards.forEach(card => {
            const state = card.getAttribute('data-state');
            if (counts.hasOwnProperty(state)) {
                counts[state]++;
            }
        });

        // Update count badges
        Object.keys(counts).forEach(state => {
            const badge = document.getElementById(`count-${state}`);
            if (badge) {
                badge.textContent = counts[state];
            }
        });
    }

    // Initialize task counts on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateTaskCounts();
    });
</script>
@endsection
