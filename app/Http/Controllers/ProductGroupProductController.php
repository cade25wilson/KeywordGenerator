<?php

namespace App\Http\Controllers;

use App\Models\ProductGroup;
use App\Models\ProductGroupProduct;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductGroupProductController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $data = $request->validate([
                'categoryId' => 'string|required|exists:product_groups,id',
                'productId' => 'string|required|exists:products,id',
            ]);

            ProductGroupProduct::create([
                'product_group_id' => $data['categoryId'],
                'product_id' => $data['productId'],
            ]);

            return response()->noContent();
        }catch(Exception $e){
            Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while creating the product group product.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
     public function destroy(string $categoryId, string $productId)
    {
        try{
            $productGroupProduct = ProductGroupProduct::where('product_group_id', $categoryId)
                ->where('product_id', $productId)
                ->with(['productGroup'])
                ->firstOrFail();

            if($productGroupProduct->productGroup->user_id != Auth::id()){
                return response()->json(['error' => 'Unauthorized action.'], 403);
            }
            
            $productGroupProduct->delete();
            return response()->noContent(204);
        } catch(Exception $e){
            Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while deleting the product group.'], 500);
        }
    }
}
