<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductGroup extends Model
{
    protected $fillable = [
        'user_id',
        'name',
    ];

    public function productGroupProducts()
    {
        return $this->hasMany(ProductGroupProduct::class);
    }

    public function products()
    {
        return $this->hasManyThrough(
            Product::class,
            ProductGroupProduct::class,
            'product_group_id', // Foreign key on ProductGroupProduct
            'id', // Foreign key on Product
            'id', // Local key on ProductGroup
            'product_id' // Local key on ProductGroupProduct
        );
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function randomProduct()
    {
        return $this->products()->inRandomOrder()->first();
    }
}
