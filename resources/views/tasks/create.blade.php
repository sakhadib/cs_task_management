@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h1 class="text-lg font-bold mb-4">Create Task</h1>

            <form method="POST" action="{{ route('tasks.store') }}">
                @csrf
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title</label>
                        <input name="title" value="{{ old('title') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg" rows="4">{{ old('description') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Team (choose from current panel)</label>
                        <select name="team_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg">
                            <option value="">-- No team --</option>
                            @foreach($teams as $team)
                                <option value="{{ $team->id }}" @if(old('team_id') == $team->id) selected @endif>{{ $team->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('tasks.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg">Create Task</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
