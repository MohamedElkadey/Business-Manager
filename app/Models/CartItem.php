<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'quantity',
    ];

    protected $guarded = [
        'id',
        'company_id',
        'cart_id',
        'product_id',
        'price',
        'pricing_version',
        'pricing_snapshot',
        'created_at',
           'updated_at',
    ];
    protected $casts = [
        'pricing_snapshot' => 'array',
        'price' => 'decimal:4',
        'quantity' => 'integer',
    ];

    public function company(){
        return $this->belongsTo(Company::class,'company_id');
    }

    public function cart(){
        return $this->belongsTo(Cart::class,'cart_id');
    }
    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }
    public function pricingInputs(){
        return $this->hasMany(CartItemPricingInput::class);
    }
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
