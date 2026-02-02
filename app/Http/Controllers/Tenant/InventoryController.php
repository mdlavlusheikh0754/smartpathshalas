<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        try {
            // Start with empty items array to avoid database issues
            $items = collect([]);
            
            // Try to get items from database
            try {
                $items = InventoryItem::orderBy('created_at', 'desc')->get();
            } catch (\Exception $dbError) {
                // If database error, continue with empty collection
                \Log::error('Inventory database error: ' . $dbError->getMessage());
            }
            
            // Transform items to include Bengali translations
            $items = $items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'item_code' => $item->item_code,
                    'name' => $item->name,
                    'category' => $item->category,
                    'category_name' => $item->category_name,
                    'class' => $item->class ?? null,
                    'price' => $item->price,
                    'stock' => $item->stock,
                    'min_stock' => $item->min_stock,
                    'unit' => $item->unit,
                    'unit_name' => $item->unit_name,
                    'description' => $item->description,
                    'status' => $item->status,
                    'status_name' => $item->status_name,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                ];
            });
            
            return view('tenant.inventory.index', compact('items'));
        } catch (\Exception $e) {
            \Log::error('Inventory controller error: ' . $e->getMessage());
            return response('Error loading inventory page: ' . $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'required|string',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'min_stock' => 'nullable|integer|min:0',
                'unit' => 'nullable|string',
                'description' => 'nullable|string'
            ]);

            $data = $request->all();
            if (empty($data['unit'])) {
                $data['unit'] = 'piece';
            }

            InventoryItem::create($data);

            return response()->json([
                'success' => true,
                'message' => 'নতুন আইটেম সফলভাবে যোগ করা হয়েছে!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $item = InventoryItem::findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'required|string',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'min_stock' => 'nullable|integer|min:0',
                'unit' => 'nullable|string',
                'description' => 'nullable|string'
            ]);
            
            // Set default unit if not provided
            if (empty($validated['unit'])) {
                $validated['unit'] = 'piece';
            }
            
            // Set default min_stock if not provided
            if (!isset($validated['min_stock'])) {
                $validated['min_stock'] = 0;
            }

            $item = InventoryItem::findOrFail($id);
            $item->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'আইটেম সফলভাবে আপডেট করা হয়েছে!',
                'item' => $item
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Inventory update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $item = InventoryItem::findOrFail($id);
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'আইটেম সফলভাবে মুছে ফেলা হয়েছে!'
        ]);
    }

    public function getStats()
    {
        $totalItems = InventoryItem::count();
        $inStock = InventoryItem::where('status', 'in_stock')->count();
        $lowStock = InventoryItem::where('status', 'low_stock')->count();
        $totalValue = InventoryItem::sum(\DB::raw('price * stock'));

        return response()->json([
            'totalItems' => $totalItems,
            'inStock' => $inStock,
            'lowStock' => $lowStock,
            'totalValue' => $totalValue
        ]);
    }
}