<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\UserProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $order = Order::query()->create([
                'user_id' => Auth::id(),
                'name' => $data['name'],
                'phone' => $data['phone'],
                'comment' => $data['comment'],
                'address' => $data['address'],
            ]);
            $order_id = $order->id;
            $userProducts = UserProduct::where('user_id', Auth::id())->get();
            foreach ($userProducts as $userProduct) {
                OrderProduct::query()->create([
                    'order_id' => $order_id,
                    'product_id' => $userProduct->product_id,
                    'amount' => $userProduct->amount,
                ]);
            }
            UserProduct::query()->where('user_id', Auth::id())->delete();

        } catch (\Throwable $exception){
            DB::rollback();

            throw $exception;
        }
        DB::commit();
    }

}
