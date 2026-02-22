<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariantAttributeOption extends Model
{
    protected $table = 'variant_attribute_options';

    protected $fillable = [
        'company_id',
        'template_id',
        'variant_attribute_id',
        'value',
    ];

    public function attribute()
    {
        return $this->belongsTo(VariantAttributeValue::class, 'variant_attribute_id');
    }
    public function company(){
        return $this->belongsTo(Company::class);
    }
    public function variantValues()
    {
        return $this->hasMany(VariantOptionValue::class);
    }

}
