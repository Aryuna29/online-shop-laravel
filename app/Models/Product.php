<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        ];
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function order()
    {
        return $this->belongsToMany(Order::class, 'order_products')
            ->withPivot('amount');
    }


}
