<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'quantity',
    ];

    protected $guarded = [
        'id',
        'company_id',
        'order_id',
        'product_id',
        'price',
        'pricing_version',
        'pricing_snapshot',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'price' => 'decimal:4',
        'pricing_snapshot' => 'array',
        'quantity' => 'integer',
    ];
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
