<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $table = 'product_variants';

    protected $fillable = [
        'company_id',
        'product_id',
        'sku',
        'price_override',
        'stock_quantity',
        'is_active',
    ];

    protected $casts = [
        'price_override' => 'decimal:4',
        'stock_quantity' => 'integer',
        'is_active'      => 'boolean',
    ];
    public function company(){
        return $this->belongsTo(Company::class);
    }
    public function product(){
        return $this->belongsTo(Product::class);
    }
    public function isInStock(): bool{
        return $this->stock_quantity > 0;
    }

    public function optionValues()
    {
        return $this->hasMany(VariantOptionValue::class);
    }




    public function hasPriceOverride(): bool
    {
        return !is_null($this->price_override);
    }

}
