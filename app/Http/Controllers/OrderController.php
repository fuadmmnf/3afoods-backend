<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $orders = Order::all();
            return ResponseHelper::success($orders, 'Orders retrieved successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to retrieve orders', 500, $e->getMessage());
        }
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $request)
    {
        try {
            // Validate and create the order
            $orderData = $request->validated();
//            $orderData['status']="default";
            info($orderData);
//            echo  $orderData;
            $order = Order::create($orderData);

            // Attach products to the order
            $this->attachProductsToOrder($order, $request->input('cart'));

            return ResponseHelper::success($order, 'Order placed successfully', 201);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to place order', 500, $e->getMessage());
        }
    }
    private function attachProductsToOrder(Order $order, array $cart)
    {

        foreach ($cart as $item) {
            // Validate if the product exists
            $product = Product::findOrFail($item['product_id']);
            $order->products()->attach($product->id, [
                'quantity' => $item['quantity'],
                'price' => $item['price'], // Use the provided price from the request
//                'created_at' => now(),
//                'updated_at' => now(),
            ]);
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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

    public function getOrdersByTypeAndStatus(string $type, string $status)
    {
        try {
            $validatedData = validator([
                'type' => $type,
                'status' => $status,
            ], [
                'type' => ['required', Rule::in(['retail', 'wholesale'])],
                'status' => ['required', Rule::in(['draft', 'pending', 'completed'])],
            ]);

            if ($validatedData->fails()) {
                throw new ValidationException($validatedData);
            }

            $orders = Order::where('type', $type)
                ->where('status', $status)
                ->get();

            return ResponseHelper::success($orders, 'Orders retrieved successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to retrieve orders', 500, $e->getMessage());
        }
    }

    public function completeOrder(string $order_id)
    {
        try {
            // Find the order by ID
            $order = Order::find($order_id);

            // Check if the order exists
            if (!$order) {
                return ResponseHelper::error("Order with ID $order_id not found", 404);
            }

            // Check if the order is in pending status
            if ($order->status !== 'pending') {
                return ResponseHelper::error("Order with ID $order_id is not in pending status", 422);
            }

            // Update the order status to completed
            $order->update(['status' => 'completed']);

            return ResponseHelper::success([], 'Order status updated to completed', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to update order status', 500, $e->getMessage());
        }
    }




}
