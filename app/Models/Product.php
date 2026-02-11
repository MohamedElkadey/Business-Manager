<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'template_id',
        'name',
        'sku',
        'description',
        'base_rate',
        'is_active',
        'extra',
    ];
    protected $guarded = [
        'id',
        'company_id',
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'extra' => 'array',
        'base_rate' => 'decimal',
        'is_active' => 'boolean',
    ];
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function template(){
        return $this->belongsTo(Template::class);
    }


    public function orderItems(){
        return $this->hasMany(OrderItem::class);
    }
    public function productvalue(){
        return $this->hasMany(ProductFieldValue::class);
    }
    public function variants(){
        return $this->hasMany(ProductVariant::class);
    }
}
