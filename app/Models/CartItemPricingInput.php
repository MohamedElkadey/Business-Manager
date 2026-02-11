<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItemPricingInput extends Model
{
    protected $table = 'cart_item_pricing_inputs';
    protected $fillable = [
        'value_number',
        'value_text',
        'value_boolean',
        'value_date',
        'value_datetime',
        'value_json',
    ];
    protected $guarded = [
        'id',
        'cart_item_id',
        'pricing_template_input_id',
        'created_at',
        'updated_at',
        
    ];

    public function cartItem(){
        return $this->belongsTo(CartItem::class,'cart_item_id');
    }
    public function pricingTemplateInput(){
        return $this->belongsTo(PricingTemplateInput::class,'pricing_template_input_id');
    }
    public function getvalue(){
        $type = $this->pricingTemplateInput->input_type;
        return match($type){
            'number' => $this->value_number,
            'text' => $this->value_text,
            'boolean' =>$this->value_boolean,
            'date' => $this->value_date,
            'datetime' => $this->value_datetime,
            'json' => $this->value_json,
            default => null
        };
    }
}
