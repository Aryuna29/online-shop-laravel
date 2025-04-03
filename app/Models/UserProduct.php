<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProduct extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'amount',
    ];
    public function amountZero($value)  // Если меньше 0, ставим 0
    {
        $this->attributes['amount'] = max(0, $value);
    }

    public function amountDelete($value) // Удаляем товар, если количество 0
    {
        if ($value <= 0) {
            $this->delete();
        } else {
            $this->attributes['amount'] = $value;
        }
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
