<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Login - Aplikasi Warkom</title>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full bg-white p-8 rounded-xl shadow-sm border border-gray-100">
        <h1 class="text-2xl font-bold mb-6 text-center text-blue-600">Selamat Datang Masuk</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block font-medium mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded-lg p-2 outline-blue-500" required autofocus>
            </div>
            <div>
                <label class="block font-medium mb-1">Password</label>
                <input type="password" name="password" class="w-full border rounded-lg p-2 outline-blue-500" required>
            </div>
            <div class="pt-4">
                <button type="submit" class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-medium transition">Masuk</button>
            </div>
        </form>

        <p class="mt-6 text-center text-gray-600 text-sm">
            Belum punya akun? <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-medium">Daftar sekarang</a>
        </p>
    </div>
</body>
</html>
