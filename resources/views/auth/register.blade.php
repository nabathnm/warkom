<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Register - Warkom</title>
</head>
<body class="bg-white min-h-screen flex items-center justify-center font-['Poppins'] py-8">

    <!-- Card Container -->
    <div class="bg-white border border-gray-300 rounded-3xl w-full max-w-[700px] mx-4 py-12 px-8 sm:px-16 md:px-24 relative shadow-lg">
        
        <h1 class="text-black text-2xl font-semibold mb-8 text-center">Login / Sign up</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 text-xs mb-6 rounded sm:mx-10">
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.submit') }}" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <label class="block text-black text-sm mb-1.5 font-medium">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full bg-white border border-gray-300 text-black px-4 py-2.5 outline-none rounded-xl text-sm placeholder-gray-400 focus:border-[#681CA3] focus:ring-1 focus:ring-[#681CA3] transition-all" placeholder="Enter your Full Name here" required autofocus>
            </div>
            
            <div>
                <label class="block text-black text-sm mb-1.5 font-medium">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full bg-white border border-gray-300 text-black px-4 py-2.5 outline-none rounded-xl text-sm placeholder-gray-400 focus:border-[#681CA3] focus:ring-1 focus:ring-[#681CA3] transition-all" placeholder="Enter your Email here" required>
            </div>
            
            <div>
                <label class="block text-black text-sm mb-1.5 font-medium">Password</label>
                <input type="password" name="password" class="w-full bg-white border border-gray-300 text-black px-4 py-2.5 outline-none rounded-xl text-sm placeholder-gray-400 focus:border-[#681CA3] focus:ring-1 focus:ring-[#681CA3] transition-all" placeholder="Enter your Password here" required>
            </div>

            <div>
                <label class="block text-black text-sm mb-1.5 font-medium">Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full bg-white border border-gray-300 text-black px-4 py-2.5 outline-none rounded-xl text-sm placeholder-gray-400 focus:border-[#681CA3] focus:ring-1 focus:ring-[#681CA3] transition-all" placeholder="Confirm your Password here" required>
            </div>

            <div>
                <label class="block text-black text-sm mb-1.5 font-medium">Register As</label>
                <select name="role" class="w-full bg-white border border-gray-300 text-black px-4 py-2.5 outline-none rounded-xl text-sm focus:border-[#681CA3] focus:ring-1 focus:ring-[#681CA3] transition-all" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            
            <div class="pt-4 flex justify-center">
                <button type="submit" class="bg-[#681CA3] text-white px-16 py-3 rounded-lg font-medium shadow-md hover:opacity-90 transition-opacity">
                    Create Account
                </button>
            </div>
        </form>

        <div class="mt-6">
            <p class="text-black text-xs text-center">
                Already have an account? <a href="{{ route('login') }}" class="text-[#681CA3] hover:underline font-bold">Log in</a>
            </p>
        </div>

        <div class="mt-8 mb-6 relative flex items-center justify-center">
            <hr class="w-2/3 border-gray-300 absolute">
            <span class="bg-white px-3 text-black text-sm relative z-10 font-medium">- OR -</span>
        </div>

        <div class="flex flex-col sm:flex-row justify-center gap-6">
            <button type="button" class="flex items-center justify-center gap-3 border border-gray-300 rounded-full px-6 py-2.5 hover:bg-gray-50 transition-colors w-full sm:w-auto shadow-sm">
                <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"><path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/><path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/><path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/><path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/></svg>
                <span class="text-xs text-black font-medium">Sign up with Google</span>
            </button>
            <button type="button" class="flex items-center justify-center gap-3 border border-gray-300 rounded-full px-6 py-2.5 hover:bg-gray-50 transition-colors w-full sm:w-auto shadow-sm">
                <i class="fa-brands fa-apple text-xl text-black -mt-0.5"></i>
                <span class="text-xs text-black font-medium">Sign up with Apple</span>
            </button>
        </div>

    </div>
</body>
</html>
