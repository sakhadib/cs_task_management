@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-indigo-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8 animate-fade-in">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                        Teams Management
                    </h1>
                    <p class="mt-3 text-lg text-gray-600">Manage teams for the currently selected panel</p>
                    @if($currentPanel)
                        <div class="mt-2 inline-flex items-center px-4 py-2 bg-purple-100 rounded-full">
                            <svg class="w-4 h-4 text-purple-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"></path>
                            </svg>
                            <span class="text-sm font-semibold text-purple-700">Panel: {{ $currentPanel->name }}</span>
                        </div>
                    @endif
                </div>
                <div class="flex items-center gap-3">
                    @if($currentPanel)
                        <button onclick="openNewTeamModal()" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl group">
                            <svg class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            New Team
                        </button>
                        <a href="{{ route('panels.index') }}" class="inline-flex items-center px-5 py-3 border-2 border-purple-600 text-purple-600 rounded-xl hover:bg-purple-50 transition-all duration-200 font-semibold">
                            Manage Panels
                        </a>
                    @else
                        <button disabled class="inline-flex items-center justify-center px-6 py-3 bg-gray-300 text-gray-500 rounded-xl cursor-not-allowed opacity-60">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            New Team
                        </button>
                    @endif
                </div>
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

        @if(!$currentPanel)
            <div class="rounded-xl bg-gradient-to-r from-yellow-50 to-amber-50 p-6 border-l-4 border-yellow-500 shadow-lg">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-yellow-800 mb-1">No Panel Selected</h3>
                        <p class="text-yellow-700">Please set a current panel from the Panels page to manage teams.</p>
                        <a href="{{ route('panels.index') }}" class="inline-flex items-center mt-3 px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors font-semibold">
                            Go to Panels
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        @else

            <!-- New Team Modal -->
            <div id="newTeamModal" class="fixed inset-0 z-50 hidden items-center justify-center backdrop-blur-sm">
                <div class="fixed inset-0 bg-black/50 transition-opacity" aria-hidden="true" onclick="closeNewTeamModal()"></div>
                <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 z-10 overflow-hidden transform transition-all">
                    <form id="newTeamForm" method="POST" action="{{ route('teams.store') }}">
                        @csrf
                        <div class="px-6 py-5 bg-gradient-to-r from-purple-600 to-indigo-600">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-xl font-bold text-white">Create New Team</h3>
                                    <p class="text-purple-100 text-sm mt-0.5">Add a new team to your panel</p>
                                </div>
                            </div>
                        </div>
                        <div class="px-6 py-5 space-y-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Team Name <span class="text-red-500">*</span>
                                </label>
                                <input name="name" type="text" required class="mt-1 block w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" placeholder="Enter team name">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                                <textarea name="description" rows="3" class="mt-1 block w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" placeholder="Describe the team's purpose..."></textarea>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-100">
                            <button type="button" onclick="closeNewTeamModal()" class="inline-flex items-center px-5 py-2.5 border-2 border-gray-300 text-sm font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
                                Cancel
                            </button>
                            <button type="submit" class="inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 shadow-lg">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Create Team
                            </button>
                        </div>
                    </form>
                </div>
            </div>


            @if($teams->isEmpty())
                <div class="text-center py-16">
                    <div class="inline-block bg-gradient-to-br from-gray-100 to-gray-200 rounded-full p-8 mb-4">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500 text-lg font-medium">No teams yet</p>
                    <p class="text-gray-400 text-sm mt-2">Create your first team to get started</p>
                    <button onclick="openNewTeamModal()" class="mt-4 inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-300 font-semibold shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create First Team
                    </button>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($teams as $team)
                        @php
                            $lead = null;
                            if (isset($team->users) && $team->users->count()) {
                                $lead = $team->users->firstWhere('pivot.is_team_lead', true);
                            }
                        @endphp
                        <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 group hover:scale-105">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h4 class="text-xl font-bold text-gray-900 group-hover:text-purple-600 transition-colors">{{ $team->name }}</h4>
                                    @if(isset($team->description))
                                        <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ Str::limit($team->description, 100) }}</p>
                                    @endif
                                </div>
                                <div class="ml-3">
                                    <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-indigo-100 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-3 mb-5">
                                <div class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <span class="font-semibold text-gray-700">{{ isset($team->users) ? $team->users->count() : 0 }}</span>
                                    <span class="text-gray-500 ml-1">Members</span>
                                </div>
                                @if($lead)
                                    <div class="flex items-center text-sm">
                                        <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                        </svg>
                                        <span class="text-gray-500">Lead:</span>
                                        <span class="font-semibold text-gray-900 ml-1">{{ $lead->name }}</span>
                                    </div>
                                @else
                                    <div class="flex items-center text-sm text-gray-400">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636a9 9 0 010 12.728m0 0l-2.829-2.829m2.829 2.829L21 21M15.536 8.464a5 5 0 010 7.072m0 0l-2.829-2.829m-4.243 2.829a4.978 4.978 0 01-1.414-2.83m-1.414 5.658a9 9 0 01-2.167-9.238m7.824 2.167a1 1 0 111.414 1.414m-1.414-1.414L3 3m8.293 8.293l1.414 1.414"></path>
                                        </svg>
                                        No team lead assigned
                                    </div>
                                @endif
                            </div>

                            <div class="border-t border-gray-200 pt-4 flex flex-wrap gap-2">
                                <a href="{{ route('teams.show', $team->id) }}" class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-gradient-to-r from-purple-100 to-indigo-100 text-purple-700 rounded-lg hover:from-purple-200 hover:to-indigo-200 transition-all duration-200 font-semibold text-sm">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View
                                </a>
                                <button onclick="openEditTeamModal({{ $team->id }}, '{{ addslashes($team->name) }}', `{{ addslashes($team->description ?? '') }}`)" class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-all duration-200 font-semibold text-sm">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </button>
                                <button onclick="openDeleteTeamModal({{ $team->id }}, '{{ addslashes($team->name) }}')" class="inline-flex items-center justify-center px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-all duration-200 font-semibold text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
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

    // Edit Team modal (dynamic)
    function openEditTeamModal(id, name, description) {
        let modal = document.getElementById('editTeamModal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'editTeamModal';
            modal.className = 'fixed inset-0 z-50 hidden items-center justify-center backdrop-blur-sm';
            modal.innerHTML = `
                <div class="fixed inset-0 bg-black/50 transition-opacity" aria-hidden="true" onclick="closeEditTeamModal()"></div>
                <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 z-10 overflow-hidden transform transition-all">
                    <form id="editTeamForm" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <div class="px-6 py-5 bg-gradient-to-r from-blue-600 to-indigo-600">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-xl font-bold text-white">Edit Team</h3>
                                    <p class="text-blue-100 text-sm mt-0.5">Update team information</p>
                                </div>
                            </div>
                        </div>
                        <div class="px-6 py-5 space-y-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Team Name <span class="text-red-500">*</span>
                                </label>
                                <input name="name" id="edit_team_name" type="text" required class="mt-1 block w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Enter team name">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                                <textarea name="description" id="edit_team_description" rows="3" class="mt-1 block w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Describe the team's purpose..."></textarea>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-100">
                            <button type="button" onclick="closeEditTeamModal()" class="inline-flex items-center px-5 py-2.5 border-2 border-gray-300 text-sm font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
                                Cancel
                            </button>
                            <button type="submit" class="inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-lg">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Update Team
                            </button>
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
        document.body.style.overflow = 'hidden';
    }

    function closeEditTeamModal() {
        const modal = document.getElementById('editTeamModal');
        if (!modal) return;
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Delete Team modal (requires typing 'delete')
    function openDeleteTeamModal(id, name) {
        let modal = document.getElementById('deleteTeamModal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'deleteTeamModal';
            modal.className = 'fixed inset-0 z-50 hidden items-center justify-center backdrop-blur-sm';
            modal.innerHTML = `
                <div class="fixed inset-0 bg-black/50 transition-opacity" aria-hidden="true" onclick="closeDeleteTeamModal()"></div>
                <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 z-10 overflow-hidden transform transition-all">
                    <div class="px-6 py-5 bg-gradient-to-r from-red-600 to-red-700">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-xl font-bold text-white">Delete Team</h3>
                                <p class="text-red-100 text-sm mt-0.5">This action cannot be undone</p>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-5">
                        <div class="mb-4 p-4 bg-red-50 rounded-xl border border-red-200">
                            <p class="text-sm text-gray-700">
                                You are about to delete <span id="deleteTeamName" class="font-bold text-red-700"></span>.
                            </p>
                            <p class="text-sm text-gray-600 mt-2">
                                Type <span class="font-bold text-red-600">delete</span> to confirm this action.
                            </p>
                        </div>
                        <input id="deleteConfirmInput" type="text" placeholder="Type 'delete' to confirm" class="block w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all">
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-100">
                        <button type="button" onclick="closeDeleteTeamModal()" class="inline-flex items-center px-5 py-2.5 border-2 border-gray-300 text-sm font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
                            Cancel
                        </button>
                        <form id="deleteTeamForm" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button id="deleteTeamButton" type="submit" disabled class="inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-semibold rounded-xl text-white bg-red-600 opacity-50 cursor-not-allowed transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete Team
                            </button>
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
                    btn.classList.remove('opacity-50', 'cursor-not-allowed');
                    btn.classList.add('hover:bg-red-700', 'shadow-lg');
                } else {
                    btn.disabled = true;
                    btn.classList.add('opacity-50', 'cursor-not-allowed');
                    btn.classList.remove('hover:bg-red-700', 'shadow-lg');
                }
            });
        }

        document.getElementById('deleteTeamName').textContent = name;
        const form = document.getElementById('deleteTeamForm');
        form.action = `/teams/${id}`;
        document.getElementById('deleteConfirmInput').value = '';
        const btn = document.getElementById('deleteTeamButton');
        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');
        btn.classList.remove('hover:bg-red-700', 'shadow-lg');

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteTeamModal() {
        const modal = document.getElementById('deleteTeamModal');
        if (!modal) return;
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    function openNewTeamModal() {
        const modal = document.getElementById('newTeamModal');
        if (!modal) return;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeNewTeamModal() {
        const modal = document.getElementById('newTeamModal');
        if (!modal) return;
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    // ESC key handling for all modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeNewTeamModal();
            closeEditTeamModal();
            closeDeleteTeamModal();
        }
    });
</script>
@endsection
