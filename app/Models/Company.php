<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name',
        'settings',
    ];

    protected $guarded = [
        'id',
        'uuid',
        'slug',
        'plan_type',
        'status',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function carts(){
        return $this->hasMany(Cart::class);
    }
    public function cartItems(){
        return $this->hasMany(CartItem::class);
    }
    public function orders(){
        return $this->hasMany(Order::class);
    }
    public function orderItems(){
        return $this->hasMany(OrderItem::class);
    }
    public function product(){
        return $this->hasMany(Product::class);
    }
    

}
