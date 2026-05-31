<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouse Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'DM Sans', sans-serif; }
        .mono { font-family: 'DM Mono', monospace; }

        /* Sidebar */
        .sidebar { width: 220px; min-height: 100vh; background: #0f1117; position: fixed; left: 0; top: 0; z-index: 50; display: flex; flex-direction: column; }
        .sidebar-logo { padding: 24px 20px 20px; border-bottom: 1px solid #1e2130; }
        .sidebar-logo-mark { width: 32px; height: 32px; background: #2563eb; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-bottom: 10px; }
        .sidebar-logo-mark svg { width: 18px; height: 18px; }
        .sidebar-title { font-size: 13px; font-weight: 600; color: #fff; letter-spacing: 0.02em; }
        .sidebar-sub { font-size: 11px; color: #6b7280; margin-top: 2px; }
        .sidebar-nav { padding: 16px 12px; flex: 1; }
        .nav-label { font-size: 10px; font-weight: 600; color: #374151; letter-spacing: 0.08em; text-transform: uppercase; padding: 0 8px; margin-bottom: 6px; margin-top: 16px; }
        .nav-label:first-child { margin-top: 0; }
        .nav-link { display: flex; align-items: center; gap: 9px; padding: 8px 10px; border-radius: 7px; font-size: 13px; font-weight: 400; color: #9ca3af; text-decoration: none; transition: all 0.15s; margin-bottom: 2px; }
        .nav-link:hover { background: #1a1f2e; color: #e5e7eb; }
        .nav-link.active { background: #1e2d4d; color: #60a5fa; }
        .nav-link svg { width: 15px; height: 15px; flex-shrink: 0; }
        .sidebar-footer { padding: 16px 12px; border-top: 1px solid #1e2130; }
        .logout-btn { display: flex; align-items: center; gap: 9px; padding: 8px 10px; border-radius: 7px; font-size: 13px; color: #6b7280; text-decoration: none; transition: all 0.15s; width: 100%; background: none; border: none; cursor: pointer; }
        .logout-btn:hover { background: #1f1a1a; color: #f87171; }

        /* Main content */
        .main-wrap { margin-left: 220px; min-height: 100vh; background: #f8f9fb; display: flex; }
        .main-inner { padding: 32px 36px; width: 100%; }

        /* Page header */
        .page-header { margin-bottom: 28px; }
        .page-title { font-size: 22px; font-weight: 600; color: #111827; letter-spacing: -0.01em; }
        .page-sub { font-size: 13px; color: #6b7280; margin-top: 3px; }
        .page-header-row { display: flex; justify-content: space-between; align-items: flex-start; }

        /* Buttons */
        .btn-primary { display: inline-flex; align-items: center; gap: 7px; background: #2563eb; color: #fff; border: none; padding: 9px 18px; border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer; text-decoration: none; transition: background 0.15s; font-family: 'DM Sans', sans-serif; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-secondary { display: inline-flex; align-items: center; gap: 7px; background: #fff; color: #374151; border: 1px solid #e5e7eb; padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 400; cursor: pointer; text-decoration: none; transition: all 0.15s; font-family: 'DM Sans', sans-serif; }
        .btn-secondary:hover { background: #f9fafb; border-color: #d1d5db; }
        .btn-danger { display: inline-flex; align-items: center; gap: 7px; background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer; text-decoration: none; transition: all 0.15s; font-family: 'DM Sans', sans-serif; }
        .btn-danger:hover { background: #fecaca; }

        /* Stat cards */
        .stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 24px; }
        .stat-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; padding: 16px 20px; }
        .stat-label { font-size: 11px; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 6px; }
        .stat-val { font-size: 26px; font-weight: 600; color: #111827; font-family: 'DM Mono', monospace; letter-spacing: -0.02em; }
        .stat-val.green { color: #059669; }
        .stat-val.red { color: #dc2626; }
        .stat-val.blue { color: #2563eb; }

        /* Flash messages */
        .flash-success { background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 10px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 20px; }
        .flash-error { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 10px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 20px; }

        /* Badges */
        .badge { display: inline-flex; align-items: center; gap: 4px; font-size: 11px; font-weight: 500; padding: 3px 9px; border-radius: 20px; white-space: nowrap; }
        .badge::before { content: ''; width: 5px; height: 5px; border-radius: 50%; flex-shrink: 0; }
        .badge-avail { background: #d1fae5; color: #065f46; }
        .badge-avail::before { background: #10b981; }
        .badge-out { background: #fee2e2; color: #991b1b; }
        .badge-out::before { background: #ef4444; }

        /* Forms */
        .form-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 28px 32px; width: 100%; }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 12px; font-weight: 600; color: #374151; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px; }
        .form-input { width: 100%; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px; color: #111827; background: #fff; font-family: 'DM Sans', sans-serif; transition: border-color 0.15s, box-shadow 0.15s; outline: none; }
        .form-input:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
        .form-input.error { border-color: #ef4444; }
        textarea.form-input { resize: vertical; min-height: 90px; }

        /* Table */
        .table-wrap { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table thead { background: #f9fafb; }
        .data-table th { padding: 11px 16px; text-align: left; font-size: 11px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid #e5e7eb; }
        .data-table td { padding: 13px 16px; font-size: 13px; color: #374151; border-bottom: 1px solid #f3f4f6; }
        .data-table tbody tr:last-child td { border-bottom: none; }
        .data-table tbody tr:hover td { background: #f9fafb; }
        .data-table .empty-row td { text-align: center; color: #9ca3af; padding: 40px; }

        /* Item cards */
        .items-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 14px; }
        .item-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 18px 20px; cursor: pointer; transition: border-color 0.15s, box-shadow 0.15s; }
        .item-card:hover { border-color: #93c5fd; box-shadow: 0 2px 12px rgba(37,99,235,0.08); }
        .item-card-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px; }
        .item-name { font-size: 14px; font-weight: 600; color: #111827; line-height: 1.4; }
        .item-cat { font-size: 12px; color: #9ca3af; margin-top: 2px; }
        .item-divider { border: none; border-top: 1px solid #f3f4f6; margin: 12px 0; }
        .item-qty-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
        .item-qty-label { font-size: 11px; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.05em; }
        .item-qty-val { font-size: 22px; font-weight: 600; color: #111827; font-family: 'DM Mono', monospace; }
        .stock-row { display: flex; gap: 6px; }
        .stock-input-group { display: flex; gap: 3px; align-items: center; }
        .stock-input { width: 52px; padding: 5px 6px; font-size: 12px; border: 1px solid #e5e7eb; border-radius: 6px; background: #fff; color: #111827; font-family: 'DM Mono', monospace; outline: none; transition: border-color 0.15s; text-align: center; }
        .stock-input:focus { border-color: #93c5fd; }
        .stock-input.err { border-color: #ef4444; }
        .btn-in { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; padding: 5px 8px; border-radius: 6px; font-size: 11px; font-weight: 600; cursor: pointer; white-space: nowrap; transition: background 0.15s; font-family: 'DM Sans', sans-serif; }
        .btn-in:hover { background: #a7f3d0; }
        .btn-out { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; padding: 5px 8px; border-radius: 6px; font-size: 11px; font-weight: 600; cursor: pointer; white-space: nowrap; transition: background 0.15s; font-family: 'DM Sans', sans-serif; }
        .btn-out:hover { background: #fecaca; }

        /* Modal */
        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center; opacity: 0; pointer-events: none; transition: opacity 0.2s; z-index: 200; padding: 20px; }
        .modal-overlay.open { opacity: 1; pointer-events: all; }
        .modal-box { background: #fff; border-radius: 14px; width: 100%; max-width: 420px; padding: 28px; position: relative; transform: translateY(10px); transition: transform 0.2s; border: 1px solid #e5e7eb; }
        .modal-overlay.open .modal-box { transform: translateY(0); }
        .modal-close { position: absolute; top: 16px; right: 16px; background: #f3f4f6; border: none; width: 28px; height: 28px; border-radius: 50%; font-size: 16px; color: #6b7280; cursor: pointer; display: flex; align-items: center; justify-content: center; line-height: 1; transition: background 0.15s; }
        .modal-close:hover { background: #e5e7eb; color: #111827; }
        .modal-item-name { font-size: 18px; font-weight: 600; color: #111827; margin-bottom: 4px; padding-right: 36px; }
        .modal-meta { font-size: 13px; color: #6b7280; margin-bottom: 16px; display: flex; align-items: center; gap: 10px; }
        .modal-desc { font-size: 13px; color: #6b7280; background: #f9fafb; border: 1px solid #f3f4f6; border-radius: 8px; padding: 12px 14px; margin-bottom: 20px; line-height: 1.6; }
        .modal-row { display: flex; gap: 10px; margin-bottom: 10px; }
        .modal-stat { flex: 1; background: #f9fafb; border-radius: 8px; padding: 10px 12px; }
        .modal-stat-label { font-size: 10px; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 4px; }
        .modal-stat-val { font-size: 18px; font-weight: 600; color: #111827; font-family: 'DM Mono', monospace; }
        .modal-actions { display: flex; gap: 8px; }

        /* Toolbar */
        .toolbar { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; margin-bottom: 20px; }
        .toolbar-right { margin-left: auto; display: flex; gap: 6px; }
        .search-wrap { position: relative; }
        .search-wrap svg { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: #9ca3af; pointer-events: none; }
        .search-input { padding: 9px 12px 9px 34px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 13px; color: #111827; background: #fff; width: 240px; outline: none; font-family: 'DM Sans', sans-serif; transition: border-color 0.15s; }
        .search-input:focus { border-color: #93c5fd; }
        .filter-select { padding: 9px 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 13px; color: #374151; background: #fff; outline: none; font-family: 'DM Sans', sans-serif; cursor: pointer; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-logo">
        <div class="sidebar-logo-mark">
            <svg viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M2 5l7-3 7 3v8l-7 3-7-3V5z" stroke="white" stroke-width="1.4" stroke-linejoin="round"/>
                <path d="M9 2v14M2 5l7 3 7-3" stroke="white" stroke-width="1.4" stroke-linecap="round"/>
            </svg>
        </div>
        <div class="sidebar-title">WMS</div>
        <div class="sidebar-sub">Warehouse Management</div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Inventory</div>
        <a href="/items" class="nav-link {{ Request::is('items') || Request::is('items?*') ? 'active' : '' }}">
            <svg viewBox="0 0 16 16" fill="none"><rect x="1" y="1" width="6" height="6" rx="1.2" stroke="currentColor" stroke-width="1.3"/><rect x="9" y="1" width="6" height="6" rx="1.2" stroke="currentColor" stroke-width="1.3"/><rect x="1" y="9" width="6" height="6" rx="1.2" stroke="currentColor" stroke-width="1.3"/><rect x="9" y="9" width="6" height="6" rx="1.2" stroke="currentColor" stroke-width="1.3"/></svg>
            Items
        </a>
        <a href="/items/history" class="nav-link {{ Request::is('items/history') ? 'active' : '' }}">
            <svg viewBox="0 0 16 16" fill="none"><circle cx="8" cy="8" r="6.5" stroke="currentColor" stroke-width="1.3"/><path d="M8 4.5V8l2.5 2" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
            History
        </a>
        <a href="/items/report" class="nav-link {{ Request::is('items/report') ? 'active' : '' }}">
            <svg viewBox="0 0 16 16" fill="none"><rect x="2" y="1.5" width="12" height="13" rx="1.5" stroke="currentColor" stroke-width="1.3"/><path d="M5 5.5h6M5 8h6M5 10.5h4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
            Report
        </a>
<div class="sidebar-footer">
        <a href="/settings" class="nav-link {{ Request::is('settings*') ? 'active' : '' }}" style="margin-bottom:4px">
            <svg viewBox="0 0 16 16" fill="none"><circle cx="8" cy="8" r="2.5" stroke="currentColor" stroke-width="1.3"/><path d="M8 1v1.5M8 13.5V15M1 8h1.5M13.5 8H15M3.05 3.05l1.06 1.06M11.89 11.89l1.06 1.06M3.05 12.95l1.06-1.06M11.89 4.11l1.06-1.06" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
            Settings
        </a>
        
    </div>
        <div class="nav-label">Manage</div>
        <a href="/items/create" class="nav-link {{ Request::is('items/create') ? 'active' : '' }}">
            <svg viewBox="0 0 16 16" fill="none"><circle cx="8" cy="8" r="6.5" stroke="currentColor" stroke-width="1.3"/><path d="M8 5v6M5 8h6" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
            Add Item
        </a>
    </nav>

    <div class="sidebar-footer">
        <a href="/logout" class="logout-btn">
            <svg width="15" height="15" viewBox="0 0 16 16" fill="none"><path d="M6 2H3a1 1 0 00-1 1v10a1 1 0 001 1h3M10 11l3-3-3-3M13 8H6" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Logout
        </a>
    </div>
</div>

<div class="main-wrap">
    <div class="main-inner">
        @yield('content')
    </div>
</div>

</body>
</html>