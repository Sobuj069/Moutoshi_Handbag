@extends('layouts.app')

@section('title', 'Customer Login | The Patchee BD')

@section('content')

    <div class="max-w-md mx-auto px-4 py-16 sm:py-24">
        <div class="text-center mb-8">
            <span class="text-xs font-bold tracking-[0.25em] uppercase text-[#c9a66b] block mb-2">MY ACCOUNT</span>
            <h1 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-[0.15em] text-neutral-900 font-sans">
                SIGN IN
            </h1>
            <p class="text-xs text-neutral-500 mt-1">Access your order history and saved delivery details</p>
        </div>

        <div class="bg-white border border-neutral-200 p-8 shadow-xs">
            @if($errors->any())
                <div class="mb-5 p-3.5 bg-red-50 text-red-700 text-xs rounded-xs">
                    @foreach($errors->all() as $err)
                        <p>{{ $err }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('customer.login.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Email Address *</label>
                    <input type="email" name="email" required value="{{ old('email', 'customer@thepatchee.com') }}"
                           class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black transition">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Password *</label>
                    <input type="password" name="password" required value="password"
                           class="w-full text-xs px-4 py-3 border border-neutral-300 focus:outline-none focus:border-black transition">
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 text-neutral-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="text-black">
                        <span>Remember me</span>
                    </label>
                    <span class="text-[11px] text-neutral-400">Demo: customer@thepatchee.com</span>
                </div>

                <button type="submit" 
                        class="w-full bg-[#1c1c1c] text-white py-3.5 px-6 text-xs font-bold tracking-[0.2em] uppercase hover:bg-black transition shadow-sm">
                    SIGN IN
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-neutral-200 text-center text-xs text-neutral-600">
                <span>Don't have an account yet?</span>
                <a href="{{ route('customer.register') }}" class="font-bold text-black hover:text-[#c9a66b] uppercase tracking-wider ml-1 underline">
                    Create Account
                </a>
            </div>
        </div>

    </div>

@endsection
