@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8 animate-fade-in">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('panels.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Panels
                </a>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900">{{ $panel->name }} Positions</h1>
            <p class="mt-2 text-gray-600">Manage panel positions and assignments</p>
        </div>

        {{-- Positions Section --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="px-6 py-5 bg-gray-800 flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-xl font-bold text-white">Panel Positions</h2>
                        <p class="text-gray-300 text-sm">{{ count($positions) }} position(s) assigned</p>
                    </div>
                </div>
                <button type="button" onclick="openAddPositionModal()" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-semibold text-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Position
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Student ID</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Position</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Level</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($positions as $pos)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 text-sm text-gray-600 font-medium">{{ $pos->user->student_id ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 font-semibold">{{ $pos->user->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $pos->position }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-200 text-gray-800 border-2 border-gray-300">
                                        Level {{ $pos->level }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-right space-x-3">
                                    <button type="button" onclick="openEditPositionModal({{ $pos->id }}, '{{ addslashes($pos->position) }}', {{ $pos->level }})" class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-medium text-xs">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit
                                    </button>
                                    <button type="button" data-pos-id="{{ $pos->id }}" data-pos-user-name="{{ $pos->user->name ?? '' }}" onclick="openPositionDeleteModal(this)" class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-medium text-xs">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Remove
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="inline-block bg-gray-100 rounded-full p-8 mb-4">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 text-lg font-medium">No positions assigned yet</p>
                                    <p class="text-gray-400 text-sm mt-2">Add a position to get started</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Position Modal -->
<div id="addPositionModal" class="fixed inset-0 z-50 hidden items-center justify-center backdrop-blur-sm">
    <div class="fixed inset-0 bg-black/50 transition-opacity" aria-hidden="true" onclick="closeAddPositionModal()"></div>
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 z-10 overflow-hidden transform transition-all">
        <div class="px-6 py-5 bg-gray-800">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-xl font-bold text-white">Add Position</h3>
                    <p class="text-gray-300 text-sm mt-0.5">Assign a new position to {{ $panel->name }}</p>
                </div>
            </div>
        </div>
        <form id="addPositionForm" action="{{ route('panels.positions.store', $panel->id) }}" method="POST">
            @csrf
            <div class="p-6 space-y-5">
                <div>
                    <label for="user_search" class="block text-sm font-bold text-gray-700 mb-2">Search Student *</label>
                    <div class="relative">
                        <svg class="w-5 h-5 absolute left-3 top-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input id="user_search" type="search" class="w-full pl-10 pr-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-all text-sm" placeholder="Search by name or student ID...">
                    </div>
                    <div id="searchResults" class="mt-2 bg-white border-2 border-gray-200 rounded-xl max-h-48 overflow-auto hidden divide-y divide-gray-100"></div>
                    <input type="hidden" name="user_id" id="selected_user_id">
                    <div id="selectedUser" class="mt-2 text-sm"></div>
                </div>

                <div>
                    <label for="position" class="block text-sm font-bold text-gray-700 mb-2">Position Title *</label>
                    <input id="position" name="position" type="text" required class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-all text-sm" placeholder="e.g., President, Secretary">
                </div>

                <div>
                    <label for="level" class="block text-sm font-bold text-gray-700 mb-2">Level *</label>
                    <input id="level" name="level" type="number" min="0" required class="block w-32 px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-all text-sm" placeholder="0">
                    <p class="mt-1 text-xs text-gray-500">Higher levels indicate more authority</p>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-200">
                <button type="button" onclick="closeAddPositionModal()" class="inline-flex items-center px-5 py-2.5 border-2 border-gray-300 text-sm font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">Cancel</button>
                <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-semibold rounded-xl text-white bg-gray-800 hover:bg-gray-900 transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Save Position
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const addModal = document.getElementById('addPositionModal');
    const userSearch = document.getElementById('user_search');
    const searchResults = document.getElementById('searchResults');
    const selectedUserId = document.getElementById('selected_user_id');
    const selectedUser = document.getElementById('selectedUser');
    let searchTimeout = null;

    function openAddPositionModal() {
        addModal.classList.remove('hidden');
        addModal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        userSearch.focus();
    }
    function closeAddPositionModal() {
        addModal.classList.remove('flex');
        addModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        searchResults.classList.add('hidden');
        searchResults.innerHTML = '';
        userSearch.value = '';
        selectedUserId.value = '';
        selectedUser.innerHTML = '';
    }

    function renderSearchResults(items) {
        if (!items || items.length === 0) {
            searchResults.innerHTML = '<div class="p-4 text-center text-sm text-gray-500">No results found</div>';
            searchResults.classList.remove('hidden');
            return;
        }
        searchResults.innerHTML = items.map(u => `
            <button type="button" class="w-full text-left px-4 py-3 hover:bg-gray-100 transition-colors group flex items-center justify-between" data-id="${u.id}" data-name="${u.name}" data-studentid="${u.student_id}">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-700 font-bold text-xs mr-3">
                        ${u.name.charAt(0).toUpperCase()}
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-gray-900">${u.name}</div>
                        <div class="text-xs text-gray-500">${u.student_id} • ${u.email}</div>
                    </div>
                </div>
                <svg class="w-5 h-5 text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        `).join('');
        searchResults.classList.remove('hidden');

        // attach click handlers
        Array.from(searchResults.children).forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const sid = this.getAttribute('data-studentid');
                selectedUserId.value = id;
                selectedUser.innerHTML = `
                    <div class="bg-gray-100 border-2 border-gray-300 rounded-lg p-3">
                        <div class="text-sm font-bold text-gray-900">${name}</div>
                        <div class="text-xs text-gray-600">${sid}</div>
                    </div>
                `;
                searchResults.classList.add('hidden');
            });
        });
    }

    // Use existing users endpoint which supports JSON via ?search=
    function doUserSearch(q) {
        if (!q) { searchResults.classList.add('hidden'); searchResults.innerHTML = ''; return; }
        fetch(`{{ url('/users') }}?search=${encodeURIComponent(q)}`, { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => {
                renderSearchResults(data);
            }).catch(err => {
                console.error('User search error', err);
            });
    }

    userSearch.addEventListener('input', function(e) {
        const q = e.target.value.trim();
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => doUserSearch(q), 250);
    });

    // Prevent form submit without selecting a user
    document.getElementById('addPositionForm').addEventListener('submit', function(e) {
        if (!selectedUserId.value) {
            e.preventDefault();
            alert('Please select a student from search results before saving.');
        }
    });

    // Edit Position Modal handling
    function openEditPositionModal(id, positionText, levelVal) {
        // Create or show modal dynamically
        let modal = document.getElementById('editPositionModal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'editPositionModal';
            modal.className = 'fixed inset-0 z-50 hidden items-center justify-center backdrop-blur-sm';
            modal.innerHTML = `
                <div class="fixed inset-0 bg-black/50 transition-opacity" aria-hidden="true" onclick="closeEditPositionModal()"></div>
                <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 z-10 overflow-hidden transform transition-all">
                    <div class="px-6 py-5 bg-gray-800">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-xl font-bold text-white">Edit Position</h3>
                                <p class="text-gray-300 text-sm mt-0.5">Update position details</p>
                            </div>
                        </div>
                    </div>
                    <form id="editPositionForm" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <div class="p-6 space-y-5">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Position Title *</label>
                                <input name="position" id="edit_position_input" type="text" required class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-all text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Level *</label>
                                <input name="level" id="edit_level_input" type="number" min="0" required class="block w-32 px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-all text-sm">
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-200">
                            <button type="button" onclick="closeEditPositionModal()" class="inline-flex items-center px-5 py-2.5 border-2 border-gray-300 text-sm font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">Cancel</button>
                            <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-semibold rounded-xl text-white bg-gray-800 hover:bg-gray-900 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            `;
            document.body.appendChild(modal);
        }

        // set values
        document.getElementById('edit_position_input').value = positionText;
        document.getElementById('edit_level_input').value = levelVal;
        const form = document.getElementById('editPositionForm');
        form.action = `/positions/${id}`;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeEditPositionModal() {
        const modal = document.getElementById('editPositionModal');
        if (!modal) return;
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Position delete modal
    function openPositionDeleteModal(btn) {
        const id = btn.getAttribute('data-pos-id');
        const name = btn.getAttribute('data-pos-user-name');
        let modal = document.getElementById('positionDeleteModal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'positionDeleteModal';
            modal.className = 'fixed inset-0 z-50 hidden items-center justify-center backdrop-blur-sm';
            modal.innerHTML = `
                <div class="fixed inset-0 bg-black/50 transition-opacity" aria-hidden="true" onclick="closePositionDeleteModal()"></div>
                <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 z-10 overflow-hidden">
                    <div class="px-6 py-5 bg-gray-800">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-xl font-bold text-white">Remove Position</h3>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-600">Are you sure you want to remove the position for <span id="posDeleteName" class="font-bold text-gray-900"></span>? This action cannot be undone.</p>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3 border-t border-gray-200">
                        <button type="button" onclick="closePositionDeleteModal()" class="inline-flex items-center px-5 py-2.5 border-2 border-gray-300 text-sm font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">Cancel</button>
                        <form id="positionDeleteForm" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-semibold rounded-xl text-white bg-gray-900 hover:bg-black transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Remove Position
                            </button>
                        </form>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }

        document.getElementById('posDeleteName').textContent = name;
        document.getElementById('positionDeleteForm').action = `/positions/${id}`;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closePositionDeleteModal() {
        const modal = document.getElementById('positionDeleteModal');
        if (!modal) return;
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // ESC key to close modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAddPositionModal();
            closeEditPositionModal();
            closePositionDeleteModal();
        }
    });
</script>
@endsection
