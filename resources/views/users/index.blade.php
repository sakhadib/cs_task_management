@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header Section --}}
        <div class="bg-gray-800 rounded-2xl shadow-xl mb-6 overflow-hidden">
            <div class="px-8 py-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-white">Users Management</h1>
                            <p class="mt-1 text-gray-300 text-sm">Manage all system users and their roles</p>
                        </div>
                    </div>
                    <button onclick="openCreateModal()" class="inline-flex items-center space-x-2 px-6 py-3 bg-white hover:bg-gray-100 text-gray-900 rounded-xl transition-all font-semibold shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Create User</span>
                    </button>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 shadow-lg overflow-hidden">
                <div class="flex items-center p-4">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-semibold text-green-800">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Error Message --}}
        @if(session('error'))
            <div class="mb-6 rounded-xl bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 shadow-lg overflow-hidden">
                <div class="flex items-center p-4">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-semibold text-red-800">
                            {{ session('error') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="mb-6 rounded-xl bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 shadow-lg overflow-hidden">
                <div class="flex items-start p-4">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-semibold text-red-800 mb-2">Please fix the following errors:</h3>
                        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Table Card --}}
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">

            {{-- Search Bar --}}
            <div class="bg-gray-50 px-6 py-5 border-b border-gray-200">
                <div class="max-w-md">
                    <label for="search" class="sr-only">Search users</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input id="search" name="search" type="search" placeholder="Search users by name..." class="block w-full pl-10 pr-3 py-3 border-2 border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-800 transition-all" />
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                ID
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Student ID
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Email
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Role
                            </th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody" class="bg-white divide-y divide-gray-200">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50 transition-all group">
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-gray-200 text-gray-800 border border-gray-300">
                                        #{{ $user->id }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">{{ $user->student_id }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0 bg-gray-200 rounded-full flex items-center justify-center font-bold text-gray-700 shadow-sm">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $user->email }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $roleColors = [
                                            'admin' => 'bg-gray-900 text-white border-black',
                                            'advisor' => 'bg-gray-700 text-white border-gray-800',
                                            'member' => 'bg-gray-200 text-gray-800 border-gray-300',
                                        ];
                                        $colorClass = $roleColors[strtolower($user->role)] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                    @endphp
                                    <span class="px-3 py-1.5 inline-flex text-xs leading-5 font-bold rounded-full border-2 {{ $colorClass }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-right">
                                    <button onclick="openEditModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->student_id }}', '{{ $user->email }}', '{{ $user->role }}')" class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit
                                    </button>
                                    <button type="button" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}" onclick="openDeleteModal(this)" class="inline-flex items-center px-3 py-1.5 bg-gray-900 text-white rounded-lg hover:bg-black transition-colors ml-2">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{-- Empty State --}}
            @if(count($users) == 0)
                <div class="text-center py-16">
                    <div class="inline-block bg-gray-100 rounded-full p-8 mb-4">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-900 text-lg font-medium">No users found</p>
                    <p class="text-gray-500 text-sm mt-2">Start by creating your first user</p>
                </div>
            @endif

            <!-- Create User Modal -->
            <div id="createModal" class="fixed inset-0 z-50 hidden items-center justify-center">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" aria-hidden="true" onclick="closeCreateModal()"></div>
                <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 z-10 overflow-hidden max-h-[90vh] overflow-y-auto">
                    <div class="bg-gray-800 px-6 py-5 sticky top-0 z-10">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-white">Create New User</h3>
                            </div>
                            <button onclick="closeCreateModal()" class="text-white/70 hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('users.store') }}">
                        @csrf
                        <div class="px-6 py-6 space-y-5">
                            <div>
                                <label for="create_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="create_name" name="name" required class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-800 transition-all" placeholder="John Doe">
                            </div>

                            <div>
                                <label for="create_student_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Student ID <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="create_student_id" name="student_id" required class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-800 transition-all" placeholder="123456">
                            </div>

                            <div>
                                <label for="create_email" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input type="email" id="create_email" name="email" required class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-800 transition-all" placeholder="john@example.com">
                            </div>

                            <div class="px-4 py-3 bg-gray-100 rounded-xl border-2 border-gray-300">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-gray-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-sm text-gray-700">
                                        A random 6-character password will be automatically generated and sent to the user's email address.
                                    </p>
                                </div>
                            </div>

                            <div>
                                <label for="create_role" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Role <span class="text-red-500">*</span>
                                </label>
                                <select id="create_role" name="role" required class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-800 transition-all">
                                    <option value="">Select a role</option>
                                    <option value="admin">Admin</option>
                                    <option value="advisor">Advisor</option>
                                    <option value="member">Member</option>
                                </select>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 sticky bottom-0 border-t border-gray-200">
                            <button type="button" onclick="closeCreateModal()" class="inline-flex items-center justify-center px-5 py-2.5 border-2 border-gray-300 text-sm font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all">
                                Cancel
                            </button>
                            <button type="submit" class="inline-flex items-center justify-center space-x-2 px-6 py-2.5 text-sm font-semibold rounded-xl text-white bg-gray-800 hover:bg-black transition-all shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span>Create User</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Edit User Modal -->
            <div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" aria-hidden="true" onclick="closeEditModal()"></div>
                <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 z-10 overflow-hidden max-h-[90vh] overflow-y-auto">
                    <div class="bg-gray-800 px-6 py-5 sticky top-0 z-10">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-white">Edit User</h3>
                            </div>
                            <button onclick="closeEditModal()" class="text-white/70 hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <form id="editUserForm" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <div class="px-6 py-6 space-y-5">
                            <div>
                                <label for="edit_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="edit_name" name="name" required class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-800 transition-all">
                            </div>

                            <div>
                                <label for="edit_student_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Student ID <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="edit_student_id" name="student_id" required class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-800 transition-all">
                            </div>

                            <div>
                                <label for="edit_email" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input type="email" id="edit_email" name="email" required class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-800 transition-all">
                            </div>

                            <div>
                                <label for="edit_password" class="block text-sm font-semibold text-gray-700 mb-2">
                                    New Password <span class="text-gray-500 text-xs">(Leave blank to keep current)</span>
                                </label>
                                <input type="password" id="edit_password" name="password" class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-800 transition-all" placeholder="••••••••">
                            </div>

                            <div>
                                <label for="edit_password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Confirm New Password
                                </label>
                                <input type="password" id="edit_password_confirmation" name="password_confirmation" class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-800 transition-all" placeholder="••••••••">
                            </div>

                            <div>
                                <label for="edit_role" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Role <span class="text-red-500">*</span>
                                </label>
                                <select id="edit_role" name="role" required class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-800 transition-all">
                                    <option value="">Select a role</option>
                                    <option value="admin">Admin</option>
                                    <option value="advisor">Advisor</option>
                                    <option value="member">Member</option>
                                </select>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 sticky bottom-0 border-t border-gray-200">
                            <button type="button" onclick="closeEditModal()" class="inline-flex items-center justify-center px-5 py-2.5 border-2 border-gray-300 text-sm font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all">
                                Cancel
                            </button>
                            <button type="submit" class="inline-flex items-center justify-center space-x-2 px-6 py-2.5 text-sm font-semibold rounded-xl text-white bg-gray-800 hover:bg-black transition-all shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Update User</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" aria-hidden="true" onclick="closeDeleteModal()"></div>
                <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 z-10 overflow-hidden">
                    <div class="bg-gray-800 px-6 py-5">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-white">Delete User</h3>
                        </div>
                    </div>
                    <div class="px-6 py-5">
                        <p class="text-gray-700">
                            Are you sure you want to delete <span id="deleteUserName" class="font-bold text-gray-900"></span>? 
                        </p>
                        <p class="mt-2 text-sm text-red-600 font-medium">
                            ⚠️ This action cannot be undone.
                        </p>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t border-gray-200">
                        <button type="button" onclick="closeDeleteModal()" class="inline-flex items-center justify-center px-5 py-2.5 border-2 border-gray-300 text-sm font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all">
                            Cancel
                        </button>
                        <form id="deleteUserForm" method="POST" action="">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center space-x-2 px-5 py-2.5 text-sm font-semibold rounded-xl text-white bg-gray-900 hover:bg-black transition-all shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                <span>Delete User</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                const usersBaseUrl = "{{ url('/users') }}";
                const deleteModal = document.getElementById('deleteModal');
                const deleteUserNameEl = document.getElementById('deleteUserName');
                const deleteUserForm = document.getElementById('deleteUserForm');
                const createModal = document.getElementById('createModal');
                const editModal = document.getElementById('editModal');
                const editUserForm = document.getElementById('editUserForm');

                // Create Modal Functions
                function openCreateModal() {
                    createModal.classList.remove('hidden');
                    createModal.classList.add('flex');
                    document.body.style.overflow = 'hidden';
                }

                function closeCreateModal() {
                    createModal.classList.remove('flex');
                    createModal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                    // Reset form
                    createModal.querySelector('form').reset();
                }

                // Edit Modal Functions
                function openEditModal(id, name, studentId, email, role) {
                    document.getElementById('edit_name').value = name;
                    document.getElementById('edit_student_id').value = studentId;
                    document.getElementById('edit_email').value = email;
                    document.getElementById('edit_role').value = role;
                    document.getElementById('edit_password').value = '';
                    document.getElementById('edit_password_confirmation').value = '';
                    editUserForm.action = usersBaseUrl + '/' + id;
                    editModal.classList.remove('hidden');
                    editModal.classList.add('flex');
                    document.body.style.overflow = 'hidden';
                }

                function closeEditModal() {
                    editModal.classList.remove('flex');
                    editModal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                    editUserForm.reset();
                }

                // Delete Modal Functions
                function openDeleteModal(btn) {
                    const id = btn.getAttribute('data-user-id');
                    const name = btn.getAttribute('data-user-name');
                    deleteUserNameEl.textContent = name;
                    deleteUserForm.action = usersBaseUrl + '/' + id;
                    deleteModal.classList.remove('hidden');
                    deleteModal.classList.add('flex');
                    document.body.style.overflow = 'hidden';
                }

                function closeDeleteModal() {
                    deleteModal.classList.remove('flex');
                    deleteModal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                    deleteUserNameEl.textContent = '';
                    deleteUserForm.action = '';
                }

                // Close modals on Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        closeCreateModal();
                        closeEditModal();
                        closeDeleteModal();
                    }
                });

                // Search: debounce and fetch
                const searchInput = document.getElementById('search');
                let searchTimeout = null;

                function renderUsersRows(users) {
                    const tbody = document.getElementById('usersTableBody');
                    if (!tbody) return;
                    
                    const roleColors = {
                        'admin': 'bg-gradient-to-r from-red-100 to-red-200 text-red-700 border-red-300',
                        'advisor': 'bg-gradient-to-r from-purple-100 to-purple-200 text-purple-700 border-purple-300',
                        'member': 'bg-gradient-to-r from-green-100 to-green-200 text-green-700 border-green-300',
                    };
                    
                    tbody.innerHTML = users.map(u => {
                        const roleClass = roleColors[u.role?.toLowerCase()] || 'bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 border-gray-300';
                        return `
                        <tr class="hover:bg-blue-50 transition-all duration-200 group">
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-700">#${u.id}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">${u.student_id ?? ''}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-11 w-11 flex-shrink-0 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-base shadow-md group-hover:scale-110 transition-transform duration-200">
                                        ${(u.name||'').charAt(0)}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-gray-900">${u.name}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    ${u.email}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1.5 inline-flex text-xs leading-5 font-bold rounded-full border ${roleClass}">
                                    ${u.role || ''}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-right">
                                <button onclick="openEditModal(${u.id}, '${u.name.replace(/'/g, "\\'")}', '${u.student_id}', '${u.email}', '${u.role}')" class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </button>
                                <button type="button" data-user-id="${u.id}" data-user-name="${u.name}" onclick="openDeleteModal(this)" class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors duration-200 ml-2">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Delete
                                </button>
                            </td>
                        </tr>
                    `;}).join('');
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

                // Auto-open create modal if there are validation errors for create
                @if($errors->any() && old('_token') && !old('_method'))
                    openCreateModal();
                    @foreach($errors->all() as $error)
                        // Optionally populate with old values
                    @endforeach
                @endif

                // Auto-open edit modal if there are validation errors for edit
                @if($errors->any() && old('_method') == 'PUT')
                    openEditModal(
                        {{ old('id', 0) }},
                        '{{ old('name', '') }}',
                        '{{ old('student_id', '') }}',
                        '{{ old('email', '') }}',
                        '{{ old('role', '') }}'
                    );
                @endif
            </script>
        </div>
    </div>
</div>

<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fade-in 0.5s ease-out;
    }
</style>
@endsection