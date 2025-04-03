<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\UserProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function getOrder()
    {
        $userProducts = UserProduct::query()->where('user_id', Auth::id())->with('product')->get();
        $sumTotal = 0;
        foreach ($userProducts as $userProduct) {
            $sumTotal += $userProduct->product->price * $userProduct->amount;
        }
        return view('orderForm', compact('userProducts', 'sumTotal'));
    }

    public function createOrder(OrderRequest $request)
    {
        $order = Order::query()->create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'phone' => $request->phone,
            'comment' => $request->comment,
            'address' => $request->address,
        ]);
        $order_id = $order->id;
        $userProducts = UserProduct::where('user_id', Auth::id())->get();
        foreach ($userProducts as $userProduct) {
            $product_id = $userProduct->product_id;
            $amount = $userProduct->amount;
            OrderProduct::query()->create([
                'order_id' => $order_id,
                'product_id' => $product_id,
                'amount' => $amount,
            ]);
        }
        UserProduct::query()->where('user_id', Auth::id())->delete();
    }

    public function getUserOrder()
    {
        $orders = Order::query()->where('user_id', Auth::id());
    }
}
