<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductFieldValue extends Model
{
    protected $fillable = [
        'field_id',
        'product_id',
        'value_number',
        'value_text',
        'value_boolean',
        'value_date',
        'value_datetime',
        'value_json',
        'extra',
    ];
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'value_number'   => 'decimal:4',
        'value_boolean'  => 'boolean',
        'value_date'     => 'date',
        'value_datetime' => 'datetime',
        'value_json'     => 'array',
        'extra'          => 'array',
    ];
    public function field(){
        return $this->belongsTo(Field::class);
    }
    public function product(){
        return $this->belongsTo(Product::class);
    }
}
