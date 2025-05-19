<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductGroupProduct extends Model
{
    protected $fillable = [
        'product_group_id',
        'product_id',
    ];

    public $timestamps = false; // Add this line to disable timestamps

    public function productGroup()
    {
        return $this->belongsTo(ProductGroup::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}