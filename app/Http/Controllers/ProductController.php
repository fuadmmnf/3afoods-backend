<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products = Product::all();
            return ResponseHelper::success($products, 'Products retrieved successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to retrieve products', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $validatedData = $request->validated();
        $product = Product::create($validatedData);
        return ResponseHelper::success($product, 'Product created successfully', 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = Product::find($id);
            if (!$product) {
                return ResponseHelper::error('Product not found', 404);
            }
            return ResponseHelper::success(['product' => $product], 'Product retrieved successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to retrieve product', 500, $e->getMessage());
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, string $id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return ResponseHelper::error('Product not found', 404);
            }

            $validatedData = $request->validated();
            $product->update($validatedData);
            return ResponseHelper::success($product, 'Product updated successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to update product', 500, $e->getMessage());
        }
    }

    public function getProductsByType(string $type)
    {
        try {
            if (!in_array($type, ['retail', 'wholesale'])) {
                return ResponseHelper::error('Invalid product type', 400);
            }

            $products = Product::where('type', $type)->get();
            return ResponseHelper::success(['products' => $products], 'Products retrieved successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to retrieve products', 500, $e->getMessage());
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return ResponseHelper::error('Product not found', 404);
            }

            $product->delete();

            return ResponseHelper::success([], 'Product deleted successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to delete product', 500, $e->getMessage());
        }
    }
}
