@extends('layouts.app')

@section('content')

<style>
@media print {
    .sidebar, .page-header, .stat-grid, .btn-secondary, .toolbar, nav,
    .no-print { display: none !important; }
    .main-wrap  { margin-left: 0 !important; }
    .main-inner { padding: 0 !important; }
    .table-wrap { border: none !important; box-shadow: none !important; }
    .data-table th, .data-table td { font-size: 11px !important; padding: 8px 10px !important; }
    .print-title { display: block !important; font-size: 16px; font-weight: 600; margin-bottom: 8px; color: #111827; }
    .print-date  { display: block !important; font-size: 11px; color: #6b7280; margin-bottom: 16px; }
}
.print-title, .print-date { display: none; }

/* Receipt modal */
.receipt-modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.45);
    z-index: 999;
    align-items: center;
    justify-content: center;
}
.receipt-modal-overlay.open { display: flex !important; }
.receipt-box {
    background: #fff;
    border-radius: 16px;
    padding: 28px 24px;
    width: 100%;
    max-width: 420px;
    position: relative;
    font-family: 'DM Sans', sans-serif;
    box-shadow: 0 20px 60px rgba(0,0,0,0.15);
}

/* Clickable row */
.sale-row { cursor: pointer; transition: background .15s; }
.sale-row:hover { background: #f0fdf4 !important; }
</style>

<div class="page-header">
    <div class="page-header-row">
        <div>
            <div class="page-title">Sales Report</div>
            <div class="page-sub">All completed sales transactions</div>
        </div>
        <div style="display:flex;gap:8px">
            <a href="{{ route('items.index') }}" class="btn-secondary no-print">
                <svg width="13" height="13" viewBox="0 0 13 13" fill="none"><path d="M8 2L4 6.5 8 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Back
            </a>
        </div>
    </div>
</div>

@php
    $totalSales   = $sales->count();
    $totalRevenue = $sales->sum('total_price');
    $totalUnits   = $sales->sum('quantity');
    $todaySales   = $sales->filter(fn($s) => $s->created_at->isToday())->count();
@endphp

<div class="stat-grid" style="margin-bottom:24px;grid-template-columns:repeat(4,1fr)">
    <div class="stat-card">
        <div class="stat-label">Total Transactions</div>
        <div class="stat-val blue">{{ $totalSales }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Today's Sales</div>
        <div class="stat-val green">{{ $todaySales }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Units Sold</div>
        <div class="stat-val">{{ number_format($totalUnits) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Revenue</div>
        <div class="stat-val" style="color:#2563eb">₱{{ number_format($totalRevenue, 2) }}</div>
    </div>
</div>

<span class="print-title">Sales Report</span>
<span class="print-date">Printed on: {{ now()->format('F d, Y h:i A') }}</span>

<div class="table-wrap">
    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Receipt No.</th>
                <th>Item</th>
                <th>Customer</th>
                <th>Contact</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Total</th>
                <th>Date & Time</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $i => $sale)
            <tr class="sale-row"
                onclick="openReceipt(
                    '{{ addslashes($sale->receipt_no) }}',
                    '{{ addslashes($sale->item->item_name ?? 'N/A') }}',
                    '{{ addslashes($sale->customer_name) }}',
                    '{{ addslashes($sale->customer_contact ?? '') }}',
                    {{ $sale->quantity }},
                    {{ $sale->unit_price }},
                    {{ $sale->total_price }},
                    '{{ $sale->created_at->format('F d, Y h:i A') }}'
                )">
                <td class="mono" style="color:#9ca3af;font-size:12px">{{ $i + 1 }}</td>
                <td class="mono" style="font-size:11px;color:#6b7280">{{ $sale->receipt_no }}</td>
                <td style="font-weight:500;color:#111827">{{ $sale->item->item_name ?? '—' }}</td>
                <td style="color:#374151">{{ $sale->customer_name }}</td>
                <td style="color:#6b7280;font-size:12px">{{ $sale->customer_contact ?? '—' }}</td>
                <td class="mono" style="font-weight:500">{{ $sale->quantity }}</td>
                <td class="mono">₱{{ number_format($sale->unit_price, 2) }}</td>
                <td class="mono" style="font-weight:600;color:#10b981">₱{{ number_format($sale->total_price, 2) }}</td>
                <td style="font-size:11px;color:#9ca3af">{{ $sale->created_at->format('M d, Y h:i A') }}</td>
            </tr>
            @empty
            <tr class="empty-row">
                <td colspan="9">No sales transactions yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ── Receipt Modal ── --}}
<div class="receipt-modal-overlay" id="receipt-overlay" onclick="handleReceiptOverlay(event)">
    <div class="receipt-box" id="receipt-box">
        <button onclick="closeReceipt()" style="position:absolute;top:14px;right:16px;background:none;border:none;font-size:16px;cursor:pointer;color:#9ca3af;line-height:1">&#x2715;</button>

        <div id="receipt-print-area">

            {{-- Header --}}
            <div style="text-align:center;padding-bottom:20px;border-bottom:1px solid #f3f4f6">
                <div style="width:42px;height:42px;background:#ecfdf5;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 10px">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M4 4h12v10a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" stroke="#10b981" stroke-width="1.5"/>
                        <path d="M8 4V2.5a.5.5 0 011 0V4M11 4V2.5a.5.5 0 011 0V4" stroke="#10b981" stroke-width="1.3" stroke-linecap="round"/>
                        <path d="M7 10h6M7 13h4" stroke="#10b981" stroke-width="1.3" stroke-linecap="round"/>
                    </svg>
                </div>
                <div style="font-size:13px;font-weight:700;color:#111827;letter-spacing:.02em">OFFICIAL RECEIPT</div>
                <div style="font-size:11px;color:#9ca3af;margin-top:3px" id="r-date"></div>
                <div style="display:inline-block;margin-top:6px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:20px;padding:3px 12px">
                    <span style="font-size:10px;color:#6b7280">Receipt No. </span>
                    <span style="font-size:10px;font-weight:700;color:#374151;font-family:'DM Mono',monospace" id="r-no"></span>
                </div>
            </div>

            {{-- Customer & Item --}}
            <div style="padding:16px 0;border-bottom:1px dashed #e5e7eb">
                <div style="font-size:10px;font-weight:700;color:#9ca3af;letter-spacing:.08em;text-transform:uppercase;margin-bottom:10px">Transaction Details</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                    <div style="background:#f9fafb;border-radius:8px;padding:10px 12px">
                        <div style="font-size:10px;color:#9ca3af;margin-bottom:2px">Item</div>
                        <div style="font-size:13px;font-weight:600;color:#111827" id="r-item"></div>
                    </div>
                    <div style="background:#f9fafb;border-radius:8px;padding:10px 12px">
                        <div style="font-size:10px;color:#9ca3af;margin-bottom:2px">Customer</div>
                        <div style="font-size:13px;font-weight:600;color:#111827" id="r-customer"></div>
                    </div>
                    <div style="background:#f9fafb;border-radius:8px;padding:10px 12px;grid-column:span 2">
                        <div style="font-size:10px;color:#9ca3af;margin-bottom:2px">Contact</div>
                        <div style="font-size:12px;font-weight:500;color:#374151" id="r-contact"></div>
                    </div>
                </div>
            </div>

            {{-- Breakdown --}}
            <div style="padding:16px 0;border-bottom:1px dashed #e5e7eb">
                <div style="font-size:10px;font-weight:700;color:#9ca3af;letter-spacing:.08em;text-transform:uppercase;margin-bottom:10px">Summary</div>
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
                    <span style="font-size:12px;color:#6b7280">Quantity</span>
                    <span style="font-size:12px;font-weight:600;color:#374151;font-family:'DM Mono',monospace" id="r-qty"></span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
                    <span style="font-size:12px;color:#6b7280">Unit Price</span>
                    <span style="font-size:12px;font-weight:600;color:#374151;font-family:'DM Mono',monospace" id="r-unit"></span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;background:#ecfdf5;border:1px solid #d1fae5;border-radius:10px;padding:12px 14px;margin-top:4px">
                    <span style="font-size:13px;font-weight:700;color:#065f46">Total Amount</span>
                    <span style="font-size:22px;font-weight:800;color:#10b981;font-family:'DM Mono',monospace;letter-spacing:-.01em" id="r-total"></span>
                </div>
            </div>

            {{-- Footer --}}
            <div style="padding-top:16px;text-align:center">
                <svg width="60" height="8" viewBox="0 0 60 8" fill="none" style="margin-bottom:10px">
                    <circle cx="4" cy="4" r="3" fill="#d1fae5"/><circle cx="14" cy="4" r="3" fill="#a7f3d0"/>
                    <circle cx="24" cy="4" r="3" fill="#6ee7b7"/><circle cx="34" cy="4" r="3" fill="#34d399"/>
                    <circle cx="44" cy="4" r="3" fill="#10b981"/><circle cx="54" cy="4" r="3" fill="#059669"/>
                </svg>
                <div style="font-size:12px;font-weight:600;color:#374151">Thank you for your purchase!</div>
                <div style="font-size:10px;color:#9ca3af;margin-top:3px">{{ config('app.name') }}</div>
                <div style="font-size:9px;color:#d1d5db;margin-top:8px;font-family:'DM Mono',monospace">This serves as your official receipt.</div>
            </div>

        </div>

        {{-- Actions --}}
        <div style="display:flex;gap:8px;margin-top:20px;padding-top:16px;border-top:1px solid #f3f4f6" class="no-print">
            <button onclick="closeReceipt()" class="btn-secondary" style="flex:1">Close</button>
            <button onclick="printReceipt()" class="btn-primary" style="flex:2;justify-content:center;background:#10b981;border-color:#10b981">
                <svg width="13" height="13" viewBox="0 0 13 13" fill="none"><rect x="2" y="4" width="9" height="6" rx="1" stroke="currentColor" stroke-width="1.3"/><path d="M4 4V2.5a.5.5 0 014 0V4M4 10v.5a.5.5 0 00.5.5h4a.5.5 0 00.5-.5V10" stroke="currentColor" stroke-width="1.3"/></svg>
                Print Receipt
            </button>
        </div>
    </div>
</div>

<script>
const receiptOverlay = document.getElementById('receipt-overlay');

function openReceipt(receiptNo, item, customer, contact, qty, unitPrice, total, date) {
    document.getElementById('r-no').textContent       = receiptNo;
    document.getElementById('r-item').textContent     = item;
    document.getElementById('r-customer').textContent = customer;
    document.getElementById('r-contact').textContent  = contact || '—';
    document.getElementById('r-qty').textContent      = qty + ' unit(s)';
    document.getElementById('r-unit').textContent     = '₱' + parseFloat(unitPrice).toLocaleString('en-PH', { minimumFractionDigits: 2 });
    document.getElementById('r-total').textContent    = '₱' + parseFloat(total).toLocaleString('en-PH', { minimumFractionDigits: 2 });
    document.getElementById('r-date').textContent     = date;
    receiptOverlay.classList.add('open');
}

function closeReceipt() { receiptOverlay.classList.remove('open'); }
function handleReceiptOverlay(e) { if (e.target === receiptOverlay) closeReceipt(); }

function printReceipt() {
    const area = document.getElementById('receipt-print-area').innerHTML;
    const win  = window.open('', '', 'width=420,height=680');
    win.document.write(`
        <html><head><title>Receipt — {{ config('app.name') }}</title>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=DM+Mono&display=swap');
            * { box-sizing: border-box; margin: 0; padding: 0; }
            body {
                font-family: 'DM Sans', sans-serif;
                background: #fff;
                padding: 28px 24px;
                font-size: 13px;
                color: #111827;
                max-width: 360px;
                margin: 0 auto;
            }
        </style></head>
        <body>${area}</body></html>
    `);
    win.document.close();
    win.focus();
    setTimeout(() => { win.print(); win.close(); }, 400);
}
</script>

@endsection