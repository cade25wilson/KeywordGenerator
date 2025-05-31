<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'platform_data',
        'keyword_data',
        'status',
    ];

    protected $casts = [
        'platform_data' => 'array',
        'keyword_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function productGroups()
    {
        return $this->belongsToMany(ProductGroup::class, 'product_group_products', 'product_id', 'product_group_id');
    }

    public function pictures()
    {
        return $this->hasMany(ProductPicture::class);
    }

    public function deleteProduct(Product $product)
    {
        $productPictures = ProductPicture::where('product_id', $product->id)
                ->pluck('image_path')
                ->toArray();

        Storage::disk('s3')->delete($productPictures);
        $product->delete();
    }
}
