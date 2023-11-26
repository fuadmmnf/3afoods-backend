<?php

namespace App\Http\Controllers;

use App\Helpers\FileHandler;
use App\Helpers\ResponseHelper;
use App\Http\Requests\ShippingProductRequest;
use App\Models\ShippingProduct;
use Illuminate\Http\Request;

class ShippingProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $shippingProducts = ShippingProduct::all();

            return ResponseHelper::success($shippingProducts, 'Shipping products retrieved successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to retrieve shipping products', 500, $e->getMessage());
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(ShippingProductRequest $request)
    {
        try {
             // Check if the request has a file and validate/upload if present
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filePath = FileHandler::upload($file, 'files', 4096, ['pdf', 'doc', 'docx']);
            } else {
                $filePath = null;
            }
            // Validate and create the shipping product
            $shippingProductData = $request->validated();
            $shippingProductData['file'] = $filePath;

            $shippingProduct = ShippingProduct::create($shippingProductData);

            return ResponseHelper::success($shippingProduct, 'Shipping product added successfully', 201);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to add shipping product', 500, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $shipping_product_id)
    {
        try {
            $shippingProduct = ShippingProduct::find($shipping_product_id);

            if (!$shippingProduct) {
                return ResponseHelper::error("Shipping product with ID $shipping_product_id not found", 404);
            }

            return ResponseHelper::success($shippingProduct, 'Shipping product retrieved successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to retrieve shipping product', 500, $e->getMessage());
        }
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
