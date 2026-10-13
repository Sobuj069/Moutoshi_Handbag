<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Moutoshi Handbag BD</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral-900 min-h-screen flex items-center justify-center p-4 selection:bg-[#c9a66b] selection:text-white">
    <div class="bg-white w-full max-w-md p-8 sm:p-10 shadow-2xl border border-neutral-800">
        
        <div class="text-center mb-8">
            <a href="/" class="inline-block mb-3">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Moutoshi Handbag" class="h-14 mx-auto object-contain">
            </a>
            <span class="block text-[11px] tracking-[0.25em] uppercase text-neutral-500 font-bold">
                ADMINISTRATION PORTAL
            </span>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-xs">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">
                    Email Address
                </label>
                <input type="email" name="email" required value="{{ old('email', 'admin@thepatchee.com') }}"
                       class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black transition">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1.5">
                    Password
                </label>
                <input type="password" name="password" required value="password"
                       class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black transition">
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 text-neutral-600">
                    <input type="checkbox" name="remember" class="text-black">
                    <span>Remember me</span>
                </label>
                <span class="text-[11px] text-neutral-400">Default: admin@thepatchee.com / password</span>
            </div>

            <button type="submit" 
                    class="w-full bg-[#1c1c1c] text-white py-3.5 px-6 text-xs font-bold tracking-[0.2em] uppercase hover:bg-black transition shadow-md">
                SECURE SIGN IN
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-neutral-200 text-center">
            <a href="{{ route('home') }}" class="text-xs text-neutral-500 hover:text-black uppercase tracking-wider">
                ← Return to Storefront
            </a>
        </div>
    </div>
</body>
</html>
