<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddRequest;
use App\Http\Requests\DecreaseRequest;
use App\Http\Requests\ReviewRequest;
use App\Models\Product;
use App\Models\Review;
use App\Models\UserProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\CartService;
use Illuminate\Support\Facades\Cache;

class ProductController
{
    private cartService $cartService;
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function getCatalog()
    {
        $user_id = Auth::id();
        $products = Cache::remember('products_all', 3600, function () {
            return Product::all();
        });
        $cart = UserProduct::query()->where('user_id', $user_id)->count(); //достаю число товаров из корзины
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
        $data = $request->validated();
        $amount = $this->cartService->addProduct($data['product_id']);
        return response()->json([
            'product_id' => $data['product_id'],
            'amount' => $amount, // новое количество для товара
        ]);
    }

    public function decreaseProduct(DecreaseRequest $request)
    {
        $data = $request->validated();
        $amount = $this->cartService->decreaseProduct($data['product_id']);
        return response()->json([
            'product_id' => $data['product_id'],
            'amount' => $amount,
        ]);
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

    public function store(Request $request)
    {
        Product::create($request->all());
        Cache::forget('products_all');

        return redirect()->route('catalog')
            ->with('success', 'Продукт создан и кэш сброшен');
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->all());
        Cache::forget('products_all');

        return redirect()->route('catalog')
            ->with('success', 'Продукт обновлён и кэш сброшен');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        Cache::forget('products_all');

        return redirect()->route('catalog')
            ->with('success', 'Продукт удалён и кэш сброшен');
    }


}
