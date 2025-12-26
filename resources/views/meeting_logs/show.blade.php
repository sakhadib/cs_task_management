@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <div class="flex items-center space-x-2 text-sm text-gray-500 mb-1">
                    <a href="{{ route('meeting_logs.index') }}" class="hover:text-indigo-600 transition-colors">Meeting Logs</a>
                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    <span>Details</span>
                </div>
                
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                    Meeting Details
                </h1>
                
                @if($panel)
                    <p class="mt-2 text-sm text-gray-600 flex items-center">
                        <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Panel: <span class="font-semibold text-indigo-600 ml-1">{{ $panel->name }}</span>
                    </p>
                @endif
            </div>

            <div class="mt-4 md:mt-0 flex space-x-3">
                <a href="{{ route('meeting_logs.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                    Back
                </a>
                @if(auth()->check())
                <button type="button" onclick="openEditMinutesModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit Log
                </button>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            
            {{-- Left Column: Meta Information --}}
            <div class="lg:col-span-1 space-y-6">
                
                {{-- Status Card --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Status</h3>
                    <div class="flex items-center">
                        @if($status === 'scheduled')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                <span class="w-2 h-2 mr-2 bg-indigo-500 rounded-full"></span> Scheduled
                            </span>
                        @elseif($status === 'running')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-50 text-green-700 border border-green-100">
                                <span class="w-2 h-2 mr-2 bg-green-500 rounded-full animate-pulse"></span> Running
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                Completed
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Meeting Info Card --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
                    
                    <div>
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Schedule</h3>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($log->scheduled_at)->format('M j, Y') }}</p>
                                <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($log->scheduled_at)->format('g:i a') }} ({{ $log->duration ?? 30 }} mins)</p>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-100"></div>

                    <div>
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Format</h3>
                        <div class="flex items-center">
                            @if($log->type === 'online')
                                <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                <span class="text-sm font-medium text-gray-900">Online Meeting</span>
                            @else
                                <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                <span class="text-sm font-medium text-gray-900">In-Person</span>
                            @endif
                        </div>
                    </div>

                    <div class="border-t border-gray-100"></div>

                    <div>
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                            {{ $log->type === 'online' ? 'Joining Link' : 'Location' }}
                        </h3>
                        @if($log->type === 'online')
                            @if($log->joining_url)
                                <a href="{{ $log->joining_url }}" target="_blank" class="flex items-center text-sm text-indigo-600 hover:text-indigo-800 hover:underline break-all group">
                                    {{ Str::limit($log->joining_url, 30) }}
                                    <svg class="w-4 h-4 ml-1 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                </a>
                            @else
                                <span class="text-sm text-gray-400 italic">No link provided</span>
                            @endif
                        @else
                             <p class="text-sm text-gray-900">{{ $log->location ?? 'Not specified' }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Right Column: Meeting Minutes (Main Content) --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 h-full flex flex-col">
                    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50 rounded-t-xl">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Meeting Minutes
                        </h2>
                    </div>
                    
                    <div class="p-8 flex-grow">
                        @if($log->meeting_minutes)
                            <div class="prose prose-indigo max-w-none text-gray-800 leading-relaxed">
                                {!! $log->meeting_minutes !!}
                            </div>
                                @else
                            <div class="h-40 flex flex-col items-center justify-center text-center">
                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                <p class="text-gray-400 text-sm">No minutes recorded yet.</p>
                                @if(auth()->check())
                                    <button onclick="openEditMinutesModal()" class="mt-2 text-indigo-600 hover:text-indigo-800 text-sm font-medium hover:underline">Start writing</button>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom Section: Attendees Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <div class="flex items-center space-x-3">
                    <div class="bg-indigo-100 text-indigo-600 p-2 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Attendees</h3>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    {{ $attendees->count() }} Present
                </span>
            </div>

            @if($attendees->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Participant Name
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Student ID
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Email Address
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($attendees as $a)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-xs font-bold">
                                                {{ substr($a->user->name ?? '?', 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $a->user->name ?? 'Unknown User' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ $a->user->student_id ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $a->user->email ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-12 flex flex-col items-center justify-center text-center">
                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <p class="text-gray-500 text-sm">No attendees recorded for this meeting.</p>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    function openEditMinutesModal() {
        let modal = document.getElementById('editMinutesModal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'editMinutesModal';
            modal.className = 'fixed inset-0 z-50 hidden items-center justify-center';
            // Styled Modal Inner HTML
            modal.innerHTML = `
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeEditMinutesModal()"></div>
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl mx-4 z-10 overflow-hidden transform transition-all flex flex-col max-h-[90vh]">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-900">Edit Meeting Minutes</h3>
                        <button onclick="closeEditMinutesModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <form id="editMinutesForm" method="POST" action="{{ route('meeting_logs.update_minutes', $log->id) }}" class="flex flex-col flex-grow overflow-hidden">
                        @csrf
                        @method('PATCH')
                        
                        <div class="px-6 py-6 overflow-y-auto flex-grow">
                             <div class="prose-editor-wrapper">
                                <textarea id="minutes_editor" name="meeting_minutes" class="w-full">{{ $log->meeting_minutes ?? '' }}</textarea>
                             </div>
                        </div>

                        <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-100 mt-auto">
                            <button type="button" onclick="closeEditMinutesModal()" class="px-5 py-2.5 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 transition-all">Cancel</button>
                            <button type="submit" class="px-5 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-md transition-all">Save Changes</button>
                        </div>
                    </form>
                </div>
            `;
            document.body.appendChild(modal);
        }

        // Initialize CKEditor with improved configuration for height
        try {
            if (!window.minutesEditor && document.getElementById('minutes_editor')) {
                ClassicEditor.create(document.getElementById('minutes_editor'), {
                    // Optional: Custom toolbar configuration if needed
                }).then(editor => { 
                    window.minutesEditor = editor; 
                    // Set a minimum height for the editor content area via CSS injection
                    editor.ui.view.editable.element.style.minHeight = '300px';
                }).catch(error => {
                    console.error(error);
                });
            }
        } catch(e) {}

        // Copy editor data on submit
        const form = document.getElementById('editMinutesForm');
        if (form) {
            form.addEventListener('submit', function() {
                try { if (window.minutesEditor) document.getElementById('minutes_editor').value = window.minutesEditor.getData(); } catch(e) {}
            });
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Prevent body scroll when modal is open
        document.body.style.overflow = 'hidden';
    }

    function closeEditMinutesModal() {
        const modal = document.getElementById('editMinutesModal');
        if (!modal) return;
        
        try {
            if (window.minutesEditor) {
                window.minutesEditor.destroy();
                window.minutesEditor = null;
            }
        } catch(e) {}
        
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        
        // Restore body scroll
        document.body.style.overflow = 'auto';
    }
</script>
@endsection