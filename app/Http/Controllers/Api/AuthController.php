<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cunsomers\Cunsomer;

class AuthController extends Controller
{

    public $loginAfterSignUp = true;

    public function register(Request $request)
    {
      $user = Cunsomer::create([
        'customer_firstname' => $request->first_name,
        'customer_lastname' => $request->last_name,
        'customer_mobile' => $request->mobile,
        'customer_status' => $request->status,
      ]);
      
      $token = auth()->guard('api')->login($user);
      return $this->respondWithToken($token);
    }

    public function login(Request $request)
    {
      $credentials = $request->only(['email', 'password']);

      if (!$token = auth()->attempt($credentials)) {
        return response()->json(['error' => 'Unauthorized'], 401);
      }

      return $this->respondWithToken($token);
    }
    public function getAuthUser(Request $request)
    {
        return response()->json(auth()->user());
    }
    public function logout()
    {
        auth()->logout();
        return response()->json(['message'=>'Successfully logged out']);
    }
    protected function respondWithToken($token)
    {
      return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => auth()->guard('api')->factory()->getTTL() * 60
      ]);
    }

}