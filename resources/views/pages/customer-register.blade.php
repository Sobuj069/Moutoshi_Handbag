@extends('layouts.app')

@section('title', 'Register Account | The Patchee BD')

@section('content')

    <div class="max-w-md mx-auto px-4 py-16 sm:py-24">
        <div class="text-center mb-8">
            <span class="text-xs font-bold tracking-[0.25em] uppercase text-[#c9a66b] block mb-2">NEW CUSTOMER</span>
            <h1 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-[0.15em] text-neutral-900 font-sans">
                CREATE ACCOUNT
            </h1>
            <p class="text-xs text-neutral-500 mt-1">Join the Patchee movement for seamless tracking & member perks</p>
        </div>

        <div class="bg-white border border-neutral-200 p-8 shadow-xs">
            @if($errors->any())
                <div class="mb-5 p-3.5 bg-red-50 text-red-700 text-xs rounded-xs">
                    @foreach($errors->all() as $err)
                        <p>{{ $err }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('customer.register.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Your Full Name *</label>
                    <input type="text" name="name" required value="{{ old('name') }}" placeholder="e.g. Samia Rahman"
                           class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black transition">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Email Address *</label>
                    <input type="email" name="email" required value="{{ old('email') }}" placeholder="you@email.com"
                           class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black transition">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Mobile Phone Number *</label>
                    <input type="tel" name="phone" required value="{{ old('phone') }}" placeholder="01XXXXXXXXX"
                           class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black transition">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Password *</label>
                    <input type="password" name="password" required placeholder="Minimum 6 characters"
                           class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black transition">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Confirm Password *</label>
                    <input type="password" name="password_confirmation" required placeholder="Repeat password"
                           class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black transition">
                </div>

                <button type="submit" 
                        class="w-full bg-[#1c1c1c] text-white py-3.5 px-6 text-xs font-bold tracking-[0.2em] uppercase hover:bg-black transition shadow-sm">
                    CREATE ACCOUNT
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-neutral-200 text-center text-xs text-neutral-600">
                <span>Already have an account?</span>
                <a href="{{ route('customer.login') }}" class="font-bold text-black hover:text-[#c9a66b] uppercase tracking-wider ml-1 underline">
                    Sign In
                </a>
            </div>
        </div>

    </div>

@endsection
