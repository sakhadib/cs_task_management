@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">Team — {{ $team->name }}</h1>
                <p class="mt-2 text-sm text-gray-600">Members and management for <strong>{{ $team->name }}</strong>.</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="openAssignMemberModal()" class="inline-flex items-center px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Assign Member</button>
                <a href="{{ route('teams.index') }}" class="text-indigo-600 hover:text-indigo-900">Back to Teams</a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-50 border-l-4 border-green-400 text-green-700">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-400 text-red-700">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-400 text-red-700">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($users as $user)
                            @php $isLead = isset($user->pivot) && $user->pivot->is_team_lead; @endphp
                            <tr class="hover:bg-gray-50 {{ $isLead ? 'bg-indigo-50' : '' }}">
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="flex items-center">
                                        <div class="ml-0">
                                            <div class="font-medium text-sm inline-flex items-center">
                                                {{ $user->name }}
                                                @if($isLead)
                                                    <i class="fas fa-crown text-yellow-500 ml-2" title="Team Lead"></i>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $user->email }}</td>
                                <td class="px-6 py-4 text-sm text-right">
                                    <div class="inline-flex items-center justify-end space-x-3">
                                        @if(!$isLead)
                                        <form method="POST" action="{{ route('teams.makeLead', ['team' => $team->id, 'user' => $user->id]) }}">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-indigo-600 text-white text-sm rounded">Make Lead</button>
                                        </form>
                                        @else
                                        <span class="px-3 py-1 bg-gray-200 text-gray-700 text-sm rounded">Lead</span>
                                        @endif

                                        <button type="button" class="text-red-600 hover:text-red-800" onclick="openRemoveMemberModal({{ $team->id }}, {{ $user->id }}, '{{ addslashes($user->name) }}')">Remove</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if(count($users) == 0)
                <div class="text-center py-10">
                    <p class="text-gray-500 text-sm">No members found for this team.</p>
                </div>
            @endif
        </div>

        <!-- Remove Member Modal -->
        <div id="removeMemberModal" class="fixed inset-0 z-50 hidden items-center justify-center">
            <div class="fixed inset-0 bg-black/50" aria-hidden="true" onclick="closeRemoveMemberModal()"></div>
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 z-10 overflow-hidden">
                <div class="px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">Remove Member</h3>
                    <p class="mt-2 text-sm text-gray-600">Are you sure you want to remove <span id="removeMemberName" class="font-medium"></span> from this team?</p>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-100">
                    <button type="button" onclick="closeRemoveMemberModal()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50">Cancel</button>
                    <form id="removeMemberForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700">Remove</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Assign Member Modal -->
    <div id="assignMemberModal" class="fixed inset-0 z-50 hidden items-center justify-center">
        <div class="fixed inset-0 bg-black/50" aria-hidden="true" onclick="closeAssignMemberModal()"></div>
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 z-10 overflow-hidden">
            <div class="px-6 py-4">
                <h3 class="text-lg font-semibold text-gray-900">Assign Member to {{ $team->name }}</h3>
                <p class="mt-2 text-sm text-gray-600">Search students by name or student ID and assign them to this team.</p>
            </div>
            <form id="assignMemberForm" method="POST" action="{{ route('teams.addUser', $team->id) }}">
                @csrf
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label for="assign_user_search" class="block text-sm font-medium text-gray-700">Search student by name or student ID</label>
                        <input id="assign_user_search" type="search" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Start typing name or student id...">
                        <div id="assignSearchResults" class="mt-2 bg-white border border-gray-200 rounded max-h-48 overflow-auto hidden"></div>
                        <input type="hidden" name="user_id" id="assign_selected_user_id">
                        <div id="assignSelectedUser" class="mt-2 text-sm text-gray-700"></div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-100">
                    <button type="button" onclick="closeAssignMemberModal()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700">Assign</button>
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
    }

    function closeRemoveMemberModal() {
        const modal = document.getElementById('removeMemberModal');
        if (!modal) return;
        modal.classList.remove('flex');
        modal.classList.add('hidden');
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
        setTimeout(() => { if (assignUserSearch) assignUserSearch.focus(); }, 50);
    }

    function closeAssignMemberModal() {
        if (!assignModal) return;
        assignModal.classList.remove('flex');
        assignModal.classList.add('hidden');
        if (assignSearchResults) { assignSearchResults.classList.add('hidden'); assignSearchResults.innerHTML = ''; }
        if (assignUserSearch) assignUserSearch.value = '';
        if (assignSelectedUserId) assignSelectedUserId.value = '';
        if (assignSelectedUser) assignSelectedUser.innerHTML = '';
    }

    function renderAssignSearchResults(items) {
        if (!items || items.length === 0) {
            assignSearchResults.innerHTML = '<div class="p-3 text-sm text-gray-500">No results</div>';
            assignSearchResults.classList.remove('hidden');
            return;
        }
        assignSearchResults.innerHTML = items.map(u => `
            <button type="button" class="w-full text-left px-3 py-2 hover:bg-gray-100" data-id="${u.id}" data-name="${u.name}" data-studentid="${u.student_id}">
                <div class="text-sm font-medium text-gray-900">${u.name}</div>
                <div class="text-xs text-gray-500">${u.student_id} — ${u.email}</div>
            </button>
        `).join('');
        assignSearchResults.classList.remove('hidden');

        Array.from(assignSearchResults.children).forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const sid = this.getAttribute('data-studentid');
                assignSelectedUserId.value = id;
                assignSelectedUser.innerHTML = `<div class="text-sm font-medium">${name}</div><div class="text-xs text-gray-500">${sid}</div>`;
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
</script>
@endsection
