@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 pb-12">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Edit Profile</h2>
            <p class="text-slate-500 mt-1">Manage your account details and academic status</p>
        </div>
        <a href="/dashboard" class="inline-flex items-center gap-2 text-slate-500 hover:text-blue-600 font-bold transition-all group bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Dashboard
        </a>
    </div>

    <div class="glass-card shadow-2xl shadow-slate-200/60 rounded-3xl border border-white relative overflow-hidden bg-white/90 backdrop-blur-md">
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-600 to-indigo-600"></div>
        
        <form action="/update-profile" method="POST" class="p-8 md:p-12 space-y-10">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="md:col-span-1">
                    <h3 class="text-lg font-bold text-slate-800">Account Identity</h3>
                    <p class="text-sm text-slate-500">Institutional identifiers and login credentials.</p>
                </div>
                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-400">Student Number</label>
                        <input type="text" name="student_no" value="{{ old('student_no', $student->student_no) }}" 
                            class="w-full rounded-xl border border-slate-200 bg-slate-100 px-4 py-3 text-slate-500 font-medium outline-none">
                        @error('student_no') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-400">Username</label>
                        <input type="text" name="username" value="{{ old('username', $student->username) }}" 
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none transition-all font-medium">
                        @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="md:col-span-1">
                    <h3 class="text-lg font-bold text-slate-800">Personal Details</h3>
                    <p class="text-sm text-slate-500">Your legal name and demographic information.</p>
                </div>
                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-400">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $student->first_name) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-400">Middle Name</label>
                        <input type="text" name="middle_name" value="{{ old('middle_name', $student->middle_name) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-400">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $student->last_name) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-400">Birthdate</label>
                        <input type="date" name="birthdate" value="{{ old('birthdate', $student->birthdate) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-400">Gender</label>
                        <select name="gender" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none appearance-none bg-white">
                            <option value="Male" {{ old('gender', $student->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $student->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="md:col-span-1">
                    <h3 class="text-lg font-bold text-slate-800">Contact & Academics</h3>
                    <p class="text-sm text-slate-500">How the institution can reach you and your current studies.</p>
                </div>
                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-400">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $student->email) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-400">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $student->phone) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-400">Address</label>
                        <textarea name="address" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">{{ old('address', $student->address) }}</textarea>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-400">Course</label>
                        <input type="text" name="course" value="{{ old('course', $student->course) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-400">Year Level</label>
                        <select name="year_level" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none appearance-none bg-white">
                            <option value="1st Year" {{ old('year_level', $student->year_level) == '1st Year' ? 'selected' : '' }}>1st Year</option>
                            <option value="2nd Year" {{ old('year_level', $student->year_level) == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                            <option value="3rd Year" {{ old('year_level', $student->year_level) == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                            <option value="4th Year" {{ old('year_level', $student->year_level) == '4th Year' ? 'selected' : '' }}>4th Year</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="pt-8 flex justify-end gap-4">
                <button type="submit" class="px-12 bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl transition-all shadow-xl shadow-blue-200 active:scale-95">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection