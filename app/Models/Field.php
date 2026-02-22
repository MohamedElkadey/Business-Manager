<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    protected $fillable = [
        'template_id',
        // 'key',
        'label',
        'field_type', // ['string','number','boolean','select','date','datetime']
        'unit',
        'required',
        'default_value',
        'options',
        'position',
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
