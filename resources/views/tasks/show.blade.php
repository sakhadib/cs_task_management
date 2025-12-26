@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8 animate-fade-in">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('tasks.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Tasks
                </a>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900">
                {{ $task->title }}
            </h1>
            <p class="mt-2 text-gray-500 text-sm">Task #{{ $task->id }}</p>
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

        <!-- Task Information Section -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 mb-8">
            <div class="px-6 py-5 bg-gray-800">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-xl font-bold text-white">Task Details</h2>
                        <p class="text-gray-300 text-sm">Complete information about this task</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <!-- State -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Status</label>
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
                    </div>

                    <!-- Assignee -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Assignee</label>
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-700 font-bold text-xs mr-2">
                                {{ substr($task->user->name ?? ($task->creator->name ?? '?'), 0, 1) }}
                            </div>
                            <span class="text-sm font-medium text-gray-900">
                                {{ $task->user->name ?? ($task->creator->name ?? 'Unassigned') }}
                            </span>
                        </div>
                    </div>

                    <!-- Team -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Team</label>
                        <p class="text-sm text-gray-900 font-medium">{{ $task->team->name ?? 'No Team' }}</p>
                    </div>

                    <!-- Panel -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Panel</label>
                        <p class="text-sm text-gray-900 font-medium">{{ $task->panel->name ?? 'No Panel' }}</p>
                    </div>
                </div>

                <!-- Description -->
                <div class="border-t border-gray-200 pt-6">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Description</label>
                    <div class="text-sm text-gray-700 leading-relaxed bg-gray-50 p-4 rounded-lg border border-gray-200">
                        {{ $task->description ?? 'No description provided.' }}
                    </div>
                </div>

                <!-- Metadata -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 pt-6 border-t border-gray-200">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Created By</label>
                        <p class="text-sm text-gray-900 font-medium">{{ $task->creator->name ?? 'Unknown' }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ optional($task->created_at)->format('F j, Y \a\t g:i A') }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Last Updated</label>
                        <p class="text-xs text-gray-500">{{ optional($task->updated_at)->format('F j, Y \a\t g:i A') ?? 'Never' }}</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex flex-wrap items-center gap-3">
                @if(auth()->check() && auth()->user()->role === 'member')
                    @if(optional($task->user)->id === auth()->id())
                        <form method="POST" action="{{ route('tasks.changeState', $task->id) }}" class="inline">
                            @csrf
                            <input type="hidden" name="state" value="working">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-semibold text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Start Working
                            </button>
                        </form>

                        <form method="POST" action="{{ route('tasks.changeState', $task->id) }}" class="inline">
                            @csrf
                            <input type="hidden" name="state" value="submitted to review">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-all duration-200 font-semibold text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Submit for Review
                            </button>
                        </form>
                    @endif
                @else
                    <form method="POST" action="{{ route('tasks.changeState', $task->id) }}" class="inline">
                        @csrf
                        <input type="hidden" name="state" value="working">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-semibold text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Start Working
                        </button>
                    </form>

                    <form method="POST" action="{{ route('tasks.changeState', $task->id) }}" class="inline">
                        @csrf
                        <input type="hidden" name="state" value="submitted to review">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-all duration-200 font-semibold text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Submit for Review
                        </button>
                    </form>

                    <form method="POST" action="{{ route('tasks.changeState', $task->id) }}" class="inline">
                        @csrf
                        <input type="hidden" name="state" value="completed">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-black transition-all duration-200 font-semibold text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Mark Complete
                        </button>
                    </form>

                    <button type="button" onclick="openAssignModal()" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-semibold text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        Assign User
                    </button>
                @endif
            </div>
        </div>

        <!-- Activity Timeline Section -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="px-6 py-5 bg-gray-800">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-xl font-bold text-white">Activity History</h2>
                        <p class="text-gray-300 text-sm">Complete timeline of all changes and updates</p>
                    </div>
                </div>
            </div>

            <div class="p-6">

                @if($histories->isEmpty())
                    <div class="text-center py-16">
                        <div class="inline-block bg-gray-100 rounded-full p-8 mb-4">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500 text-lg font-medium">No activity yet</p>
                        <p class="text-gray-400 text-sm mt-2">Changes to this task will appear here</p>
                    </div>
                @else
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

                    <div class="relative border-l-2 border-gray-200 ml-4 space-y-8">
                        @foreach($histories as $h)
                            <div class="ml-8 pb-8 border-b border-gray-100 last:border-0 last:pb-0">
                                <!-- Timeline Dot -->
                                <span class="absolute flex items-center justify-center w-10 h-10 bg-gray-200 rounded-full -left-5 ring-4 ring-white">
                                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                </span>
                                
                                <!-- Timeline Header -->
                                <div class="mb-3">
                                    <h4 class="text-base font-bold text-gray-900 capitalize">
                                        {{ $h->action }}
                                    </h4>
                                    <p class="text-sm text-gray-600 mt-1">
                                        by {{ $h->user->name ?? 'System' }} • 
                                        <time class="text-gray-500">{{ optional($h->created_at)->format('F j, Y \a\t g:i A') }}</time>
                                    </p>
                                </div>

                                <!-- Change Details -->
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                    @php
                                        $details = [];
                                        try { 
                                            $details = $h->details ? json_decode($h->details, true) : []; 
                                        } catch (\Throwable $e) { 
                                            $details = ['raw' => $h->details]; 
                                        }
                                    @endphp

                                    @if(isset($details['changes']) && is_array($details['changes']))
                                        <div class="space-y-3">
                                            @foreach($details['changes'] as $field => $vals)
                                                @php
                                                    $label = ucwords(str_replace('_', ' ', preg_replace('/_id$/', '', $field)));
                                                @endphp
                                                <div class="flex flex-col sm:flex-row sm:items-start text-sm">
                                                    <div class="w-40 font-bold text-gray-700 text-xs uppercase tracking-wide mb-2 sm:mb-0 flex-shrink-0">{{ $label }}</div>
                                                    <div class="flex-1 flex flex-col sm:flex-row sm:items-center gap-2">
                                                        <div class="px-3 py-1.5 bg-gray-200 text-gray-700 rounded text-xs line-through">
                                                            {{ renderFieldValue($field, $vals['old'] ?? null) }}
                                                        </div>
                                                        <svg class="w-4 h-4 text-gray-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                                        </svg>
                                                        <div class="px-3 py-1.5 bg-gray-900 text-white rounded text-xs font-bold">
                                                            {{ renderFieldValue($field, $vals['new'] ?? null) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif(isset($details['attributes']) && is_array($details['attributes']))
                                        <div class="space-y-2">
                                            @foreach($details['attributes'] as $k => $v)
                                                <div class="flex justify-between text-sm py-1">
                                                    <span class="text-gray-600 font-semibold">{{ ucwords(str_replace('_', ' ', $k)) }}</span>
                                                    <span class="text-gray-900 font-medium">{{ renderFieldValue($k, $v) }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <pre class="whitespace-pre-wrap text-xs text-gray-700 bg-white p-3 rounded border border-gray-300 font-mono">{{ json_encode($details, JSON_PRETTY_PRINT) }}</pre>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Assign User modal logic
    function openAssignModal() {
        let modal = document.getElementById('assignUserModal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'assignUserModal';
            modal.className = 'fixed inset-0 z-50 hidden items-center justify-center backdrop-blur-sm';
            modal.innerHTML = `
                <div class="fixed inset-0 bg-black/50 transition-opacity" aria-hidden="true" onclick="closeAssignModal()"></div>
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 z-10 overflow-hidden transform transition-all">
                    <div class="px-6 py-5 bg-gray-800">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-xl font-bold text-white">Assign User</h3>
                                <p class="text-gray-300 text-sm mt-0.5">Search for a team member to assign this task</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="relative">
                            <svg class="w-5 h-5 absolute left-3 top-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input id="assign_user_search" type="search" class="w-full pl-10 pr-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-all text-sm" placeholder="Search by name or student ID...">
                        </div>
                        <div id="assignSearchResults" class="mt-3 max-h-60 overflow-y-auto hidden border-2 border-gray-200 rounded-xl divide-y divide-gray-100 shadow-sm"></div>
                    </div>
                    <form id="assignUserForm" method="POST" action="{{ route('tasks.assign', $task->id) }}">
                        @csrf
                        <input type="hidden" name="user_id" id="assign_user_id">
                    </form>
                    <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-100">
                        <button type="button" onclick="closeAssignModal()" class="inline-flex items-center px-5 py-2.5 border-2 border-gray-300 text-sm font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">Cancel</button>
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
                    <p class="text-sm text-gray-500">No users found</p>
                </div>`;
            wrapper.classList.remove('hidden');
            return;
        }

        wrapper.innerHTML = items.map(u => `
            <button type="button" class="w-full text-left px-4 py-3 hover:bg-gray-100 transition-colors group flex items-center justify-between" data-id="${u.id}" data-name="${u.name}">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-700 font-bold text-xs mr-3">
                        ${u.name.charAt(0).toUpperCase()}
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-gray-900">${u.name}</div>
                        <div class="text-xs text-gray-500">${u.student_id ? 'ID: ' + u.student_id : u.email}</div>
                    </div>
                </div>
                <svg class="w-5 h-5 text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
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