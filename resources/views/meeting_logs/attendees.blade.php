@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Mark Attendees — {{ \Carbon\Carbon::parse($log->scheduled_at)->format('M j, Y g:ia') }}</h1>
                <p class="mt-1 text-sm text-gray-500">Select users to mark as attendees for this meeting.</p>
            </div>
            <div>
                <a href="{{ route('meeting_logs.show', $log->id) }}" class="px-3 py-2 bg-gray-100 text-gray-800 rounded">Back to Meeting</a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-50 border-l-4 border-green-400 text-green-700">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-400 text-red-700">{{ session('error') }}</div>
        @endif

        <div class="bg-white rounded-xl shadow overflow-hidden border border-gray-100 p-6">
            <form id="addAttendeeForm" method="POST" action="{{ route('meeting_logs.attendees.store', $log->id) }}">
                @csrf
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Search user by name or student ID</label>
                        <input id="user_search" type="search" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Start typing name or student id...">
                        <div id="searchResults" class="mt-2 bg-white border border-gray-200 rounded max-h-48 overflow-auto hidden"></div>
                        <input type="hidden" name="user_id" id="selected_user_id">
                        <div id="selectedUser" class="mt-2 text-sm text-gray-700"></div>
                    </div>
                </div>
            </form>

            <div class="mt-6">
                <h3 class="text-sm font-medium text-gray-600 mb-3">Attendees</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($attendees as $a)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $a->user->student_id ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $a->user->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $a->user->email ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-right">
                                        <form id="unmark-form-{{ $a->id }}" method="POST" action="{{ route('meeting_logs.attendees.destroy', [$log->id, $a->id]) }}" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" data-name="{{ $a->user->name ?? 'attendee' }}" onclick="openUnmarkModal('unmark-form-{{ $a->id }}', this.dataset.name)" class="px-3 py-1 bg-red-600 text-white rounded">Unmark</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">No attendees yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const userSearch = document.getElementById('user_search');
    const searchResults = document.getElementById('searchResults');
    const selectedUserId = document.getElementById('selected_user_id');
    const selectedUser = document.getElementById('selectedUser');
    let searchTimeout = null;

    function renderSearchResults(items) {
        if (!items || items.length === 0) {
            searchResults.innerHTML = '<div class="p-3 text-sm text-gray-500">No results</div>';
            searchResults.classList.remove('hidden');
            return;
        }
        searchResults.innerHTML = items.map(u => `
            <button type="button" onclick="addAttendee(${u.id}, this)" class="w-full text-left px-3 py-2 hover:bg-gray-100" data-id="${u.id}" data-name="${u.name}" data-studentid="${u.student_id}" data-email="${u.email}">
                <div class="text-sm font-medium text-gray-900">${u.name}</div>
                <div class="text-xs text-gray-500">${u.student_id} — ${u.email}</div>
            </button>
        `).join('');
        searchResults.classList.remove('hidden');

        // keep buttons interactive but handled via onclick=addAttendee
    }

    function doUserSearch(q) {
        if (!q) { searchResults.classList.add('hidden'); searchResults.innerHTML = ''; return; }
        fetch(`{{ url('/users') }}?search=${encodeURIComponent(q)}`, { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => renderSearchResults(data))
            .catch(err => console.error('User search error', err));
    }

    userSearch && userSearch.addEventListener('input', function(e) {
        const q = e.target.value.trim();
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => doUserSearch(q), 250);
    });

    // Instant add: click a search result to post immediately
    function addAttendee(userId, btn) {
        if (!userId) return;
        const form = document.getElementById('addAttendeeForm');
        const url = form.action;
        // get CSRF token from the form
        const tokenEl = form.querySelector('input[name="_token"]');
        const token = tokenEl ? tokenEl.value : null;
        if (!token) { alert('CSRF token missing'); return; }

        // disable button and show feedback
        btn.disabled = true;
        const oldText = btn.innerHTML;
        btn.innerHTML = 'Adding...';

        const data = new FormData();
        data.append('_token', token);
        data.append('user_id', userId);

        fetch(url, { method: 'POST', body: data, credentials: 'same-origin' })
            .then(resp => {
                if (!resp.ok) throw new Error('Failed to add attendee');
                return resp.text();
            })
            .then(() => {
                // reload to show updated attendee list
                window.location.reload();
            })
            .catch(err => {
                console.error(err);
                alert('Could not add attendee.');
                btn.disabled = false;
                btn.innerHTML = oldText;
            });
    }

    // Unmark modal handling
    function openUnmarkModal(formId, name) {
        let modal = document.getElementById('unmarkModal');
        if (!modal) {
            // create modal
            modal = document.createElement('div');
            modal.id = 'unmarkModal';
            modal.className = 'fixed inset-0 z-50 hidden items-center justify-center';
            modal.innerHTML = `
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeUnmarkModal()"></div>
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 z-10 overflow-hidden transform transition-all">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-900">Confirm Unmark</h3>
                        <button onclick="closeUnmarkModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <div class="px-6 py-6">
                        <p class="text-sm text-gray-700">Are you sure you want to unmark <span id="unmarkTargetName" class="font-semibold"></span> as an attendee? This cannot be undone.</p>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-100">
                        <button type="button" onclick="closeUnmarkModal()" class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50">Cancel</button>
                        <button id="unmarkConfirmBtn" type="button" class="px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700">Unmark</button>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }

        // set name and attach handler
        document.getElementById('unmarkTargetName').textContent = name || 'attendee';
        const confirmBtn = document.getElementById('unmarkConfirmBtn');
        // remove previous handler
        confirmBtn.onclick = function() {
            const form = document.getElementById(formId);
            if (form) form.submit();
        };

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeUnmarkModal() {
        const modal = document.getElementById('unmarkModal');
        if (!modal) return;
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>

@endsection
