@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8 animate-fade-in">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900">
                        Tasks
                    </h1>
                    <p class="mt-3 text-lg text-gray-600">Manage and track all project tasks</p>
                </div>
                @if(auth()->check() && auth()->user()->role !== 'member')
                <div class="flex items-center gap-3">
                    <button onclick="openCreateModal()" class="inline-flex items-center justify-center px-6 py-3 bg-gray-800 text-white rounded-xl hover:bg-gray-900 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl group">
                        <svg class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        New Task
                    </button>
                </div>
                @endif
            </div>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-6 rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 p-4 border-l-4 border-green-500 shadow-lg animate-fade-in">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Error Message --}}
        @if(session('error'))
            <div class="mb-6 rounded-xl bg-gradient-to-r from-red-50 to-red-100 p-4 border-l-4 border-red-500 shadow-lg animate-fade-in">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-semibold text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="mb-6 rounded-xl bg-gradient-to-r from-red-50 to-red-100 p-4 border-l-4 border-red-500 shadow-lg animate-fade-in">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-semibold text-red-800 mb-2">Please fix the following errors:</h3>
                        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Tasks Section -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="px-6 py-5 bg-gray-800">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-xl font-bold text-white">All Tasks</h2>
                            <p class="text-gray-300 text-sm">Filter by state and manage assignments</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- State Filter Tabs -->
            <div class="border-b border-gray-200 bg-gray-50">
                <div class="flex overflow-x-auto px-6">
                    <a href="{{ route('tasks.index') }}" class="px-4 py-3 text-sm font-semibold border-b-2 {{ empty(request('state')) ? 'border-gray-800 text-gray-800' : 'border-transparent text-gray-500 hover:text-gray-700' }} whitespace-nowrap transition-colors">
                        All
                        <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold bg-gray-200 text-gray-700 rounded-full">{{ $totalCount ?? 0 }}</span>
                    </a>
                    @foreach($states as $s)
                        @php 
                            $c = $counts[$s] ?? 0;
                            $stateLabels = [
                                'pending assignment' => 'Pending',
                                'team assigned' => 'Team',
                                'assigned to user' => 'Assigned',
                                'reassigned to user' => 'Reassigned',
                                'working' => 'In Progress',
                                'submitted to review' => 'Review',
                                'completed' => 'Done',
                            ];
                            $label = $stateLabels[$s] ?? ucfirst($s);
                        @endphp
                        <a href="{{ route('tasks.index', ['state' => $s]) }}" class="px-4 py-3 text-sm font-semibold border-b-2 {{ (request('state') === $s) ? 'border-gray-800 text-gray-800' : 'border-transparent text-gray-500 hover:text-gray-700' }} whitespace-nowrap transition-colors">
                            {{ $label }}
                            <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold bg-gray-200 text-gray-700 rounded-full">{{ $c }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Tasks Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">State</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Team</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Assigned to</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Created At</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($tasks as $task)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900">{{ $task->title }}</div>
                                    @if($task->description)
                                        <div class="text-sm text-gray-500 mt-1">{{ Str::limit($task->description, 60) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $stateColors = [
                                            'pending assignment' => 'bg-gray-100 text-gray-700 border-gray-300',
                                            'team assigned' => 'bg-gray-200 text-gray-800 border-gray-300',
                                            'assigned to user' => 'bg-gray-200 text-gray-800 border-gray-400',
                                            'reassigned to user' => 'bg-gray-200 text-gray-800 border-gray-400',
                                            'working' => 'bg-gray-700 text-white border-gray-700',
                                            'submitted to review' => 'bg-gray-300 text-gray-900 border-gray-400',
                                            'completed' => 'bg-gray-900 text-white border-gray-900',
                                        ];
                                        $stateLabels = [
                                            'pending assignment' => 'Pending',
                                            'team assigned' => 'Team',
                                            'assigned to user' => 'Assigned',
                                            'reassigned to user' => 'Reassigned',
                                            'working' => 'In Progress',
                                            'submitted to review' => 'Review',
                                            'completed' => 'Done',
                                        ];
                                        $stateColor = $stateColors[$task->state] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                        $stateLabel = $stateLabels[$task->state] ?? ucfirst($task->state);
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border-2 {{ $stateColor }}">
                                        {{ $stateLabel }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $task->team->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ optional($task->user)->name ?: 'Unassigned' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ optional($task->created_at)->diffForHumans() ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-right">
                                    <div class="inline-flex items-center justify-end space-x-2">
                                        <a href="{{ route('tasks.show', $task->id) }}" class="inline-flex items-center px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-semibold text-sm">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View
                                        </a>
                                        @if(auth()->check() && auth()->user()->role !== 'member')
                                            <button data-task='@json($task)' class="inline-flex items-center px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-semibold text-sm btn-open-edit">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Edit
                                            </button>
                                            <form id="delete-task-{{ $task->id }}" method="POST" action="{{ route('tasks.destroy', $task->id) }}" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" data-title="{{ $task->title }}" onclick="openDeleteModal('delete-task-{{ $task->id }}', this.dataset.title)" class="inline-flex items-center px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-semibold text-sm">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="inline-block bg-gray-100 rounded-full p-8 mb-4">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 text-lg font-medium">No tasks found</p>
                                    <p class="text-gray-400 text-sm mt-2">Create a new task to get started</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200">
                {{ $tasks->appends(request()->only('state'))->links() }}
            </div>
        </div>
    </div>

    <!-- Create Task Modal -->
    <div id="createTaskModal" class="fixed inset-0 z-50 hidden items-center justify-center backdrop-blur-sm">
        <div class="fixed inset-0 bg-black/50 transition-opacity" aria-hidden="true" onclick="closeCreateModal()"></div>
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 z-10 overflow-hidden transform transition-all">
            <div class="px-6 py-5 bg-gray-800">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xl font-bold text-white">Create New Task</h3>
                        <p class="text-gray-300 text-sm mt-0.5">Add a new task to the system</p>
                    </div>
                </div>
            </div>
            <form id="createTaskForm" method="POST" action="{{ route('tasks.store') }}">
                @csrf
                <div class="px-6 py-5 space-y-4">
                    <div>
                        <label for="create_title" class="block text-sm font-semibold text-gray-700 mb-2">
                            Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="create_title" value="{{ old('title') }}" class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-all" placeholder="Enter task title..." required>
                    </div>
                    <div>
                        <label for="create_description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="create_description" rows="4" class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-all" placeholder="Enter task description...">{{ old('description') }}</textarea>
                    </div>
                    <div>
                        <label for="create_team_id" class="block text-sm font-semibold text-gray-700 mb-2">Team</label>
                        <select name="team_id" id="create_team_id" class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-all">
                            <option value="">-- No team --</option>
                            @foreach($teams as $team)
                                <option value="{{ $team->id }}" @if(old('team_id') == $team->id) selected @endif>{{ $team->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-100">
                    <button type="button" onclick="closeCreateModal()" class="inline-flex items-center px-5 py-2.5 border-2 border-gray-300 text-sm font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-semibold rounded-xl text-white bg-gray-800 hover:bg-gray-900 transition-all duration-200 shadow-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Create Task
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Task Modal -->
    <div id="editTaskModal" class="fixed inset-0 z-50 hidden items-center justify-center backdrop-blur-sm">
        <div class="fixed inset-0 bg-black/50 transition-opacity" aria-hidden="true" onclick="closeEditModal()"></div>
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 z-10 overflow-hidden transform transition-all">
            <div class="px-6 py-5 bg-gray-800">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xl font-bold text-white">Edit Task</h3>
                        <p class="text-gray-300 text-sm mt-0.5">Update task details</p>
                    </div>
                </div>
            </div>
            <form id="editTaskForm" method="POST">
                @csrf
                @method('PUT')
                <div class="px-6 py-5 space-y-4">
                    <div>
                        <label for="edit_title" class="block text-sm font-semibold text-gray-700 mb-2">
                            Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="edit_title" class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-all" required>
                    </div>
                    <div>
                        <label for="edit_description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="edit_description" rows="4" class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-all"></textarea>
                    </div>
                    <div>
                        <label for="edit_team_id" class="block text-sm font-semibold text-gray-700 mb-2">Team</label>
                        <select name="team_id" id="edit_team_id" class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-all">
                            <option value="">-- No team --</option>
                            @foreach($teams as $team)
                                <option value="{{ $team->id }}">{{ $team->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-100">
                    <button type="button" onclick="closeEditModal()" class="inline-flex items-center px-5 py-2.5 border-2 border-gray-300 text-sm font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-semibold rounded-xl text-white bg-gray-800 hover:bg-gray-900 transition-all duration-200 shadow-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Create Modal Management
    function openCreateModal() {
        const modal = document.getElementById('createTaskModal');
        if (!modal) return;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        setTimeout(() => { 
            const titleInput = document.getElementById('create_title');
            if (titleInput) titleInput.focus();
        }, 50);
    }

    function closeCreateModal() {
        const modal = document.getElementById('createTaskModal');
        if (!modal) return;
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        const form = document.getElementById('createTaskForm');
        if (form) form.reset();
    }

    // Edit Modal Management
    function openEditModal(task) {
        const modal = document.getElementById('editTaskModal');
        if (!modal) return;
        document.getElementById('edit_title').value = task.title || '';
        document.getElementById('edit_description').value = task.description || '';
        document.getElementById('edit_team_id').value = task.team_id || '';
        const form = document.getElementById('editTaskForm');
        form.action = '/tasks/' + task.id;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        const modal = document.getElementById('editTaskModal');
        if (!modal) return;
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Edit button click handler
    document.addEventListener('click', function(e) {
        if (e.target && (e.target.classList.contains('btn-open-edit') || e.target.closest('.btn-open-edit'))) {
            const btn = e.target.classList.contains('btn-open-edit') ? e.target : e.target.closest('.btn-open-edit');
            const task = JSON.parse(btn.getAttribute('data-task'));
            openEditModal(task);
        }
    });

    // Delete Modal Management
    function openDeleteModal(formId, title) {
        let modal = document.getElementById('deleteTaskModal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'deleteTaskModal';
            modal.className = 'fixed inset-0 z-50 hidden items-center justify-center backdrop-blur-sm';
            modal.innerHTML = `
                <div class="fixed inset-0 bg-black/50 transition-opacity" aria-hidden="true" onclick="closeDeleteModal()"></div>
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 z-10 overflow-hidden transform transition-all">
                    <div class="px-6 py-5 bg-gray-800">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-xl font-bold text-white">Confirm Delete</h3>
                                <p class="text-gray-300 text-sm mt-0.5">This action cannot be undone</p>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-5">
                        <div class="p-4 bg-red-50 rounded-xl border border-red-200">
                            <p class="text-sm text-gray-700">Are you sure you want to delete <span id="deleteTargetTitle" class="font-bold text-red-700"></span>?</p>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-100">
                        <button type="button" onclick="closeDeleteModal()" class="inline-flex items-center px-5 py-2.5 border-2 border-gray-300 text-sm font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">Cancel</button>
                        <button id="deleteConfirmBtn" type="button" class="inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-semibold rounded-xl text-white bg-gray-800 hover:bg-gray-900 transition-all duration-200 shadow-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete Task
                        </button>
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
        document.body.style.overflow = '';
    }

    // ESC key handling for all modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCreateModal();
            closeEditModal();
            closeDeleteModal();
        }
    });
</script>
@endsection
