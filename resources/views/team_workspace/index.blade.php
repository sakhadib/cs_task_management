@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-semibold">
            Team Workspace
            @if(request('team'))
                &mdash; {{ optional($teams->firstWhere('id', request('team')))->name }}
            @endif
        </h2>
        <div>
            <form method="GET" class="flex items-center space-x-2">
                <select name="team" onchange="this.form.submit()" class="px-3 py-2 rounded border">
                    <option value="">All Teams</option>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}" @if(request('team') == $team->id) selected @endif>{{ $team->name }}</option>
                    @endforeach
                </select>

                <select name="state" onchange="this.form.submit()" class="px-3 py-2 rounded border">
                    @foreach($states as $key => $label)
                        <option value="{{ $key }}" @if(request('state') == $key) selected @endif>{{ $label }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <div class="bg-white shadow rounded">
        <table class="min-w-full table-auto">
            <thead>
                <tr class="text-left">
                    <th class="px-4 py-2">Title</th>
                    <th class="px-4 py-2">State</th>
                    <th class="px-4 py-2">Assignee</th>
                    <th class="px-4 py-2">Team</th>
                    <th class="px-4 py-2">Created</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                <tr class="border-t">
                    <td class="px-4 py-3">{{ $task->title }}</td>
                    <td class="px-4 py-3">{{ ucfirst($task->state) }}</td>
                    <td class="px-4 py-3">{{ optional($task->user)->name ?? 'Unassigned' }}</td>
                    <td class="px-4 py-3">{{ optional($task->team)->name }}</td>
                    <td class="px-4 py-3">{{ $task->created_at->diffForHumans() }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('tasks.show', $task) }}" class="text-blue-600 mr-2">View</a>
                        @if($task->state !== 'completed' && in_array($task->team_id, $leadTeamIds ?? []))
                        <button class="px-3 py-1 bg-green-600 text-white rounded assign-btn" data-task-id="{{ $task->id }}" data-team-id="{{ $task->team_id }}">Assign</button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="p-4">No tasks found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $tasks->links() }}</div>

    <!-- Assign Modal -->
    <div id="assignModal" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center">
        <div class="bg-white rounded shadow-lg w-full max-w-lg p-6">
            <h3 class="text-lg font-semibold mb-3">Assign Task</h3>
            <div class="mb-3">
                <input id="memberSearch" type="text" class="w-full border px-3 py-2" placeholder="Search team members by name, email, or student id">
            </div>
            <div id="searchResults" class="space-y-2 max-h-60 overflow-y-auto mb-3"></div>

            <form id="assignForm" method="POST" action="">
                @csrf
                <input type="hidden" name="user_id" id="assign_user_id">
                <div class="flex justify-end space-x-2">
                    <button type="button" id="assignCancel" class="px-4 py-2 border rounded">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Assign</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const assignModal = document.getElementById('assignModal');
    const memberSearch = document.getElementById('memberSearch');
    const searchResults = document.getElementById('searchResults');
    const assignForm = document.getElementById('assignForm');
    const assignUserId = document.getElementById('assign_user_id');

    let currentTeamId = null;
    let currentTaskId = null;

    document.querySelectorAll('.assign-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            currentTaskId = btn.getAttribute('data-task-id');
            currentTeamId = btn.getAttribute('data-team-id');
            assignForm.action = '/team-workspace/' + currentTaskId + '/assign';
            memberSearch.value = '';
            searchResults.innerHTML = '';
            assignUserId.value = '';
            assignModal.classList.remove('hidden');
            assignModal.classList.add('flex');
            memberSearch.focus();
        });
    });

    document.getElementById('assignCancel').addEventListener('click', () => {
        assignModal.classList.add('hidden');
        assignModal.classList.remove('flex');
    });

    let timeout = null;
    memberSearch.addEventListener('input', () => {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            const q = memberSearch.value.trim();
            if (!currentTeamId) return;
            fetch('/team-workspace/team/' + currentTeamId + '/users?query=' + encodeURIComponent(q))
                .then(r => r.json())
                .then(data => {
                    searchResults.innerHTML = '';
                    data.forEach(u => {
                        const div = document.createElement('div');
                        div.className = 'p-2 border rounded cursor-pointer hover:bg-gray-100';
                        div.textContent = u.name + ' (' + (u.student_id || u.email) + ')';
                        div.addEventListener('click', () => {
                            assignUserId.value = u.id;
                            // highlight selection briefly
                            document.querySelectorAll('#searchResults > div').forEach(el => el.classList.remove('bg-blue-100'));
                            div.classList.add('bg-blue-100');
                        });
                        searchResults.appendChild(div);
                    });
                });
        }, 250);
    });
</script>
@endsection
