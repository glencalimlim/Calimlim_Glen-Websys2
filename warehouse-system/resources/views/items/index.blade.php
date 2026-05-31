@extends('layouts.app')

@section('content')

@if(session('success'))
    <div class="flash-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="flash-error">{{ session('error') }}</div>
@endif

{{-- Header --}}
<div class="page-header">
    <div class="page-header-row">
        <div>
            <div class="page-title">Warehouse Items</div>
            <div class="page-sub">Manage inventory and track stock levels</div>
        </div>
        <a href="{{ route('items.create') }}" class="btn-primary">
            <svg width="13" height="13" viewBox="0 0 13 13" fill="none"><path d="M6.5 1v11M1 6.5h11" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
            Add Item
        </a>
    </div>
</div>

@php
    $totalItems = $items->count();
    $available  = $items->where('status','Available')->count();
    $outOfStock = $items->where('status','Out of Stock')->count();
    $totalUnits = $items->sum('quantity');
    $lowStock   = $items->filter(fn($i) => $i->quantity > 0 && $i->quantity <= $threshold)->count();

    $chartItems = $items->sortByDesc('quantity')->take(8)->values()->map(function($i) use ($threshold) {
        return [
            'name'     => $i->item_name,
            'quantity' => $i->quantity,
            'status'   => $i->status,
            'low'      => $i->quantity > 0 && $i->quantity <= $threshold,
        ];
    });
@endphp

