@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="bg-gray-800 rounded-2xl shadow-xl mb-6 overflow-hidden">
            <div class="px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-white">Mark Attendees</h1>
                            <p class="mt-1 text-gray-300 text-sm">{{ \Carbon\Carbon::parse($log->scheduled_at)->format('M j, Y g:ia') }}</p>
                        </div>
                    </div>
                    <a href="{{ route('meeting_logs.show', $log->id) }}" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-xl transition-all font-medium border-2 border-white/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>Back to Meeting</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 rounded-r-xl shadow-sm overflow-hidden">
                <div class="flex items-start p-4">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 rounded-r-xl shadow-sm overflow-hidden">
                <div class="flex items-start p-4">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Add Attendee Section --}}
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200 mb-6">
            <div class="bg-gray-800 px-6 py-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white">Search & Add Attendee</h3>
                </div>
            </div>
            <form id="addAttendeeForm" method="POST" action="{{ route('meeting_logs.attendees.store', $log->id) }}" class="p-6">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search user by name or student ID</label>
                    <input id="user_search" type="search" class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-all text-sm" placeholder="Start typing name or student id...">
                    <div id="searchResults" class="mt-2 bg-white border-2 border-gray-200 rounded-xl max-h-64 overflow-auto hidden shadow-lg"></div>
                    <input type="hidden" name="user_id" id="selected_user_id">
                    <div id="selectedUser" class="mt-2 text-sm text-gray-700"></div>
                </div>
            </form>
        </div>

        {{-- Attendees List --}}
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
            <div class="bg-gray-800 px-6 py-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">Attendees List</h3>
                        <p class="text-gray-300 text-sm">{{ count($attendees) }} attendee(s) marked</p>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Student ID</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($attendees as $a)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $a->user->student_id ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                                            <span class="text-xs font-medium text-gray-700">{{ substr($a->user->name ?? '?', 0, 1) }}</span>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">{{ $a->user->name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $a->user->email ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-right">
                                    <form id="unmark-form-{{ $a->id }}" method="POST" action="{{ route('meeting_logs.attendees.destroy', [$log->id, $a->id]) }}" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" data-name="{{ $a->user->name ?? 'attendee' }}" onclick="openUnmarkModal('unmark-form-{{ $a->id }}', this.dataset.name)" class="inline-flex items-center space-x-1 px-3 py-1.5 bg-gray-900 hover:bg-black text-white rounded-lg transition-colors text-xs font-medium">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            <span>Unmark</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-3">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium text-gray-900">No attendees yet</p>
                                        <p class="text-xs text-gray-500 mt-1">Search and add attendees above</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
            searchResults.innerHTML = '<div class="p-4 text-sm text-gray-500 text-center">No results found</div>';
            searchResults.classList.remove('hidden');
            return;
        }
        searchResults.innerHTML = items.map(u => `
            <div class="flex items-center justify-between p-3 hover:bg-gray-50 transition-colors border-b border-gray-100 last:border-b-0">
                <div class="flex items-center space-x-3 flex-1">
                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0">
                        <span class="text-sm font-medium text-gray-700">${u.name.charAt(0).toUpperCase()}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-gray-900 truncate">${u.name}</div>
                        <div class="text-xs text-gray-500">${u.student_id} • ${u.email}</div>
                    </div>
                </div>
                <button type="button" onclick="addAttendee(${u.id}, this)" class="inline-flex items-center space-x-1 px-3 py-1.5 bg-gray-800 hover:bg-black text-white rounded-lg transition-colors text-xs font-medium ml-3 flex-shrink-0" data-id="${u.id}" data-name="${u.name}" data-studentid="${u.student_id}" data-email="${u.email}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Add</span>
                </button>
            </div>
        `).join('');
        searchResults.classList.remove('hidden');
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
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeUnmarkModal()"></div>
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 z-10 overflow-hidden transform transition-all">
                    <div class="bg-gray-800 px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-white">Confirm Unmark</h3>
                        </div>
                    </div>
                    <div class="px-6 py-5">
                        <p class="text-sm text-gray-600">Are you sure you want to unmark <span id="unmarkTargetName" class="font-semibold text-gray-900"></span> as an attendee?</p>
                        <p class="mt-2 text-xs text-gray-500">This action cannot be undone.</p>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-200">
                        <button type="button" onclick="closeUnmarkModal()" class="inline-flex items-center px-4 py-2.5 border-2 border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-colors">Cancel</button>
                        <button id="unmarkConfirmBtn" type="button" class="inline-flex items-center space-x-2 px-4 py-2.5 text-sm font-medium rounded-xl text-white bg-gray-900 hover:bg-black transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span>Unmark Attendee</span>
                        </button>
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
        document.body.style.overflow = '';
    }

    // ESC key support
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('unmarkModal');
            if (modal && !modal.classList.contains('hidden')) {
                closeUnmarkModal();
            }
        }
    });
</script>

@endsection
