<?php

namespace App\Http\Controllers;

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

    public function storeMany(Request $request)
    {
        try{
            $data = $request->validate([
                'group_ids' => 'array|required',
                'product_id' => 'required|exists:products,id',
                'group_ids.*' => 'exists:product_groups,id',
            ]);

            $user = Auth::user();

            // Retrieve groups for provided group_ids in one query
            $groups = \App\Models\ProductGroup::whereIn('id', $data['group_ids'])
                        ->where('user_id', $user->id)
                        ->pluck('id')->toArray();

            if (count($groups) !== count($data['group_ids'])) {
                return response()->json(['error' => 'Unauthorized action.'], 403);
            }

            $productGroupProducts = array_map(function($groupId) use ($data) {
                return [
                    'product_group_id' => $groupId,
                    'product_id' => $data['product_id'],
                ];
            }, $groups);

            ProductGroupProduct::insert($productGroupProducts);
            return response()->noContent();
        } catch(Exception $e){
            Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while creating the product group products.'], 500);
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

    public function destroyMany(Request $request)
    {
        try{
            $data = $request->validate([
                'product_id' => 'required',
                'group_ids' => 'array|required',
            ]);

            $user = Auth::user();

            // Retrieve all matching product group products in one query with related group
            $groupProducts = ProductGroupProduct::where('product_id', $data['product_id'])
                ->whereIn('product_group_id', $data['group_ids'])
                ->with('productGroup')
                ->get();

            // Ensure all product groups belong to the authenticated user
            foreach ($groupProducts as $groupProduct) {
                if ($groupProduct->productGroup->user_id !== $user->id) {
                    return response()->json(['error' => 'Unauthorized action.'], 403);
                }
            }

            $deleteIds = $groupProducts->pluck('id')->toArray();
            ProductGroupProduct::destroy($deleteIds);

            return response()->noContent();
        } catch(Exception $e){
            Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while deleting the product group products.'], 500);
        }    
    }
}
