@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tasks</h1>
                <p class="mt-1 text-sm text-gray-500">All tasks</p>
            </div>
            <div>
                @if(auth()->check() && auth()->user()->role !== 'member')
                    <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700">New Task</a>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 p-6">
            <div class="mb-4 flex flex-wrap items-center gap-2">
                <a href="{{ route('tasks.index') }}" class="px-3 py-1 rounded-full text-sm {{ empty(request('state')) ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700' }}">All <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-white text-gray-700">{{ $totalCount ?? 0 }}</span></a>
                @foreach($states as $s)
                    @php $c = $counts[$s] ?? 0; @endphp
                    <a href="{{ route('tasks.index', ['state' => $s]) }}" class="px-3 py-1 rounded-full text-sm {{ (request('state') === $s) ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700' }}">{{ ucfirst($s) }} <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-white text-gray-700">{{ $c }}</span></a>
                @endforeach
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">State</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Team</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assigned to</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created At</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($tasks as $task)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $task->title }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $task->state }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $task->team->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ optional($task->user)->name ?: 'Unassigned' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ optional($task->created_at)->format('M j, Y g:ia') ?? '-' }}</td>
                                   <td class="px-6 py-4 text-sm text-right">
                                       <a href="{{ route('tasks.show', $task->id) }}" class="inline-flex items-center px-3 py-1 mr-2 text-sm font-medium rounded text-indigo-600 hover:text-indigo-900">View</a>
                                       @if(auth()->check() && auth()->user()->role !== 'member')
                                           <button data-task='@json($task)' class="text-green-600 hover:text-green-900 btn-open-edit">Edit</button>
                                        <form id="delete-task-{{ $task->id }}" method="POST" action="{{ route('tasks.destroy', $task->id) }}" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" data-title="{{ $task->title }}" onclick="openDeleteModal('delete-task-{{ $task->id }}', this.dataset.title)" class="px-3 py-1 bg-red-600 text-white rounded">Delete</button>
                                        </form>
                                       @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">No tasks found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $tasks->appends(request()->only('state'))->links() }}
            </div>
        </div>
    </div>

<!-- Edit Modal (hidden) -->
<div id="editTaskModal" class="fixed z-50 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Edit Task</h3>
                <div class="mt-2">
                    <form id="editTaskForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" name="title" id="edit_title" class="mt-1 block w-full border border-gray-300 rounded-md p-2" required />
                        </div>
                        <div class="mt-3">
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="edit_description" class="mt-1 block w-full border border-gray-300 rounded-md p-2"></textarea>
                        </div>
                        <div class="mt-3">
                            <label class="block text-sm font-medium text-gray-700">Team</label>
                            <select name="team_id" id="edit_team_id" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                                <option value="">-- None --</option>
                                @foreach($teams as $team)
                                    <option value="{{ $team->id }}">{{ $team->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-4 flex justify-end space-x-2">
                            <button type="button" id="editCancel" class="bg-white py-2 px-4 border border-gray-300 rounded-md">Cancel</button>
                            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('click', function(e){
        if (e.target && e.target.classList.contains('btn-open-edit')){
            const task = JSON.parse(e.target.getAttribute('data-task'));
            const modal = document.getElementById('editTaskModal');
            document.getElementById('edit_title').value = task.title || '';
            document.getElementById('edit_description').value = task.description || '';
            document.getElementById('edit_team_id').value = task.team_id || '';
            const form = document.getElementById('editTaskForm');
            form.action = '/tasks/' + task.id;
            modal.classList.remove('hidden');
        }
    });

    document.getElementById('editCancel').addEventListener('click', function(){
        document.getElementById('editTaskModal').classList.add('hidden');
    });

    document.getElementById('editTaskForm').addEventListener('submit', function(e){
        // allow normal submit to server (full page reload)
    });
</script>
</div>
@endsection

@section('scripts')
<script>
    function openDeleteModal(formId, title) {
        let modal = document.getElementById('deleteTaskModal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'deleteTaskModal';
            modal.className = 'fixed inset-0 z-50 hidden items-center justify-center';
            modal.innerHTML = `
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeDeleteModal()"></div>
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 z-10 overflow-hidden transform transition-all">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-900">Confirm Delete</h3>
                        <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <div class="px-6 py-6">
                        <p class="text-sm text-gray-700">Are you sure you want to delete <span id="deleteTargetTitle" class="font-semibold"></span>? This action cannot be undone.</p>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-100">
                        <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50">Cancel</button>
                        <button id="deleteConfirmBtn" type="button" class="px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700">Delete</button>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }

        document.getElementById('deleteTargetTitle').textContent = title || 'this task';
        const confirmBtn = document.getElementById('deleteConfirmBtn');
        confirmBtn.onclick = function() {
            const form = document.getElementById(formId);
            if (form) form.submit();
        };

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteTaskModal');
        if (!modal) return;
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>
@endsection
