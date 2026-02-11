<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingTemplateInput extends Model
{
    protected $fillable = [
        'key',
        'label',
        'input_type',
        'unit',
        'options',
    ];

    protected $guarded = [
        'id',
        'template_id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'options' => 'array',
    ];
    public function template()
    {
        return $this->belongsTo(Template::class);
    }
    public function cartItemInputs()
    {
        return $this->hasMany(CartItemPricingInput::class);
    }

}
