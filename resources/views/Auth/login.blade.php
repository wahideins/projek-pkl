<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Your App</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Custom styles to use the Inter font */
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <!-- Login Card -->
    <div class="relative w-full max-w-md mx-auto bg-white rounded-2xl shadow-lg p-8 m-4">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Welcome Back!</h1>
            <p class="text-gray-500 mt-2">Please enter your details to sign in.</p>
        </div>

        <!-- Login Form -->
        <!-- Replace '#' with your actual login route, e.g., '{{ route('login') }}' in Blade -->
        <form action="#" method="POST">
            <!-- CSRF Token (important for Laravel) -->
            <!-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> -->

            <!-- Email Input -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    required 
                    autocomplete="email"
                    placeholder="you@example.com"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition duration-300"
                >
            </div>

            <!-- Password Input -->
            <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <label for="password" class="text-sm font-medium text-gray-700">Password</label>
                    <!-- Replace '#' with your password reset route -->
                    <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                        Forgot password?
                    </a>
                </div>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition duration-300"
                >
            </div>
            
            <!-- Remember Me Checkbox -->
            <div class="flex items-center mb-6">
                <input 
                    id="remember-me" 
                    name="remember" 
                    type="checkbox" 
                    class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                >
                <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                    Remember me
                </label>
            </div>

            <!-- Submit Button -->
            <div>
                <button 
                    type="submit" 
                    class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-transform transform hover:scale-105 duration-300"
                >
                    Log In
                </button>
            </div>

            <!-- Sign Up Link -->
            <div class="text-center mt-8">
                <p class="text-sm text-gray-600">
                    Don't have an account? 
                    <!-- Replace '#' with your register route -->
                    <a href="#" class="font-medium text-blue-600 hover:text-blue-500">
                        Sign up
                    </a>
                </p>
            </div>
        </form>
    </div>

</body>
</html>
