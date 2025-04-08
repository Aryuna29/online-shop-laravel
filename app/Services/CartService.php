<?php

namespace App\Services;

use App\Models\Product;
use App\Models\UserProduct;
use Illuminate\Support\Facades\Auth;

class CartService
{

    public function addProduct(int $product_id): int
    {
        $product = Product::query()->findOrFail($product_id);
        $user_id = Auth::id();
        $userProduct = UserProduct::query()->where('product_id', $product->id)->where('user_id', $user_id)->first();
        $updatedAmount = 0;
        if ($userProduct) {
            $userProduct->increment('amount');
            $updatedAmount = $userProduct->amount;
        } else {
            UserProduct::query()->create([
                'product_id' => $product->id,
                'user_id' => $user_id,
                'amount' => 1,
            ]);
            $updatedAmount = 1;
        }
        return $updatedAmount;
    }

    public function decreaseProduct(int $product_id): int
    {
        $product = Product::query()->findOrFail($product_id);
        $user_id = Auth::id();
        $userProducts = UserProduct::query()->where('product_id', $product->id)->where('user_id', $user_id)->first();
        $newAmount = 0;
        if ($userProducts) {
            if ($userProducts->amount > 1) {
                $userProducts->decrement('amount');
                $newAmount = $userProducts->amount - 1;
            } elseif ($userProducts->amount === 1) {
                $userProducts->delete();
                $newAmount = 0;
            }
        } else {
            $newAmount = 0;
        }
        return $newAmount;
    }
}
