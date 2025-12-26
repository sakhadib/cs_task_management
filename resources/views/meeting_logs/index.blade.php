@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8 animate-fade-in">
            @php $filterLabel = isset($currentFilter) ? ($currentFilter === 'all' ? 'All' : ucfirst($currentFilter)) : 'Scheduled'; @endphp
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900">Meeting Logs</h1>
            @if(isset($currentPanel) && $currentPanel)
                <p class="mt-2 text-gray-600">{{ $currentPanel->name }} • {{ $filterLabel }} meetings</p>
            @else
                <p class="mt-2 text-gray-600">No current panel selected</p>
            @endif
        </div>
        <!-- CKEditor CDN -->
        <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

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
            <div class="mb-6 rounded-xl bg-gradient-to-r from-red-50 to-rose-50 p-4 border-l-4 border-red-500 shadow-lg animate-fade-in">
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

        {{-- Meetings Section --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="px-6 py-5 bg-gray-800 flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-xl font-bold text-white">All Meetings</h2>
                        <p class="text-gray-300 text-sm">Schedule and manage meetings</p>
                    </div>
                </div>
                @if(auth()->check())
                    @if(isset($currentPanel) && $currentPanel)
                        <button onclick="openNewMeetingModal()" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-semibold text-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            New Meeting
                        </button>
                    @else
                        <button disabled class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed font-semibold text-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            New Meeting
                        </button>
                    @endif
                @endif
            </div>

            {{-- Filters Section --}}
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex flex-wrap items-center gap-3">
                    @php
                        $filters = ['all' => 'All', 'scheduled' => 'Scheduled', 'running' => 'Running', 'completed' => 'Completed'];
                        $active = isset($currentFilter) ? $currentFilter : 'scheduled';
                    @endphp
                    @foreach($filters as $key => $label)
                        <a href="{{ route('meeting_logs.index', array_merge(request()->except('page'), ['filter' => $key])) }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-lg transition-all duration-200 {{ $active === $key ? 'bg-gray-800 text-white border-2 border-gray-800' : 'bg-gray-100 text-gray-700 border-2 border-gray-200 hover:bg-gray-200' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                    
                    <div class="border-l-2 border-gray-300 h-8 mx-2"></div>
                    
                    <form method="GET" action="{{ route('meeting_logs.index') }}" class="inline-flex items-center space-x-2">
                        <input type="hidden" name="filter" value="{{ $currentFilter ?? 'scheduled' }}">
                        <input type="date" name="date" value="{{ $selectedDate ?? '' }}" class="px-3 py-2 border-2 border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-500 focus:border-transparent">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-all duration-200 font-semibold text-sm">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Search
                        </button>
                        @if(!empty($selectedDate))
                            <a href="{{ route('meeting_logs.index', array_merge(request()->except(['page','date']), ['filter' => $currentFilter ?? 'scheduled'])) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-semibold text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Clear
                            </a>
                        @endif
                    </form>
                </div>
            </div>
            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Scheduled At</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Location/URL</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($logs as $log)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                @php
                                    $now = \Carbon\Carbon::now();
                                    $start = \Carbon\Carbon::parse($log->scheduled_at);
                                    $durationMinutes = $log->duration ?? 30;
                                    $end = (clone $start)->addMinutes($durationMinutes);
                                    if ($now->lt($start)) {
                                        $status = 'scheduled';
                                    } elseif ($now->between($start, $end)) {
                                        $status = 'running';
                                    } else {
                                        $status = 'completed';
                                    }
                                @endphp
                                <td class="px-6 py-4 text-sm">
                                    @if($log->type === 'online')
                                        <div class="inline-flex items-center">
                                            <div class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center mr-2">
                                                <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                                </svg>
                                            </div>
                                            <span class="text-gray-900 font-medium">Online</span>
                                        </div>
                                    @else
                                        <div class="inline-flex items-center">
                                            <div class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center mr-2">
                                                <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                            </div>
                                            <span class="text-gray-900 font-medium">Offline</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ \Carbon\Carbon::parse($log->scheduled_at)->format('M j, Y g:ia') }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @if($log->joining_url)
                                        @if($log->type === 'online' && $status === 'completed')
                                            <span class="text-gray-400">{{ Str::limit($log->joining_url, 30) }}</span>
                                        @else
                                            <a href="{{ $log->joining_url }}" target="_blank" class="text-gray-700 hover:text-gray-900 hover:underline font-medium">{{ Str::limit($log->joining_url, 30) }}</a>
                                        @endif
                                    @elseif($log->location)
                                        <span class="text-gray-700">{{ $log->location }}</span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($status === 'scheduled')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-200 text-gray-800 border-2 border-gray-300">Scheduled</span>
                                    @elseif($status === 'running')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-700 text-white border-2 border-gray-700">Running</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-900 text-white border-2 border-gray-900">Completed</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-right">
                                    <div class="inline-flex items-center justify-end flex-wrap gap-2">
                                        @if(auth()->check() && auth()->user()->role !== 'member')
                                            <button type="button" onclick="openEditMeetingModal({{ $log->id }}, '{{ $log->type }}', '{{ addslashes($log->joining_url ?? '') }}', '{{ addslashes($log->location ?? '') }}', '{{ $log->scheduled_at }}', '{{ $log->duration ?? '' }}')" class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-medium text-xs">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Edit
                                            </button>
                                        @endif
                                        @if(auth()->check() && auth()->user()->role !== 'member')
                                            <button type="button" onclick="openDeleteMeetingModal({{ $log->id }}, '{{ addslashes($log->joining_url ?? $log->location ?? '') }}')" class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-medium text-xs">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Delete
                                            </button>
                                        @endif
                                        @if(($status === 'running' || $status === 'completed') && auth()->check() && auth()->user()->role !== 'member')
                                            <a href="{{ route('meeting_logs.attendees.index', $log->id) }}" class="inline-flex items-center px-3 py-1 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-all duration-200 font-medium text-xs">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                                </svg>
                                                Attendees
                                            </a>
                                        @endif
                                        @if($status === 'completed')
                                            <a href="{{ route('meeting_logs.show', $log->id) }}" class="inline-flex items-center px-3 py-1 bg-gray-900 text-white rounded-lg hover:bg-black transition-all duration-200 font-medium text-xs">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Details
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="inline-block bg-gray-100 rounded-full p-8 mb-4">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 text-lg font-medium">No meetings found</p>
                                    <p class="text-gray-400 text-sm mt-2">Try adjusting your filters</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">{{ $logs->links() }}</div>
        </div>

        <!-- New Meeting Modal -->
        <div id="newMeetingModal" class="fixed inset-0 z-50 hidden items-center justify-center backdrop-blur-sm">
            <div class="fixed inset-0 bg-black/50 transition-opacity" aria-hidden="true" onclick="closeNewMeetingModal()"></div>
            <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 z-10 overflow-hidden transform transition-all">
                <div class="px-6 py-5 bg-gray-800">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-bold text-white">Create New Meeting</h3>
                            <p class="text-gray-300 text-sm mt-0.5">Schedule a meeting for your panel</p>
                        </div>
                    </div>
                </div>
                <form id="newMeetingForm" method="POST" action="{{ route('meeting_logs.store') }}">
                    @csrf
                    <div class="p-6 space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Meeting Type *</label>
                            <input type="hidden" id="new_meeting_type" name="type" value="online">
                            <div class="flex space-x-2">
                                <button type="button" id="new_toggle_online" onclick="setNewMeetingType('online')" class="flex-1 flex items-center justify-center space-x-2 px-4 py-3 border-2 border-gray-800 bg-gray-800 text-white rounded-xl transition-all font-medium">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                    </svg>
                                    <span>Online</span>
                                </button>
                                <button type="button" id="new_toggle_offline" onclick="setNewMeetingType('offline')" class="flex-1 flex items-center justify-center space-x-2 px-4 py-3 border-2 border-gray-300 bg-white text-gray-700 rounded-xl transition-all font-medium hover:bg-gray-50">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span>Offline</span>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Scheduled At *</label>
                            <input id="new_meeting_scheduled_at" name="scheduled_at" type="datetime-local" required class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-all text-sm">
                        </div>
                        <div id="new_meeting_url_wrapper">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Joining URL</label>
                            <input id="new_meeting_url" name="joining_url" type="url" class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-all text-sm" placeholder="https://meet.google.com/...">
                        </div>
                        <div id="new_meeting_location_wrapper" class="hidden">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Location</label>
                            <input id="new_meeting_location" name="location" type="text" class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-all text-sm" placeholder="Room 101, Main Building">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Duration (minutes)</label>
                            <input name="duration" type="number" min="0" class="block w-32 px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-all text-sm" placeholder="30">
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-200">
                        <button type="button" onclick="closeNewMeetingModal()" class="inline-flex items-center px-5 py-2.5 border-2 border-gray-300 text-sm font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">Cancel</button>
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-semibold rounded-xl text-white bg-gray-800 hover:bg-gray-900 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Create Meeting
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <script>
            // Toggle meeting type for new modal
            function setNewMeetingType(type) {
                const typeInput = document.getElementById('new_meeting_type');
                const onlineBtn = document.getElementById('new_toggle_online');
                const offlineBtn = document.getElementById('new_toggle_offline');
                const urlWrap = document.getElementById('new_meeting_url_wrapper');
                const locWrap = document.getElementById('new_meeting_location_wrapper');
                
                if (!typeInput || !onlineBtn || !offlineBtn || !urlWrap || !locWrap) return;
                
                typeInput.value = type;
                
                if (type === 'online') {
                    onlineBtn.className = 'flex-1 flex items-center justify-center space-x-2 px-4 py-3 border-2 border-gray-800 bg-gray-800 text-white rounded-xl transition-all font-medium';
                    offlineBtn.className = 'flex-1 flex items-center justify-center space-x-2 px-4 py-3 border-2 border-gray-300 bg-white text-gray-700 rounded-xl transition-all font-medium hover:bg-gray-50';
                    urlWrap.classList.remove('hidden');
                    locWrap.classList.add('hidden');
                } else {
                    onlineBtn.className = 'flex-1 flex items-center justify-center space-x-2 px-4 py-3 border-2 border-gray-300 bg-white text-gray-700 rounded-xl transition-all font-medium hover:bg-gray-50';
                    offlineBtn.className = 'flex-1 flex items-center justify-center space-x-2 px-4 py-3 border-2 border-gray-800 bg-gray-800 text-white rounded-xl transition-all font-medium';
                    urlWrap.classList.add('hidden');
                    locWrap.classList.remove('hidden');
                }
            }
        </script>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Edit modal (dynamic)
    function openEditMeetingModal(id, type, url, location, scheduledAt, duration) {
        let modal = document.getElementById('editMeetingModal');
                if (!modal) {
            modal = document.createElement('div');
            modal.id = 'editMeetingModal';
            modal.className = 'fixed inset-0 z-50 hidden items-center justify-center';
            modal.innerHTML = `
                <div class="fixed inset-0 bg-black/50" aria-hidden="true" onclick="closeEditMeetingModal()"></div>
                <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-4 z-10 overflow-hidden">
                    <form id="editMeetingForm" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <div class="bg-gray-800 px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-white">Edit Meeting</h3>
                            </div>
                        </div>
                        <div class="px-6 py-5 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Type</label>
                                <input type="hidden" id="edit_meeting_type" name="type" value="online">
                                <div class="flex space-x-2">
                                    <button type="button" id="edit_toggle_online" onclick="setEditMeetingType('online')" class="flex-1 flex items-center justify-center space-x-2 px-4 py-2.5 border-2 border-gray-800 bg-gray-800 text-white rounded-xl transition-all font-medium">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                        </svg>
                                        <span>Online</span>
                                    </button>
                                    <button type="button" id="edit_toggle_offline" onclick="setEditMeetingType('offline')" class="flex-1 flex items-center justify-center space-x-2 px-4 py-2.5 border-2 border-gray-300 bg-white text-gray-700 rounded-xl transition-all font-medium hover:bg-gray-50">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span>Offline</span>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Scheduled At</label>
                                <input name="scheduled_at" id="edit_meeting_scheduled_at" type="datetime-local" required class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-gray-800 transition-colors">
                            </div>
                            <div id="edit_meeting_url_wrapper">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Joining URL</label>
                                <input name="joining_url" id="edit_meeting_url" type="url" class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-gray-800 transition-colors" placeholder="https://meet.google.com/...">
                            </div>
                            <div id="edit_meeting_location_wrapper" class="hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Location</label>
                                <input name="location" id="edit_meeting_location" type="text" class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-gray-800 transition-colors" placeholder="Enter physical location">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Duration (minutes)</label>
                                <input name="duration" id="edit_meeting_duration" type="number" min="0" class="w-32 px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-gray-800 transition-colors">
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-200">
                            <button type="button" onclick="closeEditMeetingModal()" class="inline-flex items-center px-4 py-2.5 border-2 border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="inline-flex items-center space-x-2 px-4 py-2.5 text-sm font-medium rounded-xl text-white bg-gray-800 hover:bg-black transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Save Changes</span>
                            </button>
                        </div>
                    </form>
                </div>
            `;
            document.body.appendChild(modal);
        }

        // fill values and set type toggle
        setEditMeetingType(type);
        // set min for edit modal
        try {
            const now = new Date();
            const minIso = new Date(now.getTime() - now.getTimezoneOffset()*60000).toISOString().slice(0,16);
            const editEl = document.getElementById('edit_meeting_scheduled_at');
            if (editEl) editEl.min = minIso;
        } catch(e) {}
        // scheduledAt may not be in local datetime format; try to set via Date
        try {
            const d = new Date(scheduledAt);
            const iso = new Date(d.getTime() - d.getTimezoneOffset()*60000).toISOString().slice(0,16);
            document.getElementById('edit_meeting_scheduled_at').value = iso;
        } catch(e) {}
        document.getElementById('edit_meeting_url').value = url || '';
        document.getElementById('edit_meeting_location').value = location || '';
        document.getElementById('edit_meeting_duration').value = duration || '';

        const form = document.getElementById('editMeetingForm');
        form.action = `/meeting-logs/${id}`;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

function closeEditMeetingModal() {
        const modal = document.getElementById('editMeetingModal');
        if (!modal) return;
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Toggle meeting type for edit modal
    function setEditMeetingType(type) {
        const typeInput = document.getElementById('edit_meeting_type');
        const onlineBtn = document.getElementById('edit_toggle_online');
        const offlineBtn = document.getElementById('edit_toggle_offline');
        const urlWrap = document.getElementById('edit_meeting_url_wrapper');
        const locWrap = document.getElementById('edit_meeting_location_wrapper');
        
        if (!typeInput || !onlineBtn || !offlineBtn || !urlWrap || !locWrap) return;
        
        typeInput.value = type;
        
        if (type === 'online') {
            onlineBtn.className = 'flex-1 flex items-center justify-center space-x-2 px-4 py-2.5 border-2 border-gray-800 bg-gray-800 text-white rounded-xl transition-all font-medium';
            offlineBtn.className = 'flex-1 flex items-center justify-center space-x-2 px-4 py-2.5 border-2 border-gray-300 bg-white text-gray-700 rounded-xl transition-all font-medium hover:bg-gray-50';
            urlWrap.classList.remove('hidden');
            locWrap.classList.add('hidden');
        } else {
            onlineBtn.className = 'flex-1 flex items-center justify-center space-x-2 px-4 py-2.5 border-2 border-gray-300 bg-white text-gray-700 rounded-xl transition-all font-medium hover:bg-gray-50';
            offlineBtn.className = 'flex-1 flex items-center justify-center space-x-2 px-4 py-2.5 border-2 border-gray-800 bg-gray-800 text-white rounded-xl transition-all font-medium';
            urlWrap.classList.add('hidden');
            locWrap.classList.remove('hidden');
        }
    }

    // Delete confirmation modal
    function openDeleteMeetingModal(id, label) {
        let modal = document.getElementById('deleteMeetingModal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'deleteMeetingModal';
            modal.className = 'fixed inset-0 z-50 hidden items-center justify-center';
            modal.innerHTML = `
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" aria-hidden="true" onclick="closeDeleteMeetingModal()"></div>
                <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 z-10 overflow-hidden">
                    <div class="bg-gray-800 px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-white">Delete Meeting</h3>
                        </div>
                    </div>
                    <div class="px-6 py-5">
                        <p class="text-sm text-gray-600">Are you sure you want to delete this meeting?</p>
                        <p class="mt-2 text-sm font-medium text-gray-900"><span id="deleteMeetingLabel"></span></p>
                        <p class="mt-3 text-xs text-gray-500">This action cannot be undone.</p>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-200">
                        <button type="button" onclick="closeDeleteMeetingModal()" class="inline-flex items-center px-4 py-2.5 border-2 border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <form id="deleteMeetingForm" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center space-x-2 px-4 py-2.5 text-sm font-medium rounded-xl text-white bg-gray-900 hover:bg-black transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                <span>Delete Meeting</span>
                            </button>
                        </form>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }

        document.getElementById('deleteMeetingLabel').textContent = label || '';
        const form = document.getElementById('deleteMeetingForm');
        form.action = `/meeting-logs/${id}`;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteMeetingModal() {
        const modal = document.getElementById('deleteMeetingModal');
        if (!modal) return;
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    // New meeting modal open/close
    function openNewMeetingModal() {
        const modal = document.getElementById('newMeetingModal');
        if (!modal) return;
        // set min for scheduled_at to now (local)
        try {
            const d = new Date();
            const iso = new Date(d.getTime() - d.getTimezoneOffset()*60000).toISOString().slice(0,16);
            const el = document.getElementById('new_meeting_scheduled_at');
            if (el) el.min = iso;
        } catch(e) {}
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeNewMeetingModal() {
        const modal = document.getElementById('newMeetingModal');
        if (!modal) return;
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    // ESC key support and initialization
    document.addEventListener('DOMContentLoaded', function() {
        // ESC key support for all modals
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const newModal = document.getElementById('newMeetingModal');
                const editModal = document.getElementById('editMeetingModal');
                const deleteModal = document.getElementById('deleteMeetingModal');
                
                if (newModal && !newModal.classList.contains('hidden')) {
                    closeNewMeetingModal();
                } else if (editModal && !editModal.classList.contains('hidden')) {
                    closeEditMeetingModal();
                } else if (deleteModal && !deleteModal.classList.contains('hidden')) {
                    closeDeleteMeetingModal();
                }
            }
        });
    });
</script>
@endsection
