<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\SignUpRequest;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController
{
    public function getSignUpForm()
    {
        return view('signUpForm');
    }
    public function signUp(SignUpRequest $request)
    {
        $data = $request->validated();
       $user = User::query()->create([
           'name' => $data['name'],
           'email' => $data['email'],
           'password' => Hash::make($data['psw']),
       ]);
       return response()->redirectTo('/login');
    }

    public function getProfile()
    {
        $user = Auth::user();
        return view('profile', ['user' => $user]);
    }

    public function getEditProfile()
    {
        $user = Auth::user();
        return view('profileEdit', ['user' => $user]);
    }

    public function editProfile(ProfileRequest $request)
    {
        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        $user->save();
        return response()->redirectTo('/profile');
    }

    public function getLoginForm()
    {
        return view('loginForm');
    }
    public function login(LoginRequest $request)
    {
       if (
           Auth::attempt([
                'email' => $request->get('email'),
                'password'  => $request->get('password')
            ])
       ){
           return response()->redirectTo('/catalog');
       } else {
           return response()->redirectTo('/login')->withErrors(['email' => 'Неверные данные']);
       }
    }

    public function logout()
    {
        Auth::logout();
        return response()->redirectTo('/login');
    }
}