{{-- Stats --}}
<div class="stat-grid" style="grid-template-columns:repeat(5,1fr)">
    <div class="stat-card">
        <div class="stat-label">Total Items</div>
        <div class="stat-val blue">{{ $totalItems }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Available</div>
        <div class="stat-val green">{{ $available }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Out of Stock</div>
        <div class="stat-val red">{{ $outOfStock }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Low Stock</div>
        <div class="stat-val" style="color:#f59e0b">{{ $lowStock }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Units</div>
        <div class="stat-val">{{ number_format($totalUnits) }}</div>
    </div>
</div>

{{-- Toolbar --}}
<form method="GET" action="{{ route('items.index') }}" class="toolbar">
    <div class="search-wrap">
        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#9ca3af">
            <circle cx="6" cy="6" r="4.5" stroke="currentColor" stroke-width="1.3"/>
            <path d="M10 10l2.5 2.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
        </svg>
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Search items..." class="search-input">
    </div>

    <select name="status" class="filter-select">
        <option value="">All statuses</option>
        <option value="Available"    {{ request('status') == 'Available'    ? 'selected' : '' }}>Available</option>
        <option value="Out of Stock" {{ request('status') == 'Out of Stock' ? 'selected' : '' }}>Out of Stock</option>
    </select>

    <button type="submit" class="btn-secondary">Filter</button>

    @if(request('search') || request('status') || request('sort') || request('date_from') || request('date_to'))
    <a href="{{ route('items.index') }}" class="btn-secondary" style="color:#9ca3af">Clear</a>
@endif

    @if($lowStock > 0)
        <div style="display:flex;align-items:center;gap:6px;background:#fef3c7;border:1px solid #fcd34d;color:#92400e;padding:6px 12px;border-radius:8px;font-size:12px;font-weight:500">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M7 1L1 13h12L7 1z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/><path d="M7 6v3M7 10.5v.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
            {{ $lowStock }} item{{ $lowStock > 1 ? 's' : '' }} below threshold ({{ $threshold }} units)
        </div>
    @endif

   <div style="margin-left:auto;display:flex;align-items:center;gap:8px;position:relative">

    {{-- Sort dropdown trigger --}}
    <button type="button" id="sort-btn" onclick="toggleSortDropdown()"
            style="display:flex;align-items:center;gap:6px;padding:7px 12px;border:1px solid #e5e7eb;border-radius:8px;font-size:12px;color:#374151;background:#fff;cursor:pointer;white-space:nowrap">
        <svg width="13" height="13" viewBox="0 0 13 13" fill="none"><path d="M1 3h11M3 6.5h7M5 10h3" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
        {{ request('sort') == 'updated_newest' ? 'Newest Updated' :
           (request('sort') == 'updated_oldest' ? 'Oldest Updated' :
           (request('sort') == 'qty_desc' ? 'Qty: High to Low' :
           (request('sort') == 'qty_asc' ? 'Qty: Low to High' :
           (request('sort') == 'name_asc' ? 'Name A–Z' :
           (request('sort') == 'name_desc' ? 'Name Z–A' : 'Sort & Filter'))))) }}
        @if(request('sort') || request('date_from') || request('date_to'))
            <span style="background:#2563eb;color:#fff;border-radius:10px;font-size:10px;padding:1px 6px;font-weight:600">●</span>
        @endif
        <svg width="10" height="10" viewBox="0 0 10 10" fill="none" style="margin-left:2px"><path d="M2 4l3 3 3-3" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </button>

    {{-- Dropdown panel --}}
    <div id="sort-dropdown"
         style="display:none;position:absolute;top:calc(100% + 6px);right:0;background:#fff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,0.08);z-index:100;min-width:220px;padding:8px">

        {{-- Sort options --}}
        <div style="font-size:10px;font-weight:600;color:#9ca3af;letter-spacing:.06em;text-transform:uppercase;padding:6px 10px 4px">Sort by</div>

        @foreach([
            'updated_newest' => 'Newest Updated',
            'updated_oldest' => 'Oldest Updated',
            'qty_desc'       => 'Qty: High to Low',
            'qty_asc'        => 'Qty: Low to High',
            'name_asc'       => 'Name A–Z',
            'name_desc'      => 'Name Z–A',
        ] as $val => $label)
        <button type="button"
                onclick="setSort('{{ $val }}')"
                style="width:100%;text-align:left;padding:7px 10px;border:none;background:{{ request('sort') == $val ? '#eff6ff' : 'transparent' }};color:{{ request('sort') == $val ? '#2563eb' : '#374151' }};font-size:12px;border-radius:7px;cursor:pointer;display:flex;align-items:center;justify-content:space-between"
                onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='{{ request('sort') == $val ? '#eff6ff' : 'transparent' }}'">
            {{ $label }}
            @if(request('sort') == $val)
                <svg width="11" height="11" viewBox="0 0 11 11" fill="none"><path d="M2 5.5l2.5 2.5 4.5-4.5" stroke="#2563eb" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
            @endif
        </button>
        @endforeach

        {{-- Divider --}}
        <div style="border-top:1px solid #f3f4f6;margin:8px 0"></div>

        {{-- Date range --}}
        <div style="font-size:10px;font-weight:600;color:#9ca3af;letter-spacing:.06em;text-transform:uppercase;padding:0 10px 6px">Date Range</div>
        <div style="padding:0 10px 4px;display:flex;flex-direction:column;gap:6px">
            <div style="display:flex;align-items:center;gap:8px">
                <span style="font-size:11px;color:#6b7280;width:28px">From</span>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       style="flex:1;padding:6px 8px;border:1px solid #e5e7eb;border-radius:7px;font-size:12px;color:#374151;outline:none"
                       onfocus="this.style.borderColor='#2563eb'" onblur="this.style.borderColor='#e5e7eb'">
            </div>
            <div style="display:flex;align-items:center;gap:8px">
                <span style="font-size:11px;color:#6b7280;width:28px">To</span>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       style="flex:1;padding:6px 8px;border:1px solid #e5e7eb;border-radius:7px;font-size:12px;color:#374151;outline:none"
                       onfocus="this.style.borderColor='#2563eb'" onblur="this.style.borderColor='#e5e7eb'">
            </div>
        </div>

        {{-- Apply / Clear --}}
        <div style="padding:8px 10px 4px;display:flex;gap:6px">
            <button type="submit"
                    style="flex:2;padding:7px;background:#2563eb;color:#fff;border:none;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer">
                Apply
            </button>
            @if(request('sort') || request('date_from') || request('date_to'))
            <a href="{{ route('items.index', array_filter(['search' => request('search'), 'status' => request('status')])) }}"
               style="flex:1;padding:7px;background:#f3f4f6;color:#6b7280;border:none;border-radius:7px;font-size:12px;font-weight:500;cursor:pointer;text-align:center;text-decoration:none">
                Clear
            </a>
            @endif
        </div>
    </div>

    {{-- Hidden sort input so value persists --}}
    <input type="hidden" name="sort" id="sort-hidden" value="{{ request('sort') }}">
</div>

<script>
function toggleSortDropdown() {
    const d = document.getElementById('sort-dropdown');
    d.style.display = d.style.display === 'none' ? 'block' : 'none';
}

function setSort(val) {
    document.getElementById('sort-hidden').value = val;
    // Update button highlight
    document.querySelectorAll('#sort-dropdown button[type=button]').forEach(b => {
        b.style.background = 'transparent';
        b.style.color = '#374151';
    });
    event.currentTarget.style.background = '#eff6ff';
    event.currentTarget.style.color = '#2563eb';
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    const btn = document.getElementById('sort-btn');
    const dd  = document.getElementById('sort-dropdown');
    if (dd && !dd.contains(e.target) && !btn.contains(e.target)) {
        dd.style.display = 'none';
    }
});
</script>
</form>

{{-- Cards --}}
@if($items->isEmpty())
    <div style="text-align:center;padding:60px 20px;color:#9ca3af;font-size:14px;">
        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" style="margin:0 auto 12px;display:block;opacity:0.3"><rect x="4" y="4" width="32" height="32" rx="4" stroke="currentColor" stroke-width="2"/><path d="M13 20h14M20 13v14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        No items found. <a href="{{ route('items.create') }}" style="color:#2563eb;">Add your first item →</a>
    </div>
@else
    <div class="items-grid">
        @foreach($items as $item)
        @php $isLow = $item->quantity > 0 && $item->quantity <= $threshold; @endphp
        <div class="item-card {{ $isLow ? 'item-card-low' : '' }}"
             onclick="openModal({{ $item->id }}, '{{ addslashes($item->item_name) }}', '{{ addslashes($item->category) }}', {{ $item->quantity }}, '{{ $item->status }}', '{{ addslashes($item->description ?? '') }}', '{{ addslashes($item->supplier->name ?? '') }}', {{ $isLow ? 'true' : 'false' }}, {{ $threshold }}, '{{ $item->updated_at->format('M d, Y h:i A') }}')">

            <div class="item-card-top">
                <div style="padding-right:10px">
                    <div class="item-name">{{ $item->item_name }}</div>
                    <div class="item-cat">{{ $item->category }}</div>
                </div>
                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px">
                    <span class="badge {{ $item->status == 'Available' ? 'badge-avail' : 'badge-out' }}">
                        {{ $item->status }}
                    </span>
                    @if($isLow)
                        <span style="font-size:10px;font-weight:600;color:#dc2626;background:#fee2e2;padding:2px 6px;border-radius:10px">
                            ⚠ Low Stock
                        </span>
                    @endif
                </div>
            </div>

            <hr class="item-divider">

            <div class="item-qty-row">
                <span class="item-qty-label">Qty in stock</span>
                <span class="item-qty-val" style="{{ $isLow ? 'color:#dc2626' : '' }}">{{ $item->quantity }}</span>
            </div>

            @if($item->supplier)
                <div class="item-supplier">
                    <svg width="10" height="10" viewBox="0 0 10 10" fill="none" style="display:inline;margin-right:3px"><path d="M1 7V4.5l2.5-2.5h3L9 4.5V7a.5.5 0 01-.5.5h-7A.5.5 0 011 7z" stroke="currentColor" stroke-width="1.1"/></svg>
                    {{ $item->supplier->name }}
                </div>
            @endif

            {{-- Last updated time --}}
            <div style="margin-top:6px;display:flex;align-items:center;justify-content:space-between">
    <span style="font-size:10px;color:#9ca3af;display:flex;align-items:center;gap:3px">
        <svg width="9" height="9" viewBox="0 0 11 11" fill="none"><circle cx="5.5" cy="5.5" r="4.5" stroke="currentColor" stroke-width="1.1"/><path d="M5.5 3v2.5l1.5 1" stroke="currentColor" stroke-width="1.1" stroke-linecap="round"/></svg>
        {{ $item->updated_at->format('M d, Y') }}
    </span>
    <span style="font-size:10px;color:#9ca3af;font-family:'DM Mono',monospace">
        {{ $item->updated_at->format('h:i A') }}
    </span>
</div>
        </div>
        @endforeach
    </div>
@endif

{{-- Charts --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:32px;margin-bottom:24px">
    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:20px 24px">
        <div style="font-size:13px;font-weight:600;color:#111827;margin-bottom:4px">Stock Levels</div>
        <div style="font-size:11px;color:#9ca3af;margin-bottom:16px">Quantity per item (top 8)</div>
        <canvas id="barChart" height="200"></canvas>
    </div>
    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:20px 24px">
        <div style="font-size:13px;font-weight:600;color:#111827;margin-bottom:4px">Stock Status</div>
        <div style="font-size:11px;color:#9ca3af;margin-bottom:16px">Available vs Out of Stock</div>
        <div style="display:flex;align-items:center;justify-content:center;gap:32px">
            <canvas id="doughnutChart" width="160" height="160" style="max-width:160px;max-height:160px"></canvas>
            <div style="display:flex;flex-direction:column;gap:10px">
                <div style="display:flex;align-items:center;gap:8px">
                    <span style="width:10px;height:10px;border-radius:50%;background:#10b981;flex-shrink:0"></span>
                    <span style="font-size:12px;color:#374151">Available</span>
                    <span style="font-size:14px;font-weight:600;color:#111827;margin-left:4px;font-family:'DM Mono',monospace">{{ $available }}</span>
                </div>
                <div style="display:flex;align-items:center;gap:8px">
                    <span style="width:10px;height:10px;border-radius:50%;background:#ef4444;flex-shrink:0"></span>
                    <span style="font-size:12px;color:#374151">Out of Stock</span>
                    <span style="font-size:14px;font-weight:600;color:#111827;margin-left:4px;font-family:'DM Mono',monospace">{{ $outOfStock }}</span>
                </div>
                @if($lowStock > 0)
                <div style="display:flex;align-items:center;gap:8px">
                    <span style="width:10px;height:10px;border-radius:50%;background:#f59e0b;flex-shrink:0"></span>
                    <span style="font-size:12px;color:#374151">Low Stock</span>
                    <span style="font-size:14px;font-weight:600;color:#f59e0b;margin-left:4px;font-family:'DM Mono',monospace">{{ $lowStock }}</span>
                </div>
                @endif
                <div style="margin-top:4px;padding-top:10px;border-top:1px solid #f3f4f6">
                    <div style="font-size:11px;color:#9ca3af">Stock Rate</div>
                    <div style="font-size:18px;font-weight:600;color:#2563eb;font-family:'DM Mono',monospace">
                        {{ $totalItems > 0 ? round(($available / $totalItems) * 100) : 0 }}%
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── ITEM DETAIL MODAL ── --}}
<div class="modal-overlay" id="modal-overlay" onclick="handleOverlayClick(event)">
    <div class="modal-box" id="modal-box">
        <button class="modal-close" onclick="closeModal()">&#x2715;</button>

      
<div style="margin:10px 0 6px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:8px 12px;display:flex;align-items:center;justify-content:space-between">
    <span style="font-size:11px;color:#9ca3af;display:flex;align-items:center;gap:4px">
        <svg width="11" height="11" viewBox="0 0 11 11" fill="none"><circle cx="5.5" cy="5.5" r="4.5" stroke="currentColor" stroke-width="1.1"/><path d="M5.5 3v2.5l1.5 1" stroke="currentColor" stroke-width="1.1" stroke-linecap="round"/></svg>
        Last Updated
    </span>
    <span id="m-updated" style="font-size:12px;font-weight:600;color:#374151;font-family:'DM Mono',monospace"></span>
</div>

        <div class="modal-item-name" id="m-name"></div>
        <div class="modal-meta">
            <span id="m-status-badge"></span>
            <span id="m-low-badge" style="display:none;font-size:10px;font-weight:600;color:#dc2626;background:#fee2e2;padding:2px 6px;border-radius:10px">⚠ Low Stock</span>
        </div>

        <div class="modal-row">
            <div class="modal-stat">
                <div class="modal-stat-label">Quantity</div>
                <div class="modal-stat-val" id="m-quantity"></div>
            </div>
            <div class="modal-stat">
                <div class="modal-stat-label">Category</div>
                <div style="font-size:13px;font-weight:500;color:#374151;margin-top:4px" id="m-cat-val"></div>
            </div>
        </div>
        <div class="modal-row">
            <div class="modal-stat" style="background:#eff6ff">
                <div class="modal-stat-label" style="color:#1d4ed8">Supplier</div>
                <div style="font-size:13px;font-weight:500;color:#1d4ed8;margin-top:4px" id="m-supplier"></div>
            </div>
            <div class="modal-stat" id="m-threshold-box" style="display:none;background:#fef3c7">
                <div class="modal-stat-label" style="color:#92400e">Threshold</div>
                <div style="font-size:13px;font-weight:500;color:#92400e;margin-top:4px" id="m-threshold-val"></div>
            </div>
        </div>

        {{-- Last updated time --}}
        <div style="margin:8px 0;font-size:11px;color:#9ca3af;display:flex;align-items:center;gap:4px">
            <svg width="11" height="11" viewBox="0 0 11 11" fill="none"><circle cx="5.5" cy="5.5" r="4.5" stroke="currentColor" stroke-width="1.1"/><path d="M5.5 3v2.5l1.5 1" stroke="currentColor" stroke-width="1.1" stroke-linecap="round"/></svg>
            Last updated: <span id="m-updated" style="font-weight:500;color:#6b7280"></span>
        </div>

        <div class="modal-desc" id="m-description"></div>

        <div class="modal-actions">
            <button id="m-sell-btn" onclick="openSellModal()" class="btn-primary" style="flex:1;justify-content:center;background:#10b981;border-color:#10b981">
                <svg width="13" height="13" viewBox="0 0 13 13" fill="none"><path d="M1 1h2l1.5 7h6l1-4H4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><circle cx="5.5" cy="11" r="1" fill="currentColor"/><circle cx="10.5" cy="11" r="1" fill="currentColor"/></svg>
                Sell
            </button>
            <a id="m-edit" href="#" class="btn-secondary" style="flex:1;justify-content:center">Edit</a>
            <form id="m-delete-form" method="POST" onsubmit="return confirm('Delete this item? This cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">Delete</button>
            </form>
            <button onclick="closeModal()" class="btn-secondary">Close</button>
        </div>
    </div>
</div>

{{-- ── SELL MODAL (Kiosk) ── --}}
<div class="modal-overlay" id="sell-overlay" onclick="handleSellOverlayClick(event)">
    <div class="modal-box" id="sell-box" style="max-width:420px">
        <button class="modal-close" onclick="closeSellModal()">&#x2715;</button>

        <div style="text-align:center;margin-bottom:16px">
            <div style="font-size:11px;font-weight:600;color:#10b981;letter-spacing:.08em;text-transform:uppercase;margin-bottom:4px">Sell Item</div>
            <div style="font-size:18px;font-weight:700;color:#111827" id="sell-item-name"></div>
        </div>

        {{-- Stock info --}}
        <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:12px 16px;margin-bottom:18px;display:flex;justify-content:space-between;align-items:center">
            <span style="font-size:12px;color:#166534;font-weight:500">Current Stock</span>
            <span style="font-size:22px;font-weight:700;color:#16a34a;font-family:'DM Mono',monospace" id="sell-stock"></span>
        </div>

        <form id="sell-form" method="POST" action="{{ route('sales.store') }}">
            @csrf
            <input type="hidden" name="item_id" id="sell-item-id">

            <div style="display:flex;flex-direction:column;gap:12px">
                <div>
                    <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:4px">Customer Name <span style="color:#ef4444">*</span></label>
                    <input type="text" name="customer_name" required placeholder="Enter customer name"
                           style="width:100%;padding:9px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:13px;outline:none;box-sizing:border-box"
                           onfocus="this.style.borderColor='#2563eb'" onblur="this.style.borderColor='#d1d5db'">
                </div>
                <div>
                    <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:4px">Contact (optional)</label>
                    <input type="text" name="customer_contact" placeholder="Phone or email"
                           style="width:100%;padding:9px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:13px;outline:none;box-sizing:border-box"
                           onfocus="this.style.borderColor='#2563eb'" onblur="this.style.borderColor='#d1d5db'">
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                    <div>
                        <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:4px">Quantity <span style="color:#ef4444">*</span></label>
                        <input type="number" name="quantity" id="sell-qty" required min="1" placeholder="0"
                               style="width:100%;padding:9px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:13px;outline:none;box-sizing:border-box"
                               onfocus="this.style.borderColor='#2563eb'" onblur="this.style.borderColor='#d1d5db'"
                               oninput="updateTotal()">
                    </div>
                    <div>
                        <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:4px">Unit Price (₱)</label>
                        <input type="number" name="unit_price" id="sell-price" min="0" step="0.01" placeholder="0.00"
                               style="width:100%;padding:9px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:13px;outline:none;box-sizing:border-box"
                               onfocus="this.style.borderColor='#2563eb'" onblur="this.style.borderColor='#d1d5db'"
                               oninput="updateTotal()">
                    </div>
                </div>

                {{-- Total preview --}}
                <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:10px 16px;display:flex;justify-content:space-between;align-items:center">
                    <span style="font-size:12px;color:#1d4ed8;font-weight:500">Total</span>
                    <span style="font-size:20px;font-weight:700;color:#1d4ed8;font-family:'DM Mono',monospace" id="sell-total">₱0.00</span>
                </div>
            </div>

            <div style="display:flex;gap:8px;margin-top:18px">
                <button type="button" onclick="closeSellModal()" class="btn-secondary" style="flex:1">Cancel</button>
                <button type="submit" class="btn-primary" style="flex:2;justify-content:center;background:#10b981;border-color:#10b981">
                    ✓ Confirm Sale
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .item-card-low { border-color:#fecaca !important; background:#fffafa !important; }
    .item-card-low:hover { border-color:#f87171 !important; box-shadow:0 2px 12px rgba(239,68,68,0.12) !important; }
    #sell-overlay.open { display:flex !important; }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const allItems = @json($chartItems);

new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
        labels: allItems.map(i => i.name.length > 16 ? i.name.slice(0,16)+'…' : i.name),
        datasets: [{
            label: 'Quantity',
            data: allItems.map(i => i.quantity),
            backgroundColor: allItems.map(i => i.low ? 'rgba(239,68,68,0.15)' : i.status === 'Available' ? 'rgba(16,185,129,0.15)' : 'rgba(239,68,68,0.12)'),
            borderColor: allItems.map(i => i.low ? '#ef4444' : i.status === 'Available' ? '#10b981' : '#ef4444'),
            borderWidth: 1.5, borderRadius: 6, borderSkipped: false,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: true,
        plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ` ${ctx.parsed.y} units` } } },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 11, family: 'DM Sans' }, color: '#6b7280' } },
            y: { beginAtZero: true, grid: { color: '#f3f4f6' }, ticks: { font: { size: 11, family: 'DM Sans' }, color: '#9ca3af', precision: 0 } }
        }
    }
});

new Chart(document.getElementById('doughnutChart'), {
    type: 'doughnut',
    data: {
        labels: ['Available', 'Out of Stock'],
        datasets: [{
            data: [{{ $available }}, {{ $outOfStock }}],
            backgroundColor: ['rgba(16,185,129,0.15)', 'rgba(239,68,68,0.12)'],
            borderColor: ['#10b981', '#ef4444'],
            borderWidth: 2, hoverOffset: 6,
        }]
    },
    options: {
        responsive: false, cutout: '70%',
        plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ` ${ctx.parsed} items` } } }
    }
});



// ── Item modal ──
const overlay    = document.getElementById('modal-overlay');
let currentItemId = null;

function openModal(id, name, category, quantity, status, description, supplier, isLow, threshold, updated) {
    currentItemId = id;
    document.getElementById('m-name').textContent        = name;
    document.getElementById('m-quantity').textContent    = quantity;
    document.getElementById('m-cat-val').textContent     = category;
    document.getElementById('m-description').textContent = description || 'No description provided.';
    document.getElementById('m-supplier').textContent    = supplier || 'No supplier';
    document.getElementById('m-updated').textContent     = updated;

    const badge = document.getElementById('m-status-badge');
    badge.textContent = status;
    badge.className   = 'badge ' + (status === 'Available' ? 'badge-avail' : 'badge-out');

    document.getElementById('m-low-badge').style.display = isLow ? 'inline-block' : 'none';

    const threshBox = document.getElementById('m-threshold-box');
    if (isLow) {
        threshBox.style.display = 'block';
        document.getElementById('m-threshold-val').textContent = `${quantity} / ${threshold} units`;
    } else {
        threshBox.style.display = 'none';
    }

    document.getElementById('m-quantity').style.color = isLow ? '#dc2626' : '#111827';
    document.getElementById('m-edit').href             = `/items/edit/${id}`;
    document.getElementById('m-delete-form').action    = `/items/${id}`;

    // Sell button — disable if out of stock
    const sellBtn = document.getElementById('m-sell-btn');
    if (status === 'Out of Stock' || quantity <= 0) {
        sellBtn.disabled = true;
        sellBtn.style.opacity = '0.4';
        sellBtn.style.cursor  = 'not-allowed';
    } else {
        sellBtn.disabled = false;
        sellBtn.style.opacity = '1';
        sellBtn.style.cursor  = 'pointer';
    }

    // Store for sell modal
    sellBtn.dataset.name     = name;
    sellBtn.dataset.quantity = quantity;

    overlay.classList.add('open');
}

function closeModal() { overlay.classList.remove('open'); }
function handleOverlayClick(e) { if (e.target === overlay) closeModal(); }

// ── Sell modal ──
const sellOverlay = document.getElementById('sell-overlay');

function openSellModal() {
    const btn = document.getElementById('m-sell-btn');
    document.getElementById('sell-item-name').textContent = btn.dataset.name;
    document.getElementById('sell-stock').textContent     = btn.dataset.quantity;
    document.getElementById('sell-item-id').value         = currentItemId;
    document.getElementById('sell-qty').max               = btn.dataset.quantity;
    document.getElementById('sell-qty').value             = '';
    document.getElementById('sell-price').value           = '';
    document.getElementById('sell-total').textContent     = '₱0.00';
    sellOverlay.classList.add('open');
}

function closeSellModal() { sellOverlay.classList.remove('open'); }
function handleSellOverlayClick(e) { if (e.target === sellOverlay) closeSellModal(); }

function updateTotal() {
    const qty   = parseFloat(document.getElementById('sell-qty').value)   || 0;
    const price = parseFloat(document.getElementById('sell-price').value) || 0;
    document.getElementById('sell-total').textContent = '₱' + (qty * price).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}
</script>

@endsection