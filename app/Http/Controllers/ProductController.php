<?php

namespace App\Http\Controllers;

use App\Jobs\AnalyzeProduct;
use App\Models\Product;
use App\Models\ProductGroup;
use App\Models\ProductGroupProduct;
use App\Models\ProductPicture;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        try{
            $user = Auth::user();
            $products = Product::where('user_id', $user->id)
                ->with(['pictures'])
                ->orderBy('created_at', 'desc')
                ->get();

            return Inertia::render('ProductIndex', [
                'products' => $products,
            ]);
        } catch(Exception $e){
            Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while retrieving the products.'], 500);
        }
    }
    
    public function create()
    {
        return Inertia::render('ProductCreate');
    }

    public function store(Request $request)
    {
        try{
            $data = $request->validate([
                'title'    => 'nullable|string|max:255',
                'images'   => 'required|array|min:1',
                'images.*' => 'image|max:5120',
                'group_id' => 'nullable|exists:product_groups,id',
            ]);

            if($data['group_id']) {
                $group = ProductGroup::find($data['group_id']);
                if ($group->user_id !== Auth::id()) {
                    return redirect()->back()->withErrors(['message' => 'Unauthorized']);
                }
            }

            $user = Auth::user();

            DB::beginTransaction();
            
            $product = Product::create([
                'user_id'       => $user->id,
                'title'         => $request->input('title') ?? '',
            ]);

            // Instead of overwriting $image, push the returned image path to an array
            foreach ($request->file('images') as $image) {
                ProductPicture::storeProductPicture($product->id, $image);
            }

            if ($data['group_id']) {
                ProductGroupProduct::create([
                    'product_group_id' => $data['group_id'],
                    'product_id'       => $product->id,
                ]);
            }

            DB::commit();

            // Dispatch the job with an array of stored image paths (strings)
            AnalyzeProduct::dispatch($product, $product->pictures->pluck('image_path')->toArray());

            // Return an Inertia redirect with a flash message
            return redirect()->back()->with('success', 'Product uploaded and queued for analysis.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->back()->withErrors(['message' => 'Error uploading product: ' . $e->getMessage()]);
        }
    }

    public function reprocess(string $id): JsonResponse
    {
        try{
            $product = Product::where('id', $id)->firstOrFail();
            
            if ($product->user_id != Auth::id()){
                return response()->json(['error' => 'Unauthorized action.'], 403);
            }

            $product->status = 'processing';
            $product->save();
            
            AnalyzeProduct::dispatch($product, $product->pictures->pluck('image_path')->toArray());

            return response()->json(['message' => 'Product reprocessing has been queued successfully.'], 200);
        } catch(Exception $e){
            Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while reprocessing the product.'], 500);
        }
    }

    public function show(string $id)
    {
        try{
            $user = Auth::user();
            $product = Product::where('id', $id)
                        ->with(['pictures'])
                        ->with(['productGroups'])
                        ->firstOrFail();

            if($product->user_id != $user->id){
                return response()->json(['error' => 'Unauthorized action.'], 403);
            }
            
            $notIncludedGroups = ProductGroup::where('user_id', $user->id)
                ->whereNotIn('id', $product->productGroups->pluck('id'))
                ->get();

            return Inertia::render('ProductShow', [
                'product' => $product,
                'test' => 'test',
                'notIncludedGroups' => $notIncludedGroups,
            ]);
        } catch(Exception $e){
            Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while retrieving the product.'], 500);
        }
    }

    public function destroy(string $id)
    {
        try{
            $product = Product::where('id', $id)->firstOrFail();
            
            if ($product->user_id != Auth::id()){
                return response()->json(['error' => 'Unauthorized action.'], 403);
            }

            $product->deleteProduct($product);

            return response()->json(['message' => 'Product deleted successfully.'], 200);
        } catch(Exception $e){
            Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while deleting the product.'], 500);
        }
    }
}
