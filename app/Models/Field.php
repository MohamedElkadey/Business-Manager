<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    protected $fillable = [
        'template_id',
        'key',
        'label',
        'field_type',
        'unit',
        'required',
        'default_value',
        'options',
    ];

    protected $guarded = [
        'id',
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
    public function productvalue(){
        return $this->hasMany(ProductFieldValue::class);
    }
}
