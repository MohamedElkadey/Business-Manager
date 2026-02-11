<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';
    protected $guarded = [
        'company_id',
        'customer_id',
        'status',
    ];

    protected $casts = [
        'company_id' =>'integer',
        'customer_id' =>'integer',
        'status' => 'string'
    ];

    public function company(){
        return $this->belongsTo(Company::class,'company_id');
    }

    public function customer(){
        return $this->belongsTo(Custmer::class,'customer_id');
    }
    public function cartItems(){
        return $this->hasMany(CartItem::class);
    }

}
 