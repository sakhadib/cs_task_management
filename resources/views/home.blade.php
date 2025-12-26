<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Internal IUT Computer Society</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Inter font from Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        .card-shadow {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #60a5fa 100%);
        }
        .brand-gradient {
            background: linear-gradient(90deg, #1e40af 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-6xl flex flex-col md:flex-row rounded-2xl overflow-hidden card-shadow">
        <!-- Left side - Brand and Info -->
        <div class="gradient-bg text-white p-8 md:p-12 md:w-2/5">
            <div class="flex flex-col h-full justify-between">
                <div>
                    <div class="flex items-center mb-8">
                        <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center mr-4">
                            <i class="fas fa-laptop-code text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold">Internal</h1>
                            <p class="text-blue-100">IUT Computer Society</p>
                        </div>
                    </div>
                    
                    <h2 class="text-3xl font-bold mb-6">Secure Member Portal</h2>
                    <p class="text-blue-100 mb-8">
                        Access exclusive resources, member tools, and community features for the IUT Computer Society. This portal is for authorized members only.
                    </p>
                    
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <i class="fas fa-shield-alt w-6 text-blue-200 mr-3"></i>
                            <span class="text-blue-100">Enterprise-grade security</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-users w-6 text-blue-200 mr-3"></i>
                            <span class="text-blue-100">Exclusive member community</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-tools w-6 text-blue-200 mr-3"></i>
                            <span class="text-blue-100">Development resources & tools</span>
                        </div>
                    </div>
                </div>
                
                <div class="mt-10 pt-6 border-t border-blue-300/30">
                    <p class="text-sm text-blue-200">
                        <i class="fas fa-info-circle mr-2"></i>
                        Need help accessing your account? Contact the system administrator.
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Right side - Login Form -->
        <div class="bg-white p-8 md:p-12 md:w-3/5">
            <div class="max-w-md mx-auto">
                <div class="mb-10">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Member Login</h2>
                    <p class="text-gray-600">Sign in to your Internal IUT Computer Society account</p>
                </div>
                
                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-envelope mr-2 text-blue-500"></i>Email Address
                        </label>
                        <div class="relative">
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email') }}"
                                required 
                                autofocus
                                class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg input-focus focus:outline-none focus:border-blue-500 transition duration-200"
                                placeholder="member@iutcs.org"
                            >
                            <div class="absolute left-3 top-3.5 text-gray-400">
                                <i class="fas fa-user-circle"></i>
                            </div>
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Password Input -->
                    <div>
                        <div class="flex justify-between mb-1">
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-lock mr-2 text-blue-500"></i>Password
                            </label>
                            
                        </div>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                required 
                                class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg input-focus focus:outline-none focus:border-blue-500 transition duration-200"
                                placeholder="Enter your password"
                            >
                            <div class="absolute left-3 top-3.5 text-gray-400">
                                <i class="fas fa-key"></i>
                            </div>
                            <button type="button" class="absolute right-3 top-3.5 text-gray-400" onclick="togglePasswordVisibility()">
                                <i class="fas fa-eye" id="password-toggle-icon"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    
                    
                    <!-- Submit Button -->
                    <button type="submit" class="w-full gradient-bg text-white font-semibold py-3 px-4 rounded-lg hover:opacity-95 transition duration-200 flex items-center justify-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Sign In to Portal
                    </button>
                    
                    
                </form>
                
                               
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="absolute bottom-4 w-full text-center">
        <p class="text-gray-500 text-sm">
            &copy; {{ date('Y') }} Internal IUT Computer Society. All rights reserved.
            <span class="mx-2">|</span>
            <a href="#" class="text-gray-600 hover:text-gray-800 hover:underline">Privacy Policy</a>
            <span class="mx-2">|</span>
            <a href="#" class="text-gray-600 hover:text-gray-800 hover:underline">Terms of Service</a>
        </p>
    </div>
    
    <script>
        // Toggle password visibility
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('password-toggle-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
        
        // Form validation
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.querySelector('form');
            
            // Add form submission feedback
            loginForm.addEventListener('submit', function(e) {
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;
                
                if (!email || !password) {
                    e.preventDefault();
                    alert('Please fill in all required fields.');
                    return;
                }
                
                // Show loading state
                const submitBtn = loginForm.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Authenticating...';
                submitBtn.disabled = true;
                
                // Form will submit normally - backend will handle it
            });
        });
    </script>
</body>
</html>