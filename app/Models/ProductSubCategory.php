<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductSubCategory extends Model
{
    use HasFactory;

    /**
     * Get the ProductCategory that owns the ProductSubCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ProductCategory(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    /**
     * Get all of the Products for the ProductSubCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
