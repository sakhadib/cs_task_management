@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header Section --}}
        <div class="bg-gray-800 rounded-2xl shadow-xl mb-6 overflow-hidden">
            <div class="px-8 py-6">
                <div class="flex items-center space-x-2 text-sm text-gray-300 mb-3">
                    <a href="{{ route('meeting_logs.index') }}" class="hover:text-white transition-colors">Meeting Logs</a>
                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    <span class="text-white">Details</span>
                </div>
                
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-white">Meeting Details</h1>
                            @if($panel)
                                <p class="mt-1 text-gray-300 text-sm flex items-center">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    Panel: <span class="font-semibold ml-1">{{ $panel->name }}</span>
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 md:mt-0 flex space-x-3">
                        <a href="{{ route('meeting_logs.index') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 border-2 border-white/20 text-sm font-medium rounded-xl text-white bg-white/10 hover:bg-white/20 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            <span>Back</span>
                        </a>
                        @if(auth()->check())
                        <button type="button" onclick="openEditMinutesModal()" class="inline-flex items-center space-x-2 px-4 py-2.5 text-sm font-medium rounded-xl text-gray-900 bg-white hover:bg-gray-100 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Edit Minutes
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            
            {{-- Left Column: Meta Information --}}
            <div class="lg:col-span-1 space-y-6">
                
                {{-- Status Card --}}
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                    <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-4">Status</h3>
                    <div class="flex items-center">
                        @if($status === 'scheduled')
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-gray-200 text-gray-800 border-2 border-gray-300">
                                <span class="w-2 h-2 mr-2 bg-gray-700 rounded-full"></span> Scheduled
                            </span>
                        @elseif($status === 'running')
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-gray-700 text-white border-2 border-gray-800">
                                <span class="w-2 h-2 mr-2 bg-white rounded-full animate-pulse"></span> Running
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-gray-900 text-white border-2 border-black">
                                <span class="w-2 h-2 mr-2 bg-white rounded-full"></span> Completed
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Meeting Info Card --}}
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 space-y-6">
                    
                    <div>
                        <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Schedule</h3>
                        <div class="flex items-start">
                            <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($log->scheduled_at)->format('M j, Y') }}</p>
                                <p class="text-sm text-gray-600 mt-0.5">{{ \Carbon\Carbon::parse($log->scheduled_at)->format('g:i a') }} <span class="text-gray-400">•</span> {{ $log->duration ?? 30 }} mins</p>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200"></div>

                    <div>
                        <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Format</h3>
                        <div class="flex items-center">
                            @if($log->type === 'online')
                                <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                </div>
                                <span class="text-sm font-semibold text-gray-900">Online Meeting</span>
                            @else
                                <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <span class="text-sm font-semibold text-gray-900">In-Person</span>
                            @endif
                        </div>
                    </div>

                    <div class="border-t border-gray-200"></div>

                    <div>
                        <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">
                            {{ $log->type === 'online' ? 'Joining Link' : 'Location' }}
                        </h3>
                        @if($log->type === 'online')
                            @if($log->joining_url)
                                <a href="{{ $log->joining_url }}" target="_blank" class="inline-flex items-center text-sm text-gray-700 hover:text-gray-900 font-medium group">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    <span class="group-hover:underline break-all">{{ Str::limit($log->joining_url, 30) }}</span>
                                </a>
                            @else
                                <span class="text-sm text-gray-400 italic">No link provided</span>
                            @endif
                        @else
                             <p class="text-sm font-medium text-gray-900">{{ $log->location ?? 'Not specified' }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Right Column: Meeting Minutes (Main Content) --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 h-full flex flex-col">
                    <div class="bg-gray-800 px-6 py-4 rounded-t-2xl">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <h2 class="text-lg font-semibold text-white">Meeting Minutes</h2>
                        </div>
                    </div>
                    
                    <div class="p-8 flex-grow">
                        @if($log->meeting_minutes)
                            <div class="prose prose-gray max-w-none text-gray-800 leading-relaxed">
                                {!! $log->meeting_minutes !!}
                            </div>
                        @else
                            <div class="h-40 flex flex-col items-center justify-center text-center">
                                <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-3">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </div>
                                <p class="text-gray-900 font-medium text-sm">No minutes recorded yet</p>
                                @if(auth()->check())
                                    <button onclick="openEditMinutesModal()" class="mt-2 text-gray-700 hover:text-gray-900 text-sm font-medium hover:underline">Start writing</button>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom Section: Attendees Card --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="bg-gray-800 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-white">Attendees</h3>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white/20 text-white border border-white/30">
                        {{ $attendees->count() }} Present
                    </span>
                </div>
            </div>

            @if($attendees->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Participant Name
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Student ID
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Email Address
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($attendees as $a)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                <span class="text-sm font-semibold text-gray-700">{{ substr($a->user->name ?? '?', 0, 1) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-gray-900">
                                                    {{ $a->user->name ?? 'Unknown User' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2.5 py-1 inline-flex text-xs font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                                            {{ $a->user->student_id ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $a->user->email ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-12 flex flex-col items-center justify-center text-center">
                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-3">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <p class="text-gray-900 font-medium text-sm">No attendees recorded</p>
                    <p class="text-gray-500 text-xs mt-1">Mark attendance for this meeting</p>
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
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeEditMinutesModal()"></div>
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl mx-4 z-10 overflow-hidden transform transition-all flex flex-col max-h-[90vh]">
                    <div class="bg-gray-800 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </div>
                                <h3 class="text-lg font-semibold text-white">Edit Meeting Minutes</h3>
                            </div>
                            <button onclick="closeEditMinutesModal()" class="text-white/70 hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </div>
                    
                    <form id="editMinutesForm" method="POST" action="{{ route('meeting_logs.update_minutes', $log->id) }}" class="flex flex-col flex-grow overflow-hidden">
                        @csrf
                        @method('PATCH')
                        
                        <div class="px-6 py-6 overflow-y-auto flex-grow">
                             <div class="prose-editor-wrapper">
                                <textarea id="minutes_editor" name="meeting_minutes" class="w-full">{{ $log->meeting_minutes ?? '' }}</textarea>
                             </div>
                        </div>

                        <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-200 mt-auto">
                            <button type="button" onclick="closeEditMinutesModal()" class="inline-flex items-center px-5 py-2.5 border-2 border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all">Cancel</button>
                            <button type="submit" class="inline-flex items-center space-x-2 px-5 py-2.5 text-sm font-medium rounded-xl text-white bg-gray-800 hover:bg-black transition-all">
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
        document.body.style.overflow = '';
    }

    // ESC key support
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('editMinutesModal');
            if (modal && !modal.classList.contains('hidden')) {
                closeEditMinutesModal();
            }
        }
    });
</script>
@endsection