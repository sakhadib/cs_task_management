@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">Positions — {{ $panel->name }}</h1>
                <p class="mt-2 text-sm text-gray-600">Panel positions for <strong>{{ $panel->name }}</strong>.</p>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" onclick="openAddPositionModal()" class="inline-flex items-center px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Add Position</button>
                <a href="{{ route('panels.index') }}" class="text-indigo-600 hover:text-indigo-900">Back to Panels</a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Level</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($positions as $pos)
                            <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $pos->user->student_id ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $pos->user->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $pos->position }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $pos->level }}</td>
                                    <td class="px-6 py-4 text-sm text-right">
                                        <button type="button" class="text-indigo-600 hover:text-indigo-900" 
                                            onclick="openEditPositionModal({{ $pos->id }}, '{{ addslashes($pos->position) }}', {{ $pos->level }})">Edit</button>
                                        <button type="button" data-pos-id="{{ $pos->id }}" data-pos-user-name="{{ $pos->user->name ?? '' }}" onclick="openPositionDeleteModal(this)" class="text-red-600 hover:text-red-800 ml-4">Remove</button>
                                    </td>
                                </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if(count($positions) == 0)
                <div class="text-center py-10">
                    <p class="text-gray-500 text-sm">No positions found for this panel.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Position Modal -->
<div id="addPositionModal" class="fixed inset-0 z-50 hidden items-center justify-center">
    <div class="fixed inset-0 bg-black/50" aria-hidden="true" onclick="closeAddPositionModal()"></div>
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 z-10 overflow-hidden">
        <div class="px-6 py-4">
            <h3 class="text-lg font-semibold text-gray-900">Add Position to {{ $panel->name }}</h3>
            <p class="mt-2 text-sm text-gray-600">Search and select a student, then provide position and level.</p>
        </div>
        <form id="addPositionForm" action="{{ route('panels.positions.store', $panel->id) }}" method="POST">
            @csrf
            <div class="px-6 py-4 space-y-4">
                <div>
                    <label for="user_search" class="block text-sm font-medium text-gray-700">Search student by name or student ID</label>
                    <input id="user_search" type="search" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Start typing name or student id...">
                    <div id="searchResults" class="mt-2 bg-white border border-gray-200 rounded max-h-48 overflow-auto hidden"></div>
                    <input type="hidden" name="user_id" id="selected_user_id">
                    <div id="selectedUser" class="mt-2 text-sm text-gray-700"></div>
                </div>

                <div>
                    <label for="position" class="block text-sm font-medium text-gray-700">Position</label>
                    <input id="position" name="position" type="text" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                </div>

                <div>
                    <label for="level" class="block text-sm font-medium text-gray-700">Level</label>
                    <input id="level" name="level" type="number" min="0" class="mt-1 block w-32 px-3 py-2 border border-gray-300 rounded-lg" required>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-100">
                <button type="button" onclick="closeAddPositionModal()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50">Cancel</button>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700">Save Position</button>
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
        userSearch.focus();
    }
    function closeAddPositionModal() {
        addModal.classList.remove('flex');
        addModal.classList.add('hidden');
        searchResults.classList.add('hidden');
        searchResults.innerHTML = '';
        userSearch.value = '';
        selectedUserId.value = '';
        selectedUser.innerHTML = '';
    }

    function renderSearchResults(items) {
        if (!items || items.length === 0) {
            searchResults.innerHTML = '<div class="p-3 text-sm text-gray-500">No results</div>';
            searchResults.classList.remove('hidden');
            return;
        }
        searchResults.innerHTML = items.map(u => `
            <button type="button" class="w-full text-left px-3 py-2 hover:bg-gray-100" data-id="${u.id}" data-name="${u.name}" data-studentid="${u.student_id}">
                <div class="text-sm font-medium text-gray-900">${u.name}</div>
                <div class="text-xs text-gray-500">${u.student_id} — ${u.email}</div>
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
                selectedUser.innerHTML = `<div class="text-sm font-medium">${name}</div><div class="text-xs text-gray-500">${sid}</div>`;
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
            modal.className = 'fixed inset-0 z-50 hidden items-center justify-center';
            modal.innerHTML = `
                <div class="fixed inset-0 bg-black/50" aria-hidden="true" onclick="closeEditPositionModal()"></div>
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 z-10 overflow-hidden">
                    <form id="editPositionForm" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <div class="px-6 py-4">
                            <h3 class="text-lg font-semibold text-gray-900">Edit Position</h3>
                        </div>
                        <div class="px-6 py-4 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Position</label>
                                <input name="position" id="edit_position_input" type="text" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Level</label>
                                <input name="level" id="edit_level_input" type="number" min="0" class="mt-1 block w-32 px-3 py-2 border border-gray-300 rounded-lg" required>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-100">
                            <button type="button" onclick="closeEditPositionModal()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50">Cancel</button>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700">Save</button>
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
    }

    function closeEditPositionModal() {
        const modal = document.getElementById('editPositionModal');
        if (!modal) return;
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

    // Position delete modal
    function openPositionDeleteModal(btn) {
        const id = btn.getAttribute('data-pos-id');
        const name = btn.getAttribute('data-pos-user-name');
        let modal = document.getElementById('positionDeleteModal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'positionDeleteModal';
            modal.className = 'fixed inset-0 z-50 hidden items-center justify-center';
            modal.innerHTML = `
                <div class="fixed inset-0 bg-black/50" aria-hidden="true" onclick="closePositionDeleteModal()"></div>
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 z-10 overflow-hidden">
                    <div class="px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-900">Remove position</h3>
                        <p class="mt-2 text-sm text-gray-600">Remove position for <span id="posDeleteName" class="font-medium"></span>?</p>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                        <button type="button" onclick="closePositionDeleteModal()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50">Cancel</button>
                        <form id="positionDeleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700">Remove</button>
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
    }

    function closePositionDeleteModal() {
        const modal = document.getElementById('positionDeleteModal');
        if (!modal) return;
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }
</script>
@endsection
