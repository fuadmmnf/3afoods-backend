<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHandler;
use App\Helpers\ResponseHelper;
use App\Http\Requests\ProductRequest;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products = Product::with(['category:id,category_name'])->get();
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
        // Save the product with the generated image path
        $validatedData = $request->validated();
        // Validate and upload the image
        $imagePath = ImageHandler::upload($request->file('img'), 'product_img', 2048, ['jpg', 'jpeg', 'png', 'gif']);
        $validatedData['img'] = $imagePath ;
        $product = Product::create($validatedData);
        return ResponseHelper::success($product, 'Product created successfully', 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = Product::with(['category:id,category_name'])->find($id);
            if (!$product) {
                return ResponseHelper::error('Product not found', 404);
            }
            return ResponseHelper::success($product, 'Product retrieved successfully', 200);
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

            // Check if a new image is provided
            if ($request->hasFile('img')) {
                $imagePath = ImageHandler::upload($request->file('img'), 'product_img', 2048, ['jpg', 'jpeg', 'png', 'gif']);
                $validatedData = $request->validated();
                $validatedData['img'] = $imagePath;
            } else {
                // If no new image is provided, only update other fields
                $validatedData = $request->validated();
                unset($validatedData['img']); // Exclude img from validation data
            }

            // Update the product
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
            $limit = request()->query('limit', null);

            $productsQuery = Product::with(['category:id,category_name'])->where('type', $type);

            if (is_numeric($limit)) {
                $productsQuery->limit($limit);
            }
            $products = $productsQuery->get();
            return ResponseHelper::success($products, 'Products retrieved successfully', 200);
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
