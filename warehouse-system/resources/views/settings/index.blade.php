@extends('layouts.app')

@section('content')

<div class="page-header">
    <div class="page-header-row">
        <div>
            <div class="page-title">Settings</div>
            <div class="page-sub">Manage your account preferences</div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="flash-success">{{ session('success') }}</div>
@endif

<div style="max-width:480px">
    <div class="form-card">
        <div style="font-size:14px;font-weight:600;color:#111827;margin-bottom:4px">Stock Threshold</div>
        <div style="font-size:13px;color:#9ca3af;margin-bottom:20px">
            Items with quantity at or below this number will be highlighted in red as a low stock alert on the dashboard.
        </div>

        <form action="{{ route('settings.update') }}" method="POST">
            @csrf

            <div class="form-group" style="margin-bottom:20px">
                <label class="form-label">Threshold Quantity</label>
                <input type="number" name="stock_threshold" min="1" max="10000"
                       value="{{ auth()->user()->stock_threshold ?? 10 }}"
                       class="form-input" required>
                <p style="font-size:11px;color:#9ca3af;margin-top:5px">
                    Current threshold: <strong style="color:#111827">{{ auth()->user()->stock_threshold ?? 10 }} units</strong>
                </p>
            </div>

            <button type="submit" class="btn-primary">Save Settings</button>
        </form>
    </div>
</div>

@endsection