@extends('layouts.app')

@section('content')

<div style="width:100%">

    <div class="page-header">
        <div class="page-header-row">
            <div>
                <div class="page-title">Edit Item</div>
                <div class="page-sub">Update details for <strong style="color:#111827;font-weight:600">{{ $item->item_name }}</strong></div>
            </div>
            <a href="{{ route('items.index') }}" class="btn-secondary">
                <svg width="13" height="13" viewBox="0 0 13 13" fill="none"><path d="M8 2L4 6.5 8 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Back
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="flash-error" style="margin-bottom:20px">
            <strong style="display:block;margin-bottom:4px">Please fix the following errors:</strong>
            <ul style="margin:0;padding-left:16px">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-card">
        <form action="{{ route('items.update', $item->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Item Name</label>
                <input type="text" name="item_name"
                       value="{{ old('item_name', $item->item_name) }}"
                       placeholder="e.g. Cardboard Box (Large)"
                       class="form-input {{ $errors->has('item_name') ? 'error' : '' }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Category</label>
                <input type="text" name="category"
                       value="{{ old('category', $item->category) }}"
                       placeholder="e.g. Packaging, Equipment, PPE"
                       class="form-input {{ $errors->has('category') ? 'error' : '' }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Quantity</label>
                <input type="number" name="quantity" min="0"
                       value="{{ old('quantity', $item->quantity) }}"
                       class="form-input {{ $errors->has('quantity') ? 'error' : '' }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Supplier</label>
                <select name="supplier_id" id="supplier-select" class="form-input" onchange="handleSupplierChange(this)">
                    <option value="">— No supplier —</option>
                    @foreach($suppliers as $s)
                        <option value="{{ $s->id }}"
                            {{ old('supplier_id', $item->supplier_id) == $s->id ? 'selected' : '' }}>
                            {{ $s->name }}
                        </option>
                    @endforeach
                    <option value="__add__" style="color:#2563eb;font-weight:500">+ Add new supplier...</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom:24px">
                <label class="form-label">
                    Description
                    <span style="color:#9ca3af;font-weight:400;text-transform:none;letter-spacing:0">(optional)</span>
                </label>
                <textarea name="description" rows="3"
                          placeholder="Add notes about this item..."
                          class="form-input">{{ old('description', $item->description) }}</textarea>
            </div>

            <div style="display:flex;gap:10px">
                <button type="submit" class="btn-primary">Update Item</button>
                <a href="{{ route('items.index') }}" class="btn-secondary">Cancel</a>
            </div>

        </form>
    </div>

</div>

{{-- Add Supplier Modal --}}
<div id="supplier-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.4);z-index:300;align-items:center;justify-content:center;padding:20px">
    <div style="background:#fff;border-radius:14px;width:100%;max-width:380px;padding:28px;position:relative;border:1px solid #e5e7eb">

        <button onclick="closeSupplierModal()" style="position:absolute;top:16px;right:16px;background:#f3f4f6;border:none;width:28px;height:28px;border-radius:50%;font-size:16px;color:#6b7280;cursor:pointer;display:flex;align-items:center;justify-content:center">&#x2715;</button>

        <div style="font-size:17px;font-weight:600;color:#111827;margin-bottom:4px">Add New Supplier</div>
        <div style="font-size:13px;color:#9ca3af;margin-bottom:20px">Will appear in the dropdown immediately</div>

        <div id="supplier-modal-error" style="display:none" class="flash-error"></div>

        <div class="form-group" style="margin-bottom:20px">
            <label class="form-label">
                Supplier / Company Name
                <span style="color:#ef4444">*</span>
            </label>
            <input type="text" id="new-supplier-name"
                   placeholder="e.g. Ace Hardware, 3M Philippines"
                   class="form-input" oninput="clearSupplierError()">
        </div>

        <div style="display:flex;gap:8px">
            <button onclick="saveSupplier()" class="btn-primary" style="flex:1;justify-content:center">
                <span id="save-btn-text">Save Supplier</span>
            </button>
            <button onclick="closeSupplierModal()" class="btn-secondary">Cancel</button>
        </div>

    </div>
</div>

<script>
    const modal      = document.getElementById('supplier-modal');
    const select     = document.getElementById('supplier-select');
    const modalError = document.getElementById('supplier-modal-error');

    function handleSupplierChange(el) {
        if (el.value === '__add__') {
            el.value = '';
            openSupplierModal();
        }
    }

    function openSupplierModal() {
        document.getElementById('new-supplier-name').value = '';
        modalError.style.display = 'none';
        modal.style.display      = 'flex';
        setTimeout(() => document.getElementById('new-supplier-name').focus(), 100);
    }

    function closeSupplierModal() {
        modal.style.display = 'none';
    }

    function clearSupplierError() {
        modalError.style.display = 'none';
    }

    async function saveSupplier() {
        const name = document.getElementById('new-supplier-name').value.trim();

        if (!name) {
            modalError.textContent   = 'Supplier name is required.';
            modalError.style.display = 'block';
            document.getElementById('new-supplier-name').focus();
            return;
        }

        const btn = document.getElementById('save-btn-text');
        btn.textContent = 'Saving...';

        try {
            const res = await fetch('{{ route("suppliers.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ name })
            });

            const data = await res.json();

            if (!res.ok) {
                const firstError = data.errors
                    ? Object.values(data.errors)[0][0]
                    : (data.message || 'Something went wrong.');
                modalError.textContent   = firstError;
                modalError.style.display = 'block';
                btn.textContent          = 'Save Supplier';
                return;
            }

            const option       = document.createElement('option');
            option.value       = data.supplier.id;
            option.textContent = data.supplier.name;
            option.selected    = true;

            const addOption = select.querySelector('option[value="__add__"]');
            select.insertBefore(option, addOption);

            closeSupplierModal();
            btn.textContent = 'Save Supplier';

        } catch (err) {
            modalError.textContent   = 'Network error. Please try again.';
            modalError.style.display = 'block';
            btn.textContent          = 'Save Supplier';
        }
    }

    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeSupplierModal();
    });
</script>

@endsection