@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900">Panels</h1>
            <a href="{{ route('panels.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Create Panel</a>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-lg bg-green-50 p-4 border-l-4 border-green-400 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($panels as $panel)
                            <tr class="{{ $panel->is_current ? 'bg-green-50' : 'hover:bg-gray-50' }} transition-colors duration-150">
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $panel->id }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700 flex items-center">
                                    <div class="mr-3">
                                        @if($panel->is_current)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Current</span>
                                        @endif
                                    </div>
                                    <div>{{ $panel->name }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $panel->description }}</td>
                                <td class="px-6 py-4 text-sm text-right">
                                    <a href="{{ route('panels.edit', $panel->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    @if(!$panel->is_current)
                                        <form action="{{ route('panels.makeCurrent', $panel->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-800 ml-4">Make current</button>
                                        </form>
                                    @else
                                        <span class="text-gray-400 ml-4">&middot;</span>
                                    @endif
                                    <button type="button" data-panel-id="{{ $panel->id }}" data-panel-name="{{ $panel->name }}" onclick="openPanelDeleteModal(this)" class="text-red-600 hover:text-red-800 ml-4">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Delete Confirmation Modal for Panels -->
<div id="panelDeleteModal" class="fixed inset-0 z-50 hidden items-center justify-center">
    <div class="fixed inset-0 bg-black/50" aria-hidden="true" onclick="closePanelDeleteModal()"></div>
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 z-10 overflow-hidden">
        <div class="px-6 py-4">
            <h3 class="text-lg font-semibold text-gray-900">Delete panel</h3>
            <p class="mt-2 text-sm text-gray-600">Are you sure you want to delete <span id="panelDeleteName" class="font-medium"></span>? This action cannot be undone.</p>
        </div>
        <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
            <button type="button" onclick="closePanelDeleteModal()" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50">Cancel</button>
            <form id="panelDeleteForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700">Delete</button>
            </form>
        </div>
    </div>
</div>

<script>
    const panelDeleteModal = document.getElementById('panelDeleteModal');
    const panelDeleteName = document.getElementById('panelDeleteName');
    const panelDeleteForm = document.getElementById('panelDeleteForm');

    function openPanelDeleteModal(btn) {
        const id = btn.getAttribute('data-panel-id');
        const name = btn.getAttribute('data-panel-name');
        panelDeleteName.textContent = name;
        panelDeleteForm.action = `/panels/${id}`;
        panelDeleteModal.classList.remove('hidden');
        panelDeleteModal.classList.add('flex');
    }

    function closePanelDeleteModal() {
        panelDeleteModal.classList.remove('flex');
        panelDeleteModal.classList.add('hidden');
        panelDeleteName.textContent = '';
        panelDeleteForm.action = '';
    }
</script>
@endsection