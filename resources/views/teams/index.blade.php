@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">Teams</h1>
                <p class="mt-2 text-sm text-gray-600">Manage teams for the currently selected panel.</p>
            </div>
            <div class="flex items-center gap-3">
                @if($currentPanel)
                <button onclick="openNewTeamModal()" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">+ New Team</button>
                <a href="{{ route('panels.index') }}" class="text-indigo-600 hover:text-indigo-900">Panels</a>
                @else
                <button disabled class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 rounded-lg">+ New Team</button>
                @endif
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

        @if(!$currentPanel)
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                <p class="text-yellow-700">No current panel selected. Set a current panel from the Panels page.</p>
            </div>
        @else
            <h3 class="text-lg font-medium text-gray-700 mb-4">Teams for panel: <span class="font-semibold">{{ $currentPanel->name ?? 'Panel #' . $currentPanel->id }}</span></h3>

            <!-- New Team Modal -->
            <div id="newTeamModal" class="fixed inset-0 z-50 hidden items-center justify-center">
                <div class="fixed inset-0 bg-black/50" aria-hidden="true" onclick="closeNewTeamModal()"></div>
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 z-10 overflow-hidden">
                    <form id="newTeamForm" method="POST" action="{{ route('teams.store') }}">
                        @csrf
                        <div class="px-6 py-4">
                            <h3 class="text-lg font-semibold text-gray-900">Create Team</h3>
                        </div>
                        <div class="px-6 py-4 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Name</label>
                                <input name="name" type="text" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg"></textarea>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-100">
                            <button type="button" onclick="closeNewTeamModal()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50">Cancel</button>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700">Create</button>
                        </div>
                    </form>
                </div>
            </div>


            @if($teams->isEmpty())
                <div class="text-gray-600">No teams have been assigned to this panel yet.</div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($teams as $team)
                        @php
                            $lead = null;
                            if (isset($team->users) && $team->users->count()) {
                                $lead = $team->users->firstWhere('pivot.is_team_lead', true);
                            }
                        @endphp
                        <div class="bg-white rounded-lg shadow p-4">
                            <div>
                                <h4 class="text-lg font-semibold text-gray-800">{{ $team->name }}</h4>
                                @if(isset($team->description))
                                    <p class="text-sm text-gray-500 mt-1">{{ Str::limit($team->description, 120) }}</p>
                                @endif

                                <div class="mt-3 text-sm text-gray-500 flex items-center justify-between">
                                    <div>
                                        <span class="mr-3">Members: {{ isset($team->users) ? $team->users->count() : '—' }}</span>
                                        @if($lead)
                                            <span class="">Lead: <span class="font-medium text-gray-700">{{ $lead->name }}</span></span>
                                        @else
                                            <span class="text-sm text-gray-500">No team lead</span>
                                        @endif
                                    </div>
                                </div>

                                <hr class="my-4">

                                <div class="flex items-center justify-between">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('teams.show', $team->id) }}" class="inline-flex items-center px-3 py-1 border text-sm" style="background-color: rgba(99,102,241,0.12); border-color: rgb(99,102,241); color: rgb(79,70,229); border-radius:5px">View Team</a>
                                            <button onclick="openEditTeamModal({{ $team->id }}, '{{ addslashes($team->name) }}', `{{ addslashes($team->description ?? '') }}`)" class="inline-flex items-center px-3 py-1 border text-sm" style="background-color: rgba(250,204,21,0.12); border-color: rgb(250,204,21); color: rgb(162,124,0); border-radius:5px">Edit Team</button>
                                    </div>
                                    <div>
                                        <button onclick="openDeleteTeamModal({{ $team->id }}, '{{ addslashes($team->name) }}')" class="inline-flex items-center px-3 py-1 border text-sm" style="background-color: rgba(220,38,38,0.08); border-color: rgb(220,38,38); color: rgb(185,28,28); border-radius:5px">Delete Team</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openNewTeamModal() {
        const modal = document.getElementById('newTeamModal');
        if (!modal) return;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeNewTeamModal() {
        const modal = document.getElementById('newTeamModal');
        if (!modal) return;
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

    // Edit Team modal (dynamic)
    function openEditTeamModal(id, name, description) {
        let modal = document.getElementById('editTeamModal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'editTeamModal';
            modal.className = 'fixed inset-0 z-50 hidden items-center justify-center';
            modal.innerHTML = `
                <div class="fixed inset-0 bg-black/50" aria-hidden="true" onclick="closeEditTeamModal()"></div>
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 z-10 overflow-hidden">
                    <form id="editTeamForm" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <div class="px-6 py-4">
                            <h3 class="text-lg font-semibold text-gray-900">Edit Team</h3>
                        </div>
                        <div class="px-6 py-4 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Name</label>
                                <input name="name" id="edit_team_name" type="text" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="edit_team_description" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg"></textarea>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-100">
                            <button type="button" onclick="closeEditTeamModal()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50">Cancel</button>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700">Save</button>
                        </div>
                    </form>
                </div>
            `;
            document.body.appendChild(modal);
        }

        document.getElementById('edit_team_name').value = name || '';
        document.getElementById('edit_team_description').value = description || '';
        const form = document.getElementById('editTeamForm');
        form.action = `/teams/${id}`;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeEditTeamModal() {
        const modal = document.getElementById('editTeamModal');
        if (!modal) return;
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

    // Delete Team modal (requires typing 'delete')
    function openDeleteTeamModal(id, name) {
        let modal = document.getElementById('deleteTeamModal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'deleteTeamModal';
            modal.className = 'fixed inset-0 z-50 hidden items-center justify-center';
            modal.innerHTML = `
                <div class="fixed inset-0 bg-black/50" aria-hidden="true" onclick="closeDeleteTeamModal()"></div>
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 z-10 overflow-hidden">
                    <div class="px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-900">Delete Team</h3>
                        <p class="mt-2 text-sm text-gray-600">Type <span class="font-medium">delete</span> to confirm deleting <span id="deleteTeamName" class="font-medium"></span>.</p>
                    </div>
                    <div class="px-6 py-4 space-y-3">
                        <input id="deleteConfirmInput" type="text" placeholder="Type delete to confirm" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-100">
                        <button type="button" onclick="closeDeleteTeamModal()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50">Cancel</button>
                        <form id="deleteTeamForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <button id="deleteTeamButton" type="submit" disabled class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-red-600 opacity-50">Delete</button>
                        </form>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);

            // enable/disable delete button based on input
            modal.querySelector('#deleteConfirmInput').addEventListener('input', function(e) {
                const btn = modal.querySelector('#deleteTeamButton');
                if (e.target.value.trim().toLowerCase() === 'delete') {
                    btn.disabled = false;
                    btn.classList.remove('opacity-50');
                } else {
                    btn.disabled = true;
                    btn.classList.add('opacity-50');
                }
            });
        }

        document.getElementById('deleteTeamName').textContent = name;
        const form = document.getElementById('deleteTeamForm');
        form.action = `/teams/${id}`;
        document.getElementById('deleteConfirmInput').value = '';
        const btn = document.getElementById('deleteTeamButton');
        btn.disabled = true; btn.classList.add('opacity-50');

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDeleteTeamModal() {
        const modal = document.getElementById('deleteTeamModal');
        if (!modal) return;
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }
</script>
@endsection
