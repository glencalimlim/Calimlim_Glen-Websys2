<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Item;
use App\Models\StockTransaction;

class SaleController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'item_id'          => 'required|exists:items,id',
            'customer_name'    => 'required|string|max:255',
            'customer_contact' => 'nullable|string|max:100',
            'quantity'         => 'required|integer|min:1',
            'unit_price'       => 'nullable|numeric|min:0',
        ]);

        $item = Item::where('id', $request->item_id)
                    ->where('user_id', auth()->id())
                    ->firstOrFail();

        if ($request->quantity > $item->quantity) {
            return back()->with('error', "Not enough stock. Available: {$item->quantity}.");
        }

        $unitPrice  = $request->unit_price ?? 0;
        $totalPrice = $unitPrice * $request->quantity;
        $receiptNo  = 'RCP-' . strtoupper(uniqid());

        $sale = Sale::create([
            'item_id'          => $item->id,
            'user_id'          => auth()->id(),
            'customer_name'    => $request->customer_name,
            'customer_contact' => $request->customer_contact,
            'quantity'         => $request->quantity,
            'unit_price'       => $unitPrice,
            'total_price'      => $totalPrice,
            'receipt_no'       => $receiptNo,
        ]);

        // Deduct stock
        $item->quantity -= $request->quantity;
        $item->status    = $item->quantity === 0 ? 'Out of Stock' : 'Available';
        $item->save();

        StockTransaction::create([
            'item_id'  => $item->id,
            'user_id'  => auth()->id(),
            'type'     => 'out',
            'quantity' => $request->quantity,
        ]);

      return redirect()->route('items.report')
                 ->with('success', "Sale completed! Receipt No: {$sale->receipt_no}");
    }

    public function report()
    {
        $sales = Sale::with('item')
                     ->where('user_id', auth()->id())
                     ->latest()
                     ->get();

        return view('items.report', compact('sales'));
    }

}