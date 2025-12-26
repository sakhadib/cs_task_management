@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                @php $filterLabel = isset($currentFilter) ? ($currentFilter === 'all' ? 'All' : ucfirst($currentFilter)) : 'Scheduled'; @endphp
                <h1 class="text-3xl font-extrabold text-gray-900">Meeting Logs <span class="text-sm font-medium text-gray-500">({{ $filterLabel }})</span></h1>
                @if(isset($currentPanel) && $currentPanel)
                    <p class="mt-2 text-sm text-gray-600">Meetings for panel: <span class="font-medium">{{ $currentPanel->name }}</span></p>
                @else
                    <p class="mt-2 text-sm text-gray-600">No current panel selected.</p>
                @endif
            </div>
            <div class="flex items-center gap-3">
                @if(auth()->check())
                    @if(isset($currentPanel) && $currentPanel)
                        <button onclick="openNewMeetingModal()" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">+ New Meeting</button>
                    @else
                        <button disabled class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 rounded-lg">+ New Meeting</button>
                    @endif
                @endif
            </div>
        </div>
        <!-- CKEditor CDN -->
        <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-50 border-l-4 border-green-400 text-green-700">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-400 text-red-700">{{ session('error') }}</div>
        @endif

        <!-- New Meeting Modal -->
        <div id="newMeetingModal" class="fixed inset-0 z-50 hidden items-center justify-center">
            <div class="fixed inset-0 bg-black/50" aria-hidden="true" onclick="closeNewMeetingModal()"></div>
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-4 z-10 overflow-hidden">
                <form id="newMeetingForm" method="POST" action="{{ route('meeting_logs.store') }}">
                    @csrf
                    <div class="px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-900">Create Meeting</h3>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Type</label>
                            <select id="new_meeting_type" name="type" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg">
                                <option value="online">Online</option>
                                <option value="offline">Offline</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Scheduled At</label>
                            <input id="new_meeting_scheduled_at" name="scheduled_at" type="datetime-local" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div id="new_meeting_url_wrapper">
                            <label class="block text-sm font-medium text-gray-700">Joining URL</label>
                            <input id="new_meeting_url" name="joining_url" type="url" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div id="new_meeting_location_wrapper" class="hidden">
                            <label class="block text-sm font-medium text-gray-700">Location</label>
                            <input id="new_meeting_location" name="location" type="text" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Duration (minutes)</label>
                            <input name="duration" type="number" min="0" class="mt-1 block w-32 px-3 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Meeting Minutes</label>
                            <textarea id="new_meeting_minutes" name="meeting_minutes" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg" rows="6"></textarea>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-100">
                        <button type="button" onclick="closeNewMeetingModal()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700">Create</button>
                    </div>
                </form>
            </div>
        </div>
        <script>
            // Ensure new modal fields toggle based on type
            function toggleNewMeetingFields() {
                const t = document.getElementById('new_meeting_type');
                const urlWrap = document.getElementById('new_meeting_url_wrapper');
                const locWrap = document.getElementById('new_meeting_location_wrapper');
                if (!t || !urlWrap || !locWrap) return;
                if (t.value === 'online') {
                    urlWrap.classList.remove('hidden');
                    locWrap.classList.add('hidden');
                } else {
                    urlWrap.classList.add('hidden');
                    locWrap.classList.remove('hidden');
                }
            }
            document.addEventListener('DOMContentLoaded', function() {
                const t = document.getElementById('new_meeting_type');
                if (t) {
                    t.addEventListener('change', toggleNewMeetingFields);
                }
            });
        </script>

        <div class="bg-white rounded-xl shadow overflow-hidden border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    @php
                        $filters = ['all' => 'All', 'scheduled' => 'Scheduled', 'running' => 'Running', 'completed' => 'Completed'];
                        $colors = [
                            'all' => ['bg' => 'rgba(220,38,38,0.08)', 'border' => 'rgb(220,38,38)', 'text' => 'rgb(185,28,28)'],
                            'scheduled' => ['bg' => 'rgba(99,102,241,0.12)', 'border' => 'rgb(99,102,241)', 'text' => 'rgb(79,70,229)'],
                            'running' => ['bg' => 'rgba(34,197,94,0.12)', 'border' => 'rgb(34,197,94)', 'text' => 'rgb(16,185,129)'],
                            'completed' => ['bg' => 'rgba(156,163,175,0.12)', 'border' => 'rgb(156,163,175)', 'text' => 'rgb(55,65,81)'],
                        ];
                    @endphp
                    @foreach($filters as $key => $label)
                        @php $c = $colors[$key]; $active = (isset($currentFilter) ? $currentFilter : 'scheduled') === $key; @endphp
                        <a href="{{ route('meeting_logs.index', array_merge(request()->except('page'), ['filter' => $key])) }}" class="inline-flex items-center px-3 py-1 text-sm font-medium" style="background-color: {{ $c['bg'] }}; border:1px solid {{ $c['border'] }}; color: {{ $c['text'] }}; border-radius:3px; {{ $active ? 'box-shadow: 0 0 0 2px rgba(0,0,0,0.04); font-weight:600;' : '' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                    
                    <form method="GET" action="{{ route('meeting_logs.index') }}" class="ml-4 inline-flex items-center space-x-2">
                        <input type="hidden" name="filter" value="{{ $currentFilter ?? 'scheduled' }}">
                        <input type="date" name="date" value="{{ $selectedDate ?? '' }}" class="px-2 py-1 border border-gray-200 rounded text-sm">
                        <button type="submit" class="px-3 py-1 text-sm" style="background-color: rgba(99,102,241,0.08); border:1px solid rgb(99,102,241); color: rgb(79,70,229); border-radius:3px">Go</button>
                        @if(!empty($selectedDate))
                            <a href="{{ route('meeting_logs.index', array_merge(request()->except(['page','date']), ['filter' => $currentFilter ?? 'scheduled'])) }}" class="px-3 py-1 text-sm" style="background-color: rgba(156,163,175,0.08); border:1px solid rgb(156,163,175); color: rgb(55,65,81); border-radius:3px">Clear</a>
                        @endif
                    </form>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scheduled At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">URL</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($logs as $log)
                            <tr class="hover:bg-gray-50">
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
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    @if($log->type === 'online')
                                        <i class="fas fa-globe text-green-500 mr-2" title="Online"></i>
                                        <span>Online</span>
                                    @else
                                        <i class="fas fa-map-marker-alt text-yellow-600 mr-2" title="Offline"></i>
                                        <span>Offline</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ \Carbon\Carbon::parse($log->scheduled_at)->format('M j, Y g:ia') }}</td>
                                <td class="px-6 py-4 text-sm text-indigo-600">
                                    @if($log->joining_url)
                                        @if($log->type === 'online' && $status === 'completed')
                                            <span class="text-gray-400">Open</span>
                                        @else
                                            <a href="{{ $log->joining_url }}" target="_blank" class="hover:underline">Open</a>
                                        @endif
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-left">
                                    @if($status === 'scheduled')
                                        <span style="background-color: rgba(99,102,241,0.12); border:1px solid rgb(99,102,241); color: rgb(79,70,229); border-radius:3px; padding:3px 8px; font-size:12px;">Scheduled</span>
                                    @elseif($status === 'running')
                                        <span style="background-color: rgba(34,197,94,0.12); border:1px solid rgb(34,197,94); color: rgb(16,185,129); border-radius:3px; padding:3px 8px; font-size:12px;">Running</span>
                                    @else
                                        <span style="background-color: rgba(156,163,175,0.12); border:1px solid rgb(156,163,175); color: rgb(55,65,81); border-radius:3px; padding:3px 8px; font-size:12px;">Completed</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-right">
                                    <div class="inline-flex items-center justify-end space-x-2">
                                        @if(auth()->check() && auth()->user()->role !== 'member')
                                            <button type="button" onclick="openEditMeetingModal({{ $log->id }}, '{{ $log->type }}', '{{ addslashes($log->joining_url ?? '') }}', '{{ addslashes($log->location ?? '') }}', '{{ $log->scheduled_at }}', '{{ $log->duration ?? '' }}')" class="px-3 py-1 bg-indigo-600 text-white text-sm rounded">Edit</button>
                                        @endif
                                        @if(auth()->check() && auth()->user()->role !== 'member')
                                            <button type="button" onclick="openDeleteMeetingModal({{ $log->id }}, '{{ addslashes($log->joining_url ?? $log->location ?? '') }}')" class="px-3 py-1 bg-red-600 text-white text-sm rounded">Delete</button>
                                        @endif
                                        @if(($status === 'running' || $status === 'completed') && auth()->check() && auth()->user()->role !== 'member')
                                            <a href="{{ route('meeting_logs.attendees.index', $log->id) }}" class="px-3 py-1 bg-green-600 text-white text-sm rounded">Mark Attendee</a>
                                        @endif
                                        @if($status === 'completed')
                                            <a href="{{ route('meeting_logs.show', $log->id) }}" class="px-3 py-1 bg-gray-800 text-white text-sm rounded">Meeting Details</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">No meeting logs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4">{{ $logs->links() }}</div>
        </div>
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
                        <div class="px-6 py-4">
                            <h3 class="text-lg font-semibold text-gray-900">Edit Meeting</h3>
                        </div>
                            <div class="px-6 py-4 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Type</label>
                                <select name="type" id="edit_meeting_type" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg">
                                    <option value="online">Online</option>
                                    <option value="offline">Offline</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Scheduled At</label>
                                <input name="scheduled_at" id="edit_meeting_scheduled_at" type="datetime-local" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <div id="edit_meeting_url_wrapper">
                                <label class="block text-sm font-medium text-gray-700">Joining URL</label>
                                <input name="joining_url" id="edit_meeting_url" type="url" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <div id="edit_meeting_location_wrapper" class="hidden">
                                <label class="block text-sm font-medium text-gray-700">Location</label>
                                <input name="location" id="edit_meeting_location" type="text" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Duration (minutes)</label>
                                <input name="duration" id="edit_meeting_duration" type="number" min="0" class="mt-1 block w-32 px-3 py-2 border border-gray-300 rounded-lg">
                            </div>
                            
                            
                        </div>
                        <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-100">
                            <button type="button" onclick="closeEditMeetingModal()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50">Cancel</button>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700">Save</button>
                        </div>
                    </form>
                </div>
            `;
            document.body.appendChild(modal);
        }

        // attach change listener for toggling edit fields
        const typeEl = document.getElementById('edit_meeting_type');
        if (typeEl) {
            typeEl.removeEventListener('change', toggleEditMeetingFields);
            typeEl.addEventListener('change', toggleEditMeetingFields);
        }

        // fill values
        document.getElementById('edit_meeting_type').value = type;
        // set min and toggle fields for edit modal
        try {
            const now = new Date();
            const minIso = new Date(now.getTime() - now.getTimezoneOffset()*60000).toISOString().slice(0,16);
            const editEl = document.getElementById('edit_meeting_scheduled_at');
            if (editEl) editEl.min = minIso;
        } catch(e) {}
        if (typeof toggleEditMeetingFields === 'function') toggleEditMeetingFields();
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
    }

        function closeEditMeetingModal() {
        const modal = document.getElementById('editMeetingModal');
        if (!modal) return;
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

    // Toggle fields inside edit modal
    function toggleEditMeetingFields() {
        const t = document.getElementById('edit_meeting_type');
        const urlWrap = document.getElementById('edit_meeting_url_wrapper');
        const locWrap = document.getElementById('edit_meeting_location_wrapper');
        if (!t || !urlWrap || !locWrap) return;
        if (t.value === 'online') {
            urlWrap.classList.remove('hidden');
            locWrap.classList.add('hidden');
        } else {
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
                <div class="fixed inset-0 bg-black/50" aria-hidden="true" onclick="closeDeleteMeetingModal()"></div>
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 z-10 overflow-hidden">
                    <div class="px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-900">Delete Meeting</h3>
                        <p class="mt-2 text-sm text-gray-600">Are you sure you want to delete this meeting (<span id="deleteMeetingLabel" class="font-medium"></span>)?</p>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-100">
                        <button type="button" onclick="closeDeleteMeetingModal()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50">Cancel</button>
                        <form id="deleteMeetingForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700">Delete</button>
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
    }

    function closeDeleteMeetingModal() {
        const modal = document.getElementById('deleteMeetingModal');
        if (!modal) return;
        modal.classList.remove('flex');
        modal.classList.add('hidden');
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
        // initialize CKEditor for new meeting minutes
        try {
            if (!window.newMeetingEditor && document.getElementById('new_meeting_minutes')) {
                ClassicEditor.create(document.getElementById('new_meeting_minutes')).then(editor => { window.newMeetingEditor = editor; }).catch(()=>{});
            }
        } catch(e) {}
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeNewMeetingModal() {
        const modal = document.getElementById('newMeetingModal');
        if (!modal) return;
        // destroy new editor instance if present
        try {
            if (window.newMeetingEditor) {
                window.newMeetingEditor.destroy();
                window.newMeetingEditor = null;
            }
        } catch(e) {}
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

        // Ensure new form copies editor data into textarea before submit
    document.addEventListener('DOMContentLoaded', function() {
        const newForm = document.getElementById('newMeetingForm');
        if (newForm) {
            newForm.addEventListener('submit', function() {
                try { if (window.newMeetingEditor) document.getElementById('new_meeting_minutes').value = window.newMeetingEditor.getData(); } catch(e) {}
            });
        }
    });
</script>
@endsection
