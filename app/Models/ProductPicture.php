<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductPicture extends Model
{
    protected $fillable = [
        'product_id',
        'image_path',
        'hash',
        'cluster_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public static function storeProductPicture($productId, $image)
    {
        $path = $image->store('product_images', 's3');
        return self::create([
            'product_id' => $productId,
            'image_path' => $path,
            'hash' => md5_file($image->getRealPath()),
            'cluster_id' => null,
        ]);
    }

    // //delete image from s3 when deleting the model
    // protected static function boot()
    // {
    //     parent::boot();

    //     static::deleting(function ($model) {
    //         if ($model->image_path) {
    //             Storage::disk('s3')->delete($model->image_path);
    //         }
    //     });
    // }

    public function getUrlAttribute()
    {
        return Storage::disk('s3')->url($this->image_path);
    }
}
