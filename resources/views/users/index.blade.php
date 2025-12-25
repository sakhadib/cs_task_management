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
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                ID
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Email
                            </th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        #{{ $user->id }}
                                    </span>
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
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                    <a href="#" class="text-indigo-600 hover:text-indigo-900 transition-colors duration-200">Edit</a>
                                    <span class="text-gray-300">|</span>
                                    <a href="#" class="text-red-600 hover:text-red-900 transition-colors duration-200">Delete</a>
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
        </div>
    </div>
</div>
@endsection