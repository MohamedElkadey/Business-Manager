<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariantAttributeOption extends Model
{
    protected $table = 'variant_attribute_options';

    protected $fillable = [
        'variant_attribute_id',
        'value',
    ];

    public function attribute()
    {
        return $this->belongsTo(VariantAttributeValue::class, 'variant_attribute_id');
    }

    public function variantValues()
    {
        return $this->hasMany(VariantOptionValue::class);
    }

}
