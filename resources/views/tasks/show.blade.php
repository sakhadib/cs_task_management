@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <div class="flex items-center space-x-2 text-sm text-gray-500 mb-1">
                    <a href="{{ route('tasks.index') }}" class="hover:text-indigo-600 transition-colors">Tasks</a>
                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    <span>Task #{{ $task->id }}</span>
                </div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                    {{ $task->title }}
                </h1>
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap items-center gap-2 w-full">
                <a href="{{ route('tasks.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                    Back to List
                </a>

                <form method="POST" action="{{ route('tasks.changeState', $task->id) }}" class="w-full sm:w-auto">
                    @csrf
                    <input type="hidden" name="state" value="working">
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-3 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-all">
                        Mark Working
                    </button>
                </form>

                <form method="POST" action="{{ route('tasks.changeState', $task->id) }}" class="w-full sm:w-auto">
                    @csrf
                    <input type="hidden" name="state" value="submitted to review">
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-3 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                        Submit to Review
                    </button>
                </form>

                <form method="POST" action="{{ route('tasks.changeState', $task->id) }}" class="w-full sm:w-auto">
                    @csrf
                    <input type="hidden" name="state" value="completed">
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-3 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all">
                        Mark Completed
                    </button>
                </form>

                <div class="w-full sm:w-auto">
                    <button type="button" onclick="openAssignModal()" class="w-full sm:w-auto inline-flex items-center justify-center px-3 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        Assign User
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Left Column: Task Info Card --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide">Task Information</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        
                        {{-- State Badge --}}
                        <div>
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wider block mb-1">State</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 capitalize">
                                {{ $task->state }}
                            </span>
                        </div>

                        {{-- Assignee --}}
                        <div>
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wider block mb-1">Assignee</span>
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs mr-3">
                                    {{ substr($task->user->name ?? ($task->creator->name ?? '?'), 0, 1) }}
                                </div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $task->user->name ?? ($task->creator->name ?? 'Unassigned') }}
                                </div>
                            </div>
                        </div>

                        {{-- Metadata Grid --}}
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider block mb-1">Team</span>
                                <p class="text-sm text-gray-900 font-medium">{{ $task->team->name ?? 'No Team' }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider block mb-1">Panel</span>
                                <p class="text-sm text-gray-900 font-medium">{{ $task->panel->name ?? 'No Panel' }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider block mb-1">Created By</span>
                                <p class="text-sm text-gray-900">{{ $task->creator->name ?? 'Unknown' }}</p>
                                <p class="text-xs text-gray-400">{{ optional($task->created_at)->format('M j, Y g:i a') }}</p>
                            </div>
                        </div>

                        <div class="border-t border-gray-100 pt-4">
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wider block mb-2">Description</span>
                            <div class="text-sm text-gray-600 leading-relaxed bg-gray-50 p-3 rounded-lg border border-gray-100">
                                {{ $task->description ?? 'No description provided.' }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Right Column: Activity Timeline --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Activity History
                    </h3>

                    @if($histories->isEmpty())
                        <div class="text-center py-12">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-3">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <p class="text-gray-500 text-sm">No activity recorded for this task yet.</p>
                        </div>
                    @else
                        {{-- PHP Helper Definition (Kept as is, just wrapped cleanly) --}}
                        @php
                            if (! function_exists('renderFieldValue')) {
                                function renderFieldValue($field, $val) {
                                    if ($val === null || $val === '') return 'NULL';
                                    if (in_array($field, ['user_id', 'created_by', 'updated_by'])) {
                                        $u = \App\Models\User::find($val);
                                        return $u ? $u->name : $val;
                                    }
                                    if ($field === 'team_id') {
                                        $t = \App\Models\Team::find($val);
                                        return $t ? $t->name : $val;
                                    }
                                    if ($field === 'panel_id') {
                                        $p = \App\Models\Panel::find($val);
                                        return $p ? $p->name : $val;
                                    }
                                    if (is_array($val)) return json_encode($val);
                                    return (string) $val;
                                }
                            }
                        @endphp

                        <ol class="relative border-l border-gray-200 ml-3 space-y-8">
                            @foreach($histories as $h)
                                <li class="ml-6 group">
                                    {{-- Timeline Dot --}}
                                    <span class="absolute flex items-center justify-center w-8 h-8 bg-indigo-50 rounded-full -left-4 ring-4 ring-white border border-indigo-100">
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </span>
                                    
                                    {{-- Timeline Header --}}
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2">
                                        <div>
                                            <h4 class="text-base font-semibold text-gray-900 capitalize">
                                                {{ $h->action }}
                                                <span class="font-normal text-gray-500 text-sm">by {{ $h->user->name ?? 'System' }}</span>
                                            </h4>
                                            <time class="block mb-2 text-xs font-normal text-gray-400">
                                                {{ optional($h->created_at)->format('M j, Y • g:i a') }}
                                            </time>
                                        </div>
                                    </div>

                                    {{-- Collapsible Details Section --}}
                                    <div x-data="{ open: false }" class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm transition-shadow hover:shadow-md">
                                        {{-- Toggle Header --}}
                                        <button @click="open = !open" type="button" class="w-full px-4 py-2 bg-gray-50 flex items-center justify-between text-xs font-medium text-gray-600 hover:bg-gray-100 transition-colors">
                                            <span>Change Details</span>
                                            <svg :class="{'rotate-180': open}" class="w-4 h-4 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </button>

                                        {{-- Content --}}
                                        <div x-show="open" x-collapse style="display: none;">
                                            <div class="p-4">
                                                @php
                                                    $details = [];
                                                    try { $details = $h->details ? json_decode($h->details, true) : []; } catch (\Throwable $e) { $details = ['raw' => $h->details]; }
                                                @endphp

                                                @if(isset($details['changes']) && is_array($details['changes']))
                                                    <div class="grid grid-cols-1 gap-3">
                                                        @foreach($details['changes'] as $field => $vals)
                                                            @php
                                                                $label = ucwords(str_replace('_', ' ', preg_replace('/_id$/', '', $field)));
                                                            @endphp
                                                            <div class="flex flex-col sm:flex-row sm:items-center text-sm border-b border-gray-50 last:border-0 pb-2 last:pb-0">
                                                                <div class="w-32 font-medium text-gray-500 text-xs uppercase tracking-wide mb-1 sm:mb-0">{{ $label }}</div>
                                                                <div class="flex-1 flex items-center space-x-3">
                                                                    <div class="px-2 py-1 bg-red-50 text-red-700 rounded text-xs line-through decoration-red-400 decoration-1 break-all">
                                                                        {{ renderFieldValue($field, $vals['old'] ?? null) }}
                                                                    </div>
                                                                    <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                                                    <div class="px-2 py-1 bg-green-50 text-green-700 rounded text-xs font-semibold break-all">
                                                                        {{ renderFieldValue($field, $vals['new'] ?? null) }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @elseif(isset($details['attributes']) && is_array($details['attributes']))
                                                    <div class="space-y-2">
                                                        @foreach($details['attributes'] as $k => $v)
                                                             <div class="flex justify-between text-sm border-b border-gray-50 pb-1">
                                                                <span class="text-gray-500 font-medium">{{ ucwords(str_replace('_', ' ', $k)) }}</span>
                                                                <span class="text-gray-800">{{ renderFieldValue($k, $v) }}</span>
                                                             </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <pre class="whitespace-pre-wrap text-xs text-gray-600 bg-gray-50 p-3 rounded border border-gray-100 font-mono">{{ json_encode($details, JSON_PRETTY_PRINT) }}</pre>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Alpine.js for Collapsible logic (Lightweight and standard in Laravel ecosystem) --}}
<script src="//unpkg.com/alpinejs" defer></script>

@endsection

@section('scripts')
<script>
    // Assign User modal logic
    function openAssignModal() {
        let modal = document.getElementById('assignUserModal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'assignUserModal';
            modal.className = 'fixed inset-0 z-50 hidden items-center justify-center';
            modal.innerHTML = `
                <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeAssignModal()"></div>
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 z-10 overflow-hidden transform transition-all scale-100">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Assign User</h3>
                            <p class="text-xs text-gray-500">Search for a team member to take over this task.</p>
                        </div>
                         <button onclick="closeAssignModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <div class="p-6">
                        <div class="relative">
                            <svg class="w-5 h-5 absolute left-3 top-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            <input id="assign_user_search" type="search" class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm" placeholder="Search by name or ID...">
                        </div>
                        <div id="assignSearchResults" class="mt-3 max-h-60 overflow-y-auto hidden border border-gray-100 rounded-lg divide-y divide-gray-100 shadow-sm"></div>
                    </div>
                    <form id="assignUserForm" method="POST" action="{{ route('tasks.assign', $task->id) }}">
                        @csrf
                        <input type="hidden" name="user_id" id="assign_user_id">
                    </form>
                    <div class="bg-gray-50 px-6 py-3 flex items-center justify-end">
                        <button type="button" onclick="closeAssignModal()" class="text-sm text-gray-600 hover:text-gray-900 font-medium px-4 py-2">Cancel</button>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);

            // Attach search listener
            setTimeout(() => {
                const searchEl = document.getElementById('assign_user_search');
                if (searchEl) {
                    let assignTimeout = null;
                    searchEl.addEventListener('input', function(e) {
                        const q = e.target.value.trim();
                        clearTimeout(assignTimeout);
                        assignTimeout = setTimeout(() => doAssignSearch(q), 300);
                    });
                }
            }, 50);
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        const input = document.getElementById('assign_user_search');
        if(input) input.focus();
    }

    function closeAssignModal() {
        const modal = document.getElementById('assignUserModal');
        if (!modal) return;
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

    function renderAssignResults(items) {
        const wrapper = document.getElementById('assignSearchResults');
        if (!wrapper) return;
        
        if (!items || items.length === 0) {
            wrapper.innerHTML = `
                <div class="p-8 text-center">
                    <p class="text-sm text-gray-500">No users found.</p>
                </div>`;
            wrapper.classList.remove('hidden');
            return;
        }

        wrapper.innerHTML = items.map(u => `
            <button type="button" class="w-full text-left px-4 py-3 hover:bg-indigo-50 transition-colors group flex items-center justify-between" data-id="${u.id}" data-name="${u.name}">
                <div>
                    <div class="text-sm font-semibold text-gray-900 group-hover:text-indigo-700">${u.name}</div>
                    <div class="text-xs text-gray-500">${u.student_id ? 'ID: ' + u.student_id : u.email}</div>
                </div>
                <svg class="w-4 h-4 text-indigo-400 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </button>
        `).join('');
        wrapper.classList.remove('hidden');

        Array.from(wrapper.children).forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                document.getElementById('assign_user_id').value = id;
                const form = document.getElementById('assignUserForm');
                if (form) form.submit();
            });
        });
    }

    function doAssignSearch(q) {
        const wrapper = document.getElementById('assignSearchResults');
        if (!q) { 
            if (wrapper) { 
                wrapper.classList.add('hidden'); 
                wrapper.innerHTML = ''; 
            } 
            return; 
        }
        
        // Show loading state
        wrapper.innerHTML = '<div class="p-4 text-center text-xs text-gray-400">Searching...</div>';
        wrapper.classList.remove('hidden');

        fetch(`{{ url('/users') }}?search=${encodeURIComponent(q)}`, { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => renderAssignResults(data))
            .catch(err => { 
                console.error('Search error', err); 
                wrapper.innerHTML = '<div class="p-3 text-sm text-red-500">Error searching users</div>';
            });
    }
</script>
@endsection