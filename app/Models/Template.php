<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'expression',
        'pricing_version',
        'parent_template_id',
        'is_active',
    ];
    protected $casts = [
        'pricing_version' => 'integer',
        'is_active' => 'boolean',
        //'expression' => 'array', // if you switch to jsonb
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_template_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_template_id');
    }


    public function field(){
        return  $this->hasMany(Field::class);
    }
    public function pricingTemplateInput(){
        return $this->hasMany(PricingTemplateInput::class);
    }
    public function product(){
        return $this->hasMany(Product::class);
    }
    public function variantattribute(){
        return $this->hasMany(VariantAttributeValue::class);
    }
    
}
