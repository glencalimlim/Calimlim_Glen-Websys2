@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <nav class="text-sm text-slate-500 mb-2">Portal / Dashboard</nav>
            <h2 class="text-4xl font-extrabold text-slate-900 leading-none">Hi, {{ $student->first_name }}!</h2>
            <p class="text-slate-500 mt-2 font-medium">Student ID: <span class="text-blue-600">{{ $student->student_no }}</span></p>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="/edit-profile" class="inline-flex items-center gap-2 bg-white border border-slate-200 text-slate-700 px-5 py-2.5 rounded-xl font-semibold hover:bg-slate-50 transition shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                Edit Profile
            </a>
            <a href="/logs" class="inline-flex items-center gap-2 bg-slate-800 text-white px-5 py-2.5 rounded-xl font-semibold hover:bg-slate-900 transition shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Activity Logs
            </a>
            <a href="/logout" class="bg-red-50 text-red-600 px-5 py-2.5 rounded-xl font-semibold hover:bg-red-100 transition">Logout</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 glass-card rounded-3xl shadow-sm border border-slate-200 p-8">
            <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                <span class="w-1.5 h-6 bg-blue-600 rounded-full"></span>
                Personal Information
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Full Name</p>
                    <p class="text-slate-900 font-medium text-lg">{{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Email Address</p>
                    <p class="text-slate-900 font-medium text-lg">{{ $student->email }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Phone Number</p>
                    <p class="text-slate-900 font-medium text-lg">{{ $student->phone }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Gender</p>
                    <p class="text-slate-900 font-medium text-lg">{{ $student->gender }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Home Address</p>
                    <p class="text-slate-900 font-medium text-lg">{{ $student->address }}</p>
                </div>
            </div>
        </div>

        <div class="glass-card rounded-3xl shadow-sm border border-slate-200 p-8">
            <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                <span class="w-1.5 h-6 bg-blue-600 rounded-full"></span>
                Academic Status
            </h3>
            <div class="space-y-6">
                <div class="bg-blue-50 rounded-2xl p-5 border border-blue-100">
                    <p class="text-blue-600 text-xs font-bold uppercase tracking-widest mb-1">Current Course</p>
                    <p class="text-blue-900 font-bold text-xl">{{ $student->course }}</p>
                </div>
                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                    <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-1">Year Level</p>
                    <p class="text-slate-900 font-bold text-xl">{{ $student->year_level }}</p>
                </div>
                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                    <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-1">Account Username</p>
                    <p class="text-slate-900 font-bold text-xl">{{ $student->username }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection