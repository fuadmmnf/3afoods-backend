<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories = Category::all();
            return ResponseHelper::success($categories, 'Categories retrieved successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to retrieve categories', 500, $e->getMessage());
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $category = Category::create($validatedData);
            return ResponseHelper::success($category, 'Category created successfully', 201);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to create category', 500, $e->getMessage());
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $category_id)
    {
        try {
            // Find the category by ID
            $category = Category::find($category_id);

            // Check if the category exists
            if (!$category) {
                return ResponseHelper::error("Category with ID $category_id not found", 404);
            }

            return ResponseHelper::success($category, 'Category retrieved successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to retrieve category', 500, $e->getMessage());
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $id)
    {
        try {
            $category = Category::find($id);
            if (!$category) {
                return ResponseHelper::error('Category not found', 404);
            }
            $validatedData = $request->validated();
            $category->update($validatedData);
            return ResponseHelper::success($category, 'Category updated successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to update Category', 500, $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Find the category by ID
            $category = Category::find($id);

            // Check if the category exists
            if (!$category) {
                return ResponseHelper::error("Category with ID $id not found", 404);
            }

            // Delete the category
            $category->delete();

            return ResponseHelper::success([], 'Category deleted successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to delete category', 500, $e->getMessage());
        }
    }

}
