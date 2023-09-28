<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth; 

use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Cookie;

class AuthController extends Controller
{
    public function register(Request $request)
    {
     return User::create([
     'name'=> $request->input(key:'name'),
     'email'=>$request->input(key:'email'),
     'password'=>Hash::make($request->input(key:'password')),
     ]);
    }

    public function login(Request $request)
    {
      $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        $token = $user->createToken('token')->plainTextToken;

        return response([
            'message' => 'success',
            'token' => $token,
        ]);
    }

    return response(['message' => 'Invalid credentials!'], Response::HTTP_UNAUTHORIZED);
 }

 public function User(){
    return Auth::user();
}

public function logout(){
  $user = Auth::user();

  // Revoke and delete the current user's token
  $user->currentAccessToken()->delete();

  // Remove the JWT cookie
  $cookie = cookie()->forget('jwt');

  return response([
      'message' => 'Successfully logged out',
  ])->withCookie($cookie);
}
}
