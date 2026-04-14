@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto">
    <div class="glass-card shadow-2xl shadow-blue-200/50 rounded-3xl p-10 border border-white">
        <div class="mb-10 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-50 rounded-2xl mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8-0v4h8z" />
                </svg>
            </div>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Welcome Back</h2>
            <p class="text-slate-500 mt-2">Please enter your details to sign in</p>
        </div>

        <form action="/login" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Username</label>
                <input type="text" name="username" placeholder="Enter your ID" class="w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-3 focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all outline-none border">
                @error('username') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Password</label>
                <input type="password" name="password" placeholder="••••••••" class="w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-3 focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all outline-none border">
                @error('password') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3.5 rounded-xl transition-all shadow-lg shadow-blue-200 active:scale-[0.98]">
                Sign In
            </button>
            
            <div class="relative flex py-3 items-center">
                <div class="flex-grow border-t border-slate-200"></div>
                <span class="flex-shrink mx-4 text-slate-400 text-sm">or</span>
                <div class="flex-grow border-t border-slate-200"></div>
            </div>

            <a href="/register" class="block w-full text-center bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold py-3.5 rounded-xl transition-all">
                Create Account
            </a>
        </form>
    </div>
</div>
@endsection