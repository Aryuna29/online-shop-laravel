<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddRequest;
use App\Http\Requests\DecreaseRequest;
use App\Http\Requests\ReviewRequest;
use App\Models\Product;
use App\Models\Review;
use App\Models\UserProduct;
use Illuminate\Support\Facades\Auth;

class ProductController
{
    public function getCatalog()
    {
        $user_id = Auth::id();
        $cart = UserProduct::query()->where('user_id', $user_id)->count(); //достаю число товаров из корзины
        $products = Product::all();
        foreach ($products as $product) {
           $userProduct = UserProduct::query()->where('user_id', $user_id)->where('product_id', $product->id)->first();
           if ($userProduct) {
               $product->amount = $userProduct->amount;
           } else {
               $product->amount = 0;
           }
        }
        return view('catalog', ['products' => $products, 'cart' => $cart]);

    }

    public function addProduct(AddRequest $request)
    {
        $product = Product::query()->findOrFail($request->product_id);
        $user_id = Auth::id();
        $userProduct = UserProduct::query()->where('product_id', $product->id)->where('user_id', $user_id)->first();
        if ($userProduct) {
          $userProduct->increment('amount');
        } else {
             UserProduct::query()->create([
                'product_id' => $product->id,
                'user_id' => $user_id,
                'amount' => 1,
            ]);
        }
        return redirect()->route('catalog');
    }

    public function decreaseProduct(DecreaseRequest $request)
    {
        $product = Product::query()->findOrFail($request->product_id);
        $user_id = Auth::id();
        $userProducts = UserProduct::query()->where('product_id', $product->id)->where('user_id', $user_id)->first();
        if ($userProducts) {
            $userProducts->delete();
            return redirect()->route('catalog');
    } else {
            return back()->with('error', 'Товар не найден.');
        }
    }

    public function getProduct(int $id)
    {
        $product = Product::query()->find($id);
        $count = Review::query()->where('product_id', $id)->count();
        if ($count===0){
            $ratingTotal = 0;
        } else {
            $ratingTotal = Review::query()->where('product_id', $id)->sum('rating')/$count;
        }
        return view('review', ['product' => $product, 'count' => $count, 'ratingTotal' => $ratingTotal]);
    }

    public function addReview(ReviewRequest $request, int $id)
    {
        $product = Product::query()->find($id);
        $review = Review::query()->create([
         'product_id'=> $product->id,
         'user_id' => Auth::id(),
         'review' => $request->review,
         'rating' => $request->rating,
     ]);
        return redirect()->route('add.review', $product->id);
    }

}
