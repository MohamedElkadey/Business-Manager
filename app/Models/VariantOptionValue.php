<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariantOptionValue extends Model
{
    protected $table = 'variant_option_values';

    protected $fillable = [
        'product_variant_id',
        'variant_attribute_option_id',
    ];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function option()
    {
        return $this->belongsTo(VariantAttributeOption::class, 'variant_attribute_option_id');
    }

}
