<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'status',
        'metadata',
    ];

    protected $guarded = [
        'id',
        'company_id',
        'customer_id',
        'total_price',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'metadata' => 'array',
    ];


    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function customer()
    {
        return $this->belongsTo(Custmer::class);
    }

     public function orderItems()
     {
         return $this->hasMany(OrderItem::class);
     }
}
