<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductGroup;
use App\Models\ProductGroupProduct;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ProductGroupController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $request->validate([
                'name' => 'string|required',
            ]);

            $productGroup = ProductGroup::create([
                'name' => $request->name,
                'user_id' => Auth::id(),
            ]);

            return response()->json($productGroup, 201);
        }catch(Exception $e){
            Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while creating the product group.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $productGroup = ProductGroup::where('id', $id)
                ->with(['products', 'products.pictures'])
                ->firstOrFail();

            if($productGroup->user_id != Auth::id()){
                return response()->json(['error' => 'Unauthorized action.'], 403);
            }

            return Inertia::render('ProductGroup', [
                'productGroup' => $productGroup,
            ]);
        } catch(Exception $e){
            Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while retrieving the product group.'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try{
            $data = $request->validate([
                'name' => 'required|string',
            ]);

            $productGroup = ProductGroup::Where('id', $id)->firstOrFail();
            if($productGroup->user_id != Auth::id()){
                return response()->json(['error' => 'Unauthorized action.'], 403);
            }

            $productGroup->update([
                'name' => $data['name'],
            ]);
            return response()->noContent();
        } catch(Exception $e){
            Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while updating the product group.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, $delete = false)
    {
        $delete = filter_var($delete, FILTER_VALIDATE_BOOLEAN); // Convert to boolean

        try {
            $productGroup = ProductGroup::where('id', $id)->firstOrFail();
            
            if ($productGroup->user_id != Auth::id()) {
                return response()->json(['error' => 'Unauthorized action.'], 403);
            }

            DB::beginTransaction();

            if ($delete) {
                Product::destroy(ProductGroupProduct::where('product_group_id', $id)
                                ->pluck('product_id')->toArray());
            }

            $productGroup->delete();
            DB::commit();

            return response()->noContent();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while deleting the product group.'], 500);
        }
    }
}
