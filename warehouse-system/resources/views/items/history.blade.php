@extends('layouts.app')

@section('content')

<div class="page-header">
    <div class="page-header-row">
        <div>
            <div class="page-title">Stock History</div>
            <div class="page-sub">Transaction log and stock movement trends</div>
        </div>
        <a href="{{ route('items.index') }}" class="btn-secondary">
            <svg width="13" height="13" viewBox="0 0 13 13" fill="none"><path d="M8 2L4 6.5 8 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Back
        </a>
    </div>
</div>

@if(session('success'))
    <div class="flash-success">{{ session('success') }}</div>
@endif

@php
    $totalIn  = $transactions->where('type','in')->sum('quantity');
    $totalOut = $transactions->where('type','out')->sum('quantity');
@endphp

<div class="stat-grid" style="grid-template-columns:repeat(3,1fr);max-width:500px;margin-bottom:20px">
    <div class="stat-card">
        <div class="stat-label">Transactions</div>
        <div class="stat-val blue">{{ $transactions->count() }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total In</div>
        <div class="stat-val green">+{{ number_format($totalIn) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Out</div>
        <div class="stat-val red">-{{ number_format($totalOut) }}</div>
    </div>
</div>

{{-- Chart --}}
<div class="chart-card">
    <div class="chart-title">Stock Movement — Last 14 Days</div>
    <canvas id="stockChart" height="80"></canvas>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('items.history') }}" class="toolbar">
    <select name="type" class="filter-select">
        <option value="">All types</option>
        <option value="in"  {{ request('type') == 'in'  ? 'selected' : '' }}>Stock In</option>
        <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Stock Out</option>
    </select>
    <select name="supplier_id" class="filter-select">
        <option value="">All suppliers</option>
        @foreach($suppliers as $s)
            <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>
                {{ $s->name }}
            </option>
        @endforeach
    </select>
    <button type="submit" class="btn-secondary">Filter</button>
    @if(request('type') || request('supplier_id'))
        <a href="{{ route('items.history') }}" class="btn-secondary" style="color:#9ca3af">Clear</a>
    @endif
</form>

{{-- Table --}}
<div class="table-wrap">
    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Item</th>
                <th>Type</th>
                <th>Qty</th>
                <th>Supplier</th>
                <th>Date &amp; Time</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $i => $t)
            <tr>
                <td class="mono" style="color:#9ca3af;font-size:12px">{{ $i + 1 }}</td>
                <td style="font-weight:500;color:#111827">{{ $t->item->item_name ?? 'Deleted Item' }}</td>
                <td>
                    @if($t->type == 'in')
                        <span class="badge badge-avail">Stock In</span>
                    @else
                        <span class="badge badge-out">Stock Out</span>
                    @endif
                </td>
                <td class="mono" style="font-weight:500;color:{{ $t->type == 'in' ? '#059669' : '#dc2626' }}">
                    {{ $t->type == 'in' ? '+' : '-' }}{{ $t->quantity }}
                </td>
                <td style="color:#6b7280;font-size:12px">{{ $t->supplier->name ?? '—' }}</td>
                <td class="mono" style="color:#9ca3af;font-size:12px" data-utc="{{ $t->created_at->toIso8601String() }}">
                    {{ $t->created_at->format('M d, Y  H:i:s') }}
                </td>
            </tr>
            @empty
            <tr class="empty-row">
                <td colspan="6">No transactions recorded yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    new Chart(document.getElementById('stockChart'), {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [
                {
                    label: 'Stock In',
                    data: @json($stockIn),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16,185,129,0.08)',
                    borderWidth: 2,
                    pointRadius: 3,
                    pointBackgroundColor: '#10b981',
                    tension: 0.3,
                    fill: true,
                },
                {
                    label: 'Stock Out',
                    data: @json($stockOut),
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239,68,68,0.06)',
                    borderWidth: 2,
                    pointRadius: 3,
                    pointBackgroundColor: '#ef4444',
                    tension: 0.3,
                    fill: true,
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: {
                    labels: { font: { family: 'DM Sans', size: 12 }, boxWidth: 10, padding: 16 }
                },
                tooltip: {
                    backgroundColor: '#111827',
                    titleFont: { family: 'DM Sans', size: 12 },
                    bodyFont: { family: 'DM Mono', size: 12 },
                    padding: 10,
                    cornerRadius: 6,
                }
            },
            scales: {
                x: {
                    grid: { color: '#f3f4f6' },
                    ticks: { font: { family: 'DM Mono', size: 11 }, color: '#9ca3af' }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#f3f4f6' },
                    ticks: { font: { family: 'DM Mono', size: 11 }, color: '#9ca3af', precision: 0 }
                }
            }
        }
    });

    // Convert all stored UTC timestamps to the user's local timezone
    document.querySelectorAll('td[data-utc]').forEach(td => {
        const d = new Date(td.dataset.utc);
        const formatted = d.toLocaleString('en-US', {
            month:  'short',
            day:    '2-digit',
            year:   'numeric',
            hour:   '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        });
        td.textContent = formatted;
    });
</script>

@endsection