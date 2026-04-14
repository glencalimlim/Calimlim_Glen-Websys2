@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900">System Activity</h2>
            <p class="text-slate-500">A detailed audit trail of your account actions</p>
        </div>
        <a href="/dashboard" class="bg-white border border-slate-200 text-slate-700 px-6 py-3 rounded-xl font-bold hover:bg-slate-50 transition shadow-sm">
            Back to Dashboard
        </a>
    </div>

    <div class="glass-card rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-slate-400">Event</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-slate-400">Description</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-slate-400 text-right">Timestamp</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($logs as $log)
                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <td class="px-6 py-5">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $log->event == 'Login' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ $log->event }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-slate-600 font-medium">
                            {{ $log->description }}
                        </td>
                        <td class="px-6 py-5 text-right text-slate-400 font-mono text-sm">
                            {{ date('M d, Y • H:i', strtotime($log->created_at)) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection