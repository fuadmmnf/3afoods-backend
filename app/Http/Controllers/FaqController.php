<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\FaqRequest;
use App\Models\Faq;


class FaqController extends Controller
{
    public function store(FaqRequest $request)
    {
        try {
            $faq = Faq::create($request->validated());
            return ResponseHelper::success($faq, 'FAQ added successfully', 201);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to add FAQ', 500, $e->getMessage());
        }
    }


    public function index()
    {
        try {
            $faq = Faq::all();
            return ResponseHelper::success($faq , 'FAQs retrieved successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to retrieve FAQs', 500, $e->getMessage());
        }
    }

    public function show($faq_id)
    {
        try {
            $faq = Faq::find($faq_id);
            if (!$faq) {
                return ResponseHelper::error("FAQs with ID $faq_id not found", 404);
            }
            return ResponseHelper::success($faq, 'FAQ retrieved successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to retrieve FAQ', 500, $e->getMessage());
        }
    }

    public function update(FaqRequest $request, $faq_id)
    {
        try {
            $faq = Faq::find($faq_id);
            if (!$faq) {
                return ResponseHelper::error("FAQs with ID $faq_id not found", 404);
            }
            $faq->update($request->validated());
            return ResponseHelper::success($faq, 'FAQ updated successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to update FAQ', 500, $e->getMessage());
        }
    }

    public function destroy($faq_id)
    {
        try {
            $faq = Faq::find($faq_id);
            if (!$faq) {
                return ResponseHelper::error("FAQs with ID $faq_id not found", 404);
            }
            $faq->delete();
            return ResponseHelper::success(null, 'FAQ deleted successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to delete FAQ', 500, $e->getMessage());
        }
    }
}
