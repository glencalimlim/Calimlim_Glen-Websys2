<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\StockTransaction;
use Carbon\Carbon;

class ItemController extends Controller
{
    public function index(Request $request)
{
    $query = Item::where('user_id', auth()->id())->with('supplier');

    if ($request->search) {
        $query->where('item_name', 'like', '%' . $request->search . '%');
    }

    if ($request->status) {
        $query->where('status', $request->status);
    }

    // Date range filter
    // Date range filter — only applies when BOTH dates are filled
if ($request->filled('date_from') && $request->filled('date_to')) {
    $query->whereBetween('updated_at', [
        \Carbon\Carbon::parse($request->date_from)->startOfDay(),
        \Carbon\Carbon::parse($request->date_to)->endOfDay(),
    ]);
}

    switch ($request->sort) {
        case 'name_asc':
            $query->orderBy('item_name', 'asc');
            break;
        case 'name_desc':
            $query->orderBy('item_name', 'desc');
            break;
        case 'qty_asc':
            $query->orderBy('quantity', 'asc');
            break;
        case 'qty_desc':
            $query->orderBy('quantity', 'desc');
            break;
        case 'newest':
            $query->orderBy('created_at', 'desc');
            break;
        case 'oldest':
            $query->orderBy('created_at', 'asc');
            break;
        case 'updated_newest':
            $query->orderBy('updated_at', 'desc');
            break;
        case 'updated_oldest':
            $query->orderBy('updated_at', 'asc');
            break;
        default:
            $query->orderBy('updated_at', 'desc');
            break;
    }

    $items     = $query->get();
    $suppliers = Supplier::where('user_id', auth()->id())->orderBy('name')->get();
    $threshold = auth()->user()->stock_threshold ?? 10;

    return view('items.index', compact('items', 'suppliers', 'threshold'));
}

    public function create()
    {
        $suppliers = Supplier::where('user_id', auth()->id())->orderBy('name')->get();
        return view('items.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name'   => 'required|string|max:255',
            'category'    => 'required|string|max:255',
            'quantity'    => 'required|integer|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'description' => 'nullable|string',
        ]);

        if ($request->supplier_id) {
            $existing = Item::where('user_id', auth()->id())
                ->where('supplier_id', $request->supplier_id)
                ->where('item_name', $request->item_name)
                ->first();

            if ($existing) {
                $existing->quantity += $request->quantity;
                $existing->status    = $existing->quantity > 0 ? 'Available' : 'Out of Stock';
                $existing->save();

                if ($request->quantity > 0) {
                    StockTransaction::create([
                        'item_id'     => $existing->id,
                        'user_id'     => auth()->id(),
                        'supplier_id' => $request->supplier_id,
                        'type'        => 'in',
                        'quantity'    => $request->quantity,
                    ]);
                }

                return redirect()->route('items.index')
                    ->with('success', "\"{$request->item_name}\" already exists from this supplier — stock updated by {$request->quantity}.");
            }
        }

        $item = Item::create([
            'user_id'     => auth()->id(),
            'supplier_id' => $request->supplier_id,
            'item_name'   => $request->item_name,
            'category'    => $request->category,
            'quantity'    => $request->quantity,
            'description' => $request->description,
            'status'      => $request->quantity > 0 ? 'Available' : 'Out of Stock',
        ]);

        if ($request->quantity > 0) {
            StockTransaction::create([
                'item_id'     => $item->id,
                'user_id'     => auth()->id(),
                'supplier_id' => $request->supplier_id,
                'type'        => 'in',
                'quantity'    => $request->quantity,
            ]);
        }

        return redirect()->route('items.index')->with('success', 'Item added successfully.');
    }

    public function stockIn(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $item = Item::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        $item->quantity += $request->quantity;
        $item->status    = 'Available';
        $item->save();

        StockTransaction::create([
            'item_id'     => $item->id,
            'user_id'     => auth()->id(),
            'supplier_id' => $item->supplier_id,
            'type'        => 'in',
            'quantity'    => $request->quantity,
        ]);

        return back()->with('success', "Added {$request->quantity} unit(s) to \"{$item->item_name}\".");
    }

    public function stockOut(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $item = Item::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        if ($request->quantity > $item->quantity) {
            return back()->with('error', "Not enough stock for \"{$item->item_name}\". Available: {$item->quantity}.");
        }

        $item->quantity -= $request->quantity;
        $item->status    = $item->quantity === 0 ? 'Out of Stock' : 'Available';
        $item->save();

        StockTransaction::create([
            'item_id'  => $item->id,
            'user_id'  => auth()->id(),
            'type'     => 'out',
            'quantity' => $request->quantity,
        ]);

        return back()->with('success', "Removed {$request->quantity} unit(s) from \"{$item->item_name}\".");
    }

    public function history(Request $request)
    {
        $query = StockTransaction::with(['item', 'supplier'])
            ->where('user_id', auth()->id());

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->supplier_id) {
            $query->where('supplier_id', $request->supplier_id);
        }

        $transactions = $query->latest()->get();
        $suppliers    = Supplier::where('user_id', auth()->id())->orderBy('name')->get();

        $days     = collect(range(13, 0))->map(fn($d) => Carbon::today()->subDays($d));
        $labels   = $days->map(fn($d) => $d->format('M d'))->values();
        $stockIn  = $days->map(fn($d) => StockTransaction::where('user_id', auth()->id())
            ->where('type', 'in')->whereDate('created_at', $d)->sum('quantity'))->values();
        $stockOut = $days->map(fn($d) => StockTransaction::where('user_id', auth()->id())
            ->where('type', 'out')->whereDate('created_at', $d)->sum('quantity'))->values();

        return view('items.history', compact('transactions', 'suppliers', 'labels', 'stockIn', 'stockOut'));
    }

    public function report()
{
    $items = Item::where('user_id', auth()->id())->with('supplier')->get();
    
    $sales = \App\Models\Sale::with('item')
                ->where('user_id', auth()->id())
                ->latest()
                ->get();

    return view('items.report', compact('items', 'sales'));
}

    public function edit($id)
    {
        $item      = Item::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $suppliers = Supplier::where('user_id', auth()->id())->orderBy('name')->get();
        return view('items.edit', compact('item', 'suppliers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'item_name'   => 'required|string|max:255',
            'category'    => 'required|string|max:255',
            'quantity'    => 'required|integer|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'description' => 'nullable|string',
        ]);

        /** @var \App\Models\Item $item */
        $item = Item::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        $oldQty = $item->quantity;
        $newQty = (int) $request->quantity;
        $diff   = $newQty - $oldQty;

        $item->update([
            'item_name'   => $request->item_name,
            'category'    => $request->category,
            'quantity'    => $newQty,
            'supplier_id' => $request->supplier_id,
            'description' => $request->description,
            'status'      => $newQty > 0 ? 'Available' : 'Out of Stock',
        ]);

        if ($diff !== 0) {
            StockTransaction::create([
                'item_id'     => $item->id,
                'user_id'     => auth()->id(),
                'supplier_id' => $item->supplier_id,
                'type'        => $diff > 0 ? 'in' : 'out',
                'quantity'    => abs($diff),
            ]);
        }

        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    public function destroy($id)
    {
        Item::where('id', $id)->where('user_id', auth()->id())->delete();
        return back()->with('success', 'Item deleted.');
    }
}