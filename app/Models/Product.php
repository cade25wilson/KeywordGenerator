<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'attributes',
        'platform_data',
        'status',
    ];

    protected $casts = [
        'platform_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function productGroup()
    {
        return $this->belongsToMany(ProductGroup::class);
    }

    public function pictures()
    {
        return $this->hasMany(ProductPicture::class);
    }
}
