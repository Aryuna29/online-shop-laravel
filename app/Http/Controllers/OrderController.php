<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\UserProduct;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

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
        $userId = Auth::id();
        $userOrders = Cache::remember("user:{$userId}", 30, function () {
            return Order::with('products')
                ->where('user_id', Auth::id())
                ->get();
        });
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

    public function store(Request $request)
    {
        $userId = Auth::id();
        $order = Order::create($request->all());
        Cache::forget("user:{$userId}");
        return redirect()->route('userOrders')
            ->with('success', 'Заказ создан и кэш сброшен');
    }
    public function update(Request $request, Product $product)
    {
        $userId = Auth::id();
        $product->update($request->all());
        Cache::forget("user:{$userId}");

        return redirect()->route('userOrders')
            ->with('success', 'Заказ обновлён и кэш сброшен');
    }

    public function destroy(Product $product)
    {
        $userId = Auth::id();
        $product->delete();
        Cache::forget("user:{$userId}");

        return redirect()->route('userOrders')
            ->with('success', 'Заказ удалён и кэш сброшен');
    }
}
