@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Create Panel</h1>
                <p class="mt-2 text-sm text-gray-600">Add a new panel.</p>
            </div>
            <a href="{{ route('panels.index') }}" class="text-indigo-600 hover:text-indigo-900">Back to Panels</a>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-lg bg-red-50 p-4 border-l-4 border-red-500 shadow-sm">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <form action="{{ route('panels.store') }}" method="POST">
                @csrf
                <div class="px-6 py-8 space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <div class="mt-1">
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Panel Name">
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <div class="mt-1">
                            <textarea name="description" id="description" rows="4" class="block w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Optional description">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-100">
                    <a href="{{ route('panels.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700">Create Panel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection