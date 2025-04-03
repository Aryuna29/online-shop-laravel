<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProduct;
use Illuminate\Support\Facades\Auth;

class CartController
{
    public function getCart()
    {
        /** @var User $user */ //подсказка
        $user = Auth::user();
        //связанные продукты показываю с помощью with
        $userProducts = UserProduct::query()->where('user_id', Auth::id())->with('product')->get();
        return view('cart', ['userProducts' => $userProducts]);

    }


}
