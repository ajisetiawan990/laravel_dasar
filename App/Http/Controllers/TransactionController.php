<?php

namespace App\Http\Controllers;

// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\TransactionDetails;
use App\Models\Product;

class TransactionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'item' => 'required|array',
            'item.*.product_id' => 'required|exists:products,id',
            'item.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $totalPrice = 0;

            $transaction = Transaction::create([
                'user_id' => $request->user_id,
                'total_price' => 0,
            ]);

            foreach($request->item as $item){
                $product = Product::findOrFail($item['product_id']);

                $subTotal = $product->price * $item['quantity'];
                $totalPrice += $subTotal;

                TransactionDetails::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subTotal
                ]);

                if($product->stock < $item['quantity']) {
                    throw new \Exception("Stock Product {$product->name} tidak cukup ");
                }
                $product->decrement('stock', $item['quantity']);
            }

            $transaction->update(['total_price' => $totalPrice]);

            DB::commit();
            return response()->json([
                'status' => 'Success',
                'message' => 'Transaksi berhasil dibuat',
                'transaction' => $transaction->load('details.product')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'Failed',
                'message' => 'Gagal Transaksi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
