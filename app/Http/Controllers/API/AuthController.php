<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends ApiController
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        try {
            // verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($request->all())) {
                return $this->respondNotAuthenticated('Invalid email or password');
            }
            if (!$user->is_active) {
                return $this->respondNotAuthenticated('User not verified');
            }
            return $this->respondAccepted("", ['token' => $token, 'user' => $user]);
        } catch (JWTException $e) {
            // something went wrong
            return $this->respondNotAuthenticated('Invalid email or password');
        }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->respondBadRequest("", ['errors' => $validator->errors()]);
        }

        $data = $request->all();
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        return $this->respondAccepted("User created successfully", ['user' => $user]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function VerifyUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->respondBadRequest("", ['errors' => $validator->errors()]);
        }

        $user = User::where('phone' , $request->phone)->update(['is_active' => true]);

        return $this->respondAccepted("User verified successfully", ['user' => $user]);
    }
}
