<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menus';
    
    protected $fillable = [
        'name', 'category', 'price_per_kilo', 'stock'
    ];
    
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}