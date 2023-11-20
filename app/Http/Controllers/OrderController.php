<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Order;
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
    public function store(Request $request)
    {
        //
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




}
