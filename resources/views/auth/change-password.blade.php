<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - CS Task Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <!-- Header Card -->
            <div class="bg-gray-800 rounded-t-2xl px-8 py-6 shadow-xl">
                <div class="flex items-center justify-center space-x-3">
                    <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white">Change Password</h2>
                        <p class="text-sm text-white/70 mt-0.5">First time login - security required</p>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-b-2xl shadow-xl px-8 py-8">
                <!-- Warning Message -->
                @if(session('warning'))
                <div class="mb-6 px-4 py-3 rounded-xl border-2 border-yellow-400 bg-gradient-to-r from-yellow-50 to-amber-50">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <span class="text-sm font-medium text-yellow-800">{{ session('warning') }}</span>
                    </div>
                </div>
                @endif

                <!-- Info Box -->
                <div class="mb-6 px-4 py-3 bg-gray-100 rounded-xl border-2 border-gray-300">
                    <p class="text-sm text-gray-700">
                        <span class="font-semibold text-gray-900">⚠️ Security Notice:</span> You must change your default password before accessing the system. Choose a strong password with at least 8 characters.
                    </p>
                </div>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    
                    <!-- Current Password -->
                    <div class="mb-5">
                        <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">
                            Current Password <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="password" 
                            id="current_password" 
                            name="current_password" 
                            required 
                            autofocus
                            class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-800 transition-all @error('current_password') border-red-500 @enderror" 
                            placeholder="Enter your current password">
                        @error('current_password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="mb-5">
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                            New Password <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required 
                            class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-800 transition-all @error('password') border-red-500 @enderror" 
                            placeholder="Enter new password (min. 8 characters)">
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                            Confirm New Password <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            required 
                            class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-800 transition-all" 
                            placeholder="Re-enter new password">
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full inline-flex items-center justify-center space-x-2 px-6 py-3.5 text-base font-semibold rounded-xl text-white bg-gray-800 hover:bg-black transition-all shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Change Password & Continue</span>
                    </button>

                    <!-- Logout Link -->
                    <div class="mt-4 text-center">
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-gray-600 hover:text-gray-800 font-medium transition-colors">
                                Or logout and return to login page
                            </button>
                        </form>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
