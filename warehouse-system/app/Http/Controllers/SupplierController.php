<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::where('user_id', auth()->id())
            ->withCount('items')
            ->latest()
            ->get();

        return view('suppliers.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'contact' => 'nullable|string|max:100',
            'email'   => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        // Prevent duplicate per user
        $exists = Supplier::where('user_id', auth()->id())
            ->where('name', $request->name)
            ->exists();

        if ($exists) {
            $message = "Supplier \"{$request->name}\" already exists.";
            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 422);
            }
            return back()->with('error', $message);
        }

        $supplier = Supplier::create([
            'user_id' => auth()->id(),
            'name'    => $request->name,
            'contact' => $request->contact,
            'email'   => $request->email,
            'address' => $request->address,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['supplier' => $supplier], 201);
        }

        return back()->with('success', "Supplier \"{$supplier->name}\" added.");
    }

    public function destroy($id)
    {
        Supplier::where('id', $id)
            ->where('user_id', auth()->id())
            ->delete();

        return back()->with('success', 'Supplier removed.');
    }
}