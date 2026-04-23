<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'customer_name', 'customer_email', 'items', 
        'total_amount', 'amount_paid', 'remaining_balance', 
        'order_status', 'payment_status'
    ];
    
    protected $casts = [
        'items' => 'array'
    ];
    
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}