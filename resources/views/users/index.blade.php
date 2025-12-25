@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row justify-between items-center mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-4 sm:mb-0">
                Users Management
            </h1>
            <a href="{{ route('users.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Create User
            </a>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-6 rounded-lg bg-green-50 p-4 border-l-4 border-green-400 shadow-sm flex items-center">
                <svg class="h-6 w-6 text-green-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm font-medium text-green-800">
                    {{ session('success') }}
                </p>
            </div>
        @endif

        {{-- Table Card --}}
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">

            <div class="px-6 py-4 border-b border-gray-100">
                <div class="max-w-md">
                    <label for="search" class="sr-only">Search users</label>
                    <input id="search" name="search" type="search" placeholder="Search users by name..." class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                ID
                            </th>
                               <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                   Student ID
                               </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Email
                            </th>
                               <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                   Role
                               </th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody" class="bg-white divide-y divide-gray-200">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        #{{ $user->id }}
                                    </span>
                                </td>
                                   <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                       {{ $user->student_id }}
                                   </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        {{-- Simple Avatar Placeholder --}}
                                        <div class="h-10 w-10 flex-shrink-0 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold text-sm">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900">{{ $user->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $user->email }}
                                </td>
                                   <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                       {{ ucfirst($user->role) }}
                                   </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">
                                            <a href="{{ route('users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            <button type="button" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}" onclick="openDeleteModal(this)" class="text-red-600 hover:text-red-800 ml-4">Delete</button>
                                        </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{-- Empty State Check (Optional visual polish if list is empty) --}}
            @if(count($users) == 0)
                <div class="text-center py-10">
                    <p class="text-gray-500 text-sm">No users found.</p>
                </div>
            @endif

            <!-- Delete Confirmation Modal -->
            <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center">
                <div class="fixed inset-0 bg-black/50" aria-hidden="true" onclick="closeDeleteModal()"></div>
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 z-10 overflow-hidden">
                    <div class="px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-900">Delete user</h3>
                        <p class="mt-2 text-sm text-gray-600">Are you sure you want to delete <span id="deleteUserName" class="font-medium"></span>? This action cannot be undone.</p>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                        <button type="button" onclick="closeDeleteModal()" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50">Cancel</button>
                        <form id="deleteUserForm" method="POST" action="">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700">Delete</button>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                const usersBaseUrl = "{{ url('/users') }}";
                const deleteModal = document.getElementById('deleteModal');
                const deleteUserNameEl = document.getElementById('deleteUserName');
                const deleteUserForm = document.getElementById('deleteUserForm');

                function openDeleteModal(btn) {
                    const id = btn.getAttribute('data-user-id');
                    const name = btn.getAttribute('data-user-name');
                    deleteUserNameEl.textContent = name;
                    deleteUserForm.action = usersBaseUrl + '/' + id;
                    deleteModal.classList.remove('hidden');
                    deleteModal.classList.add('flex');
                }

                function closeDeleteModal() {
                    deleteModal.classList.remove('flex');
                    deleteModal.classList.add('hidden');
                    deleteUserNameEl.textContent = '';
                    deleteUserForm.action = '';
                }

                // Search: debounce and fetch
                const searchInput = document.getElementById('search');
                let searchTimeout = null;

                function renderUsersRows(users) {
                    const tbody = document.getElementById('usersTableBody');
                    if (!tbody) return;
                    tbody.innerHTML = users.map(u => `
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"> <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">#${u.id}</span> </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${u.student_id ?? ''}</td>
                            <td class="px-6 py-4 whitespace-nowrap"> <div class="flex items-center"> <div class="h-10 w-10 flex-shrink-0 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold text-sm">${(u.name||'').charAt(0)}</div> <div class="ml-4"> <div class="text-sm font-semibold text-gray-900">${u.name}</div> </div> </div> </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${u.email}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${(u.role||'')}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right"> <a href="/users/${u.id}/edit" class="text-indigo-600 hover:text-indigo-900">Edit</a> <button type="button" data-user-id="${u.id}" data-user-name="${u.name}" onclick="openDeleteModal(this)" class="text-red-600 hover:text-red-800 ml-4">Delete</button> </td>
                        </tr>
                    `).join('');
                }

                function doSearch(q) {
                    const url = `${usersBaseUrl}?search=${encodeURIComponent(q)}`;
                    fetch(url, { headers: { 'Accept': 'application/json' } })
                        .then(r => r.json())
                        .then(data => {
                            renderUsersRows(data);
                        })
                        .catch(err => console.error('Search error', err));
                }

                if (searchInput) {
                    searchInput.addEventListener('input', function(e) {
                        const q = e.target.value.trim();
                        clearTimeout(searchTimeout);
                        searchTimeout = setTimeout(() => {
                            doSearch(q);
                        }, 300);
                    });
                }
            </script>
        </div>
    </div>
</div>
@endsection