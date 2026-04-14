@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="glass-card shadow-2xl shadow-blue-200/50 rounded-3xl p-8 md:p-12 border border-white bg-white/90 backdrop-blur-sm relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-2 bg-blue-600"></div>

        <div class="mb-10 text-center">
            <h2 class="text-4xl font-extrabold text-slate-900 tracking-tight">Create Account</h2>
            <p class="text-slate-500 mt-2 text-lg">Enter your details to register</p>
        </div>

        <form action="/register" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
            @csrf

            <div class="md:col-span-2 border-b border-slate-100 pb-2">
                <h3 class="text-sm font-bold uppercase tracking-widest text-blue-600">Personal Identity</h3>
            </div>

            <div class="space-y-1">
                <label class="block text-sm font-semibold text-slate-700">Student Number</label>
                <input type="text" name="student_no" value="{{ old('student_no') }}" placeholder="2024-XXXXX" class="w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-3 border outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                @error('student_no') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div class="col-span-1">
                    <label class="block text-sm font-semibold text-slate-700">First Name</label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" class="w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-3 border outline-none">
                    @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="col-span-1">
                    <label class="block text-sm font-semibold text-slate-700">Middle Name</label>
                    <input type="text" name="middle_name" value="{{ old('middle_name') }}" class="w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-3 border outline-none">
                </div>
                <div class="col-span-1">
                    <label class="block text-sm font-semibold text-slate-700">Last Name</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" class="w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-3 border outline-none">
                    @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="space-y-1">
                <label class="block text-sm font-semibold text-slate-700">Birthdate</label>
                <input type="date" name="birthdate" value="{{ old('birthdate') }}" class="w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-3 border outline-none">
                @error('birthdate') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1">
                <label class="block text-sm font-semibold text-slate-700">Gender</label>
                <select name="gender" class="w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-3 border outline-none bg-white">
                    <option value="">Select Gender</option>
                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
                @error('gender') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2 border-b border-slate-100 pb-2 mt-4">
                <h3 class="text-sm font-bold uppercase tracking-widest text-blue-600">Institutional & Contact</h3>
            </div>

            <div class="space-y-1">
                <label class="block text-sm font-semibold text-slate-700">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-3 border outline-none">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1">
                <label class="block text-sm font-semibold text-slate-700">Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-3 border outline-none">
                @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1">
                <label class="block text-sm font-semibold text-slate-700">Course</label>
                <input type="text" name="course" value="{{ old('course') }}" class="w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-3 border outline-none">
                @error('course') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1">
                <label class="block text-sm font-semibold text-slate-700">Year Level</label>
                <select name="year_level" class="w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-3 border outline-none bg-white">
                    <option value="1st Year">1st Year</option>
                    <option value="2nd Year">2nd Year</option>
                    <option value="3rd Year">3rd Year</option>
                    <option value="4th Year">4th Year</option>
                </select>
            </div>

            <div class="md:col-span-2 space-y-1">
                <label class="block text-sm font-semibold text-slate-700">Home Address</label>
                <textarea name="address" rows="2" class="w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-3 border outline-none">{{ old('address') }}</textarea>
                @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2 border-b border-slate-100 pb-2 mt-4">
                <h3 class="text-sm font-bold uppercase tracking-widest text-blue-600">Security</h3>
            </div>

            <div class="space-y-1">
                <label class="block text-sm font-semibold text-slate-700">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" class="w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-3 border outline-none">
                @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block text-sm font-semibold text-slate-700">Password</label>
                    <input type="password" name="password" class="w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-3 border outline-none">
                </div>
                <div class="space-y-1">
                    <label class="block text-sm font-semibold text-slate-700">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-3 border outline-none">
                </div>
                @error('password') <p class="text-red-500 text-xs col-span-2 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2 pt-6">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl transition-all shadow-lg shadow-blue-200 active:scale-[0.98]">
                    Register Account
                </button>
            </div>
        </form>
    </div>
</div>
@endsection