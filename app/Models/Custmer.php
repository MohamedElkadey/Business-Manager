<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Custmer extends Model
{
    protected $guarded = [
        'id',
        'company_id',

    ];
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'metadata',
        'is_active',
    ];
    protected $casts = [
        'metadata' => 'array',
    ];
    public function company(){
        return $this->belongsTo(Company::class,'company_id');
    }
    public function orders(){
        return $this->hasMany(Order::class);
    }
    public function cart(){
        return $this->hasMany(Cart::class);
    }
}
