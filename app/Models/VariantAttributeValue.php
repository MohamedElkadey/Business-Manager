<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariantAttributeValue extends Model
{
    protected $table = 'variant_attributes';

    protected $fillable = [
        'company_id',
        'template_id',
        'name',
        'key',
    ];
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];
    public function template()
    {
        return $this->belongsTo(Template::class);
    }
    public function company(){
        return $this->belongsTo(Company::class);
    }

    public function options()
    {
        return $this->hasMany(VariantAttributeOption::class);
    }

}
