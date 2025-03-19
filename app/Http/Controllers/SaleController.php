<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\SaleItems;
use App\Models\ProductsStocks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Store a new sale.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_mobile_number' => 'required|string|max:15',
            'discount' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_stock_id' => 'required|exists:products_stocks,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            // Calculate total amount
            $totalAmount = 0;

            // Create the sale record
            $sale = Sales::create([
                'user_id' => auth()->id(),
                'customer_mobile_number' => $request->customer_mobile_number,
                'total_amount' => 0, // Will be updated later
                'discount' => $request->discount ?? 0,
                'final_amount' => 0, // Will be updated later
            ]);

            // Process each sale item
            foreach ($request->items as $item) {
                $productStock = ProductsStocks::findOrFail($item['product_stock_id']);

                // Check if enough stock is available
                if ($productStock->quantity < $item['quantity']) {
                    return response()->json([
                        'error' => "Not enough stock available for Product ID {$productStock->id}. Available: {$productStock->quantity}"
                    ], 400);
                }

                // Calculate subtotal
                $subtotal = $productStock->price * $item['quantity'];
                $totalAmount += $subtotal;

                // Deduct stock
                $productStock->decrement('quantity', $item['quantity']);

                // Create sale item record
                SaleItems::create([
                    'sale_id' => $sale->id,
                    'product_stock_id' => $item['product_stock_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $productStock->price,
                    'subtotal' => $subtotal,
                ]);
            }

            // Update total and final amount
            $sale->update([
                'total_amount' => $totalAmount,
                'final_amount' => $totalAmount - $sale->discount,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Sale recorded successfully, stock updated',
                'sale' => $sale
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => 'Failed to process sale',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

