<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\UserProduct;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    private OrderService $orderService;
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

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
        $data = $request->validated();
        $this->orderService->create($data);
        return response()->redirectTo('user-order');
    }

    public function getUserOrder()
    {
        $userOrders = Order::with('products')
        ->where('user_id', Auth::id())
        ->get();
        foreach ($userOrders as $userOrder) {
            $sum = 0;
            foreach ($userOrder->products as $product) {
                $product->total = $product->pivot->amount * $product->price;
                $sum += $product->total;
            }
            $userOrder->sumTotal = $sum;
        }

        return view('userOrders', compact('userOrders'));
    }
}
