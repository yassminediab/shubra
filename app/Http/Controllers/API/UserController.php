<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

class UserController extends ApiController
{
    public function wishlistProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            return $this->respondBadRequest("", ['errors' => $validator->errors()]);
        }
        $user = $request->user();

        $user->wishlists()->attach([$request->product_id])

        return $this->respondSuccess('Wishlist created successfully',$user);
    }
}
