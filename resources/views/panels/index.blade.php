@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8 animate-fade-in">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900">Panels</h1>
            <p class="mt-2 text-gray-600">Manage your organization panels</p>
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

        {{-- Panels Section --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="px-6 py-5 bg-gray-800 flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-xl font-bold text-white">All Panels</h2>
                        <p class="text-gray-300 text-sm">{{ count($panels) }} panel(s) total</p>
                    </div>
                </div>
                <button type="button" onclick="openCreateModal()" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-semibold text-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Panel
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($panels as $panel)
                            <tr class="hover:bg-gray-50 transition-colors duration-150 {{ $panel->is_current ? 'bg-gray-100' : '' }}">
                                <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $panel->id }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex items-center">
                                        <span class="text-gray-900 font-semibold">{{ $panel->name }}</span>
                                        @if($panel->is_current)
                                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-900 text-white border-2 border-gray-900">
                                                Current
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $panel->description ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-right space-x-3">
                                    <button type="button" onclick="openEditModal({{ $panel->id }}, '{{ addslashes($panel->name) }}', '{{ addslashes($panel->description ?? '') }}')" class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-medium text-xs">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit
                                    </button>
                                    @if(!$panel->is_current)
                                        <form action="{{ route('panels.makeCurrent', $panel->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3 py-1 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-all duration-200 font-medium text-xs">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Make Current
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('panels.positions', $panel->id) }}" class="inline-flex items-center px-3 py-1 bg-gray-900 text-white rounded-lg hover:bg-black transition-all duration-200 font-medium text-xs">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                            </svg>
                                            Positions
                                        </a>
                                    @endif
                                    <button type="button" data-panel-id="{{ $panel->id }}" data-panel-name="{{ $panel->name }}" onclick="openDeleteModal(this)" class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-medium text-xs">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div class="inline-block bg-gray-100 rounded-full p-8 mb-4">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 text-lg font-medium">No panels yet</p>
                                    <p class="text-gray-400 text-sm mt-2">Create your first panel to get started</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Create Panel Modal -->
<div id="createModal" class="fixed inset-0 z-50 hidden items-center justify-center backdrop-blur-sm">
    <div class="fixed inset-0 bg-black/50 transition-opacity" aria-hidden="true" onclick="closeCreateModal()"></div>
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 z-10 overflow-hidden transform transition-all">
        <div class="px-6 py-5 bg-gray-800">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-xl font-bold text-white">Create New Panel</h3>
                    <p class="text-gray-300 text-sm mt-0.5">Add a new panel to the system</p>
                </div>
            </div>
        </div>
        <form action="{{ route('panels.store') }}" method="POST">
            @csrf
            <div class="p-6 space-y-5">
                <div>
                    <label for="create_name" class="block text-sm font-bold text-gray-700 mb-2">Panel Name *</label>
                    <input type="text" name="name" id="create_name" required class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-all text-sm" placeholder="Enter panel name">
                </div>
                <div>
                    <label for="create_description" class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="create_description" rows="4" class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-all text-sm" placeholder="Optional description"></textarea>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-200">
                <button type="button" onclick="closeCreateModal()" class="inline-flex items-center px-5 py-2.5 border-2 border-gray-300 text-sm font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">Cancel</button>
                <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-semibold rounded-xl text-white bg-gray-800 hover:bg-gray-900 transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create Panel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Panel Modal -->
<div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center backdrop-blur-sm">
    <div class="fixed inset-0 bg-black/50 transition-opacity" aria-hidden="true" onclick="closeEditModal()"></div>
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 z-10 overflow-hidden transform transition-all">
        <div class="px-6 py-5 bg-gray-800">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-xl font-bold text-white">Edit Panel</h3>
                    <p class="text-gray-300 text-sm mt-0.5">Update panel information</p>
                </div>
            </div>
        </div>
        <form id="editForm" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-5">
                <div>
                    <label for="edit_name" class="block text-sm font-bold text-gray-700 mb-2">Panel Name *</label>
                    <input type="text" name="name" id="edit_name" required class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-all text-sm" placeholder="Enter panel name">
                </div>
                <div>
                    <label for="edit_description" class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="edit_description" rows="4" class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-all text-sm" placeholder="Optional description"></textarea>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-200">
                <button type="button" onclick="closeEditModal()" class="inline-flex items-center px-5 py-2.5 border-2 border-gray-300 text-sm font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">Cancel</button>
                <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-semibold rounded-xl text-white bg-gray-800 hover:bg-gray-900 transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Update Panel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center backdrop-blur-sm">
    <div class="fixed inset-0 bg-black/50 transition-opacity" aria-hidden="true" onclick="closeDeleteModal()"></div>
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 z-10 overflow-hidden">
        <div class="px-6 py-5 bg-gray-800">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-xl font-bold text-white">Delete Panel</h3>
                </div>
            </div>
        </div>
        <div class="p-6">
            <p class="text-sm text-gray-600">Are you sure you want to delete <span id="deleteName" class="font-bold text-gray-900"></span>? This action cannot be undone.</p>
        </div>
        <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3 border-t border-gray-200">
            <button type="button" onclick="closeDeleteModal()" class="inline-flex items-center px-5 py-2.5 border-2 border-gray-300 text-sm font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">Cancel</button>
            <form id="deleteForm" method="POST" action="" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-semibold rounded-xl text-white bg-gray-900 hover:bg-black transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Delete Panel
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // Create Modal
    function openCreateModal() {
        const modal = document.getElementById('createModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        document.getElementById('create_name').focus();
    }

    function closeCreateModal() {
        const modal = document.getElementById('createModal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('create_name').value = '';
        document.getElementById('create_description').value = '';
    }

    // Edit Modal
    function openEditModal(id, name, description) {
        const modal = document.getElementById('editModal');
        const form = document.getElementById('editForm');
        
        form.action = `/panels/${id}`;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_description').value = description;
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        document.getElementById('edit_name').focus();
    }

    function closeEditModal() {
        const modal = document.getElementById('editModal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Delete Modal
    function openDeleteModal(btn) {
        const id = btn.getAttribute('data-panel-id');
        const name = btn.getAttribute('data-panel-name');
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        
        document.getElementById('deleteName').textContent = name;
        form.action = `/panels/${id}`;
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // ESC key to close modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCreateModal();
            closeEditModal();
            closeDeleteModal();
        }
    });
</script>
@endsection