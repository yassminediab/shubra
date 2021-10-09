<?php

namespace App\Http\Controllers\API;

use App\Product;
use App\ProductReview;
use App\Transformers\ProductTransformer;
use App\Transformers\UserTransformer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;
use Saad\Fractal\Fractal;

class UserController extends ApiController
{
    public function getWishlist(Request $request) {
        $products = $request->user()->wishlist;

        $transformedProduct = Fractal::create($products, new ProductTransformer());
        return $this->respondSuccess('product returned successfully',$transformedProduct);
    }

    public function editProfile(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'required',
            'last_name' => 'required',
            'date_of_birth' => 'required|date',
            'title' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->respondBadRequest("", ['errors' => $validator->errors()]);
        }

        $request->user()->update($request->all());
        return $this->respondSuccess('Profile updated successfully');
    }

    public function editEmail(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'email' => 'required|unique:users,email,'.$request->user()->id,
        ]);

        if ($validator->fails()) {
            return $this->respondBadRequest("", ['errors' => $validator->errors()]);
        }

        $request->user()->update($request->all());
        return $this->respondSuccess('Email updated successfully');
    }

    public function editPhone(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'phone' => 'required|unique:users,phone,'.$request->user()->id,
        ]);

        if ($validator->fails()) {
            return $this->respondBadRequest("", ['errors' => $validator->errors()]);
        }

        $data = $request->all();
        $data['is_active'] = false;

        $request->user()->update($data);
        return $this->respondSuccess('Phone updated successfully');
    }

    public function editPassword(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->respondBadRequest("", ['errors' => $validator->errors()]);
        }

        $request->user()->update(['password' => Hash::make($request->password)]);
        return $this->respondSuccess('Password updated successfully');
    }

    public function editAvatar(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'avatar' => 'required|image',
        ]);

        if ($validator->fails()) {
            return $this->respondBadRequest("", ['errors' => $validator->errors()]);
        }
        $path = 'app/public/avatars';

        $imageName = time().'.'.$request->avatar->getClientOriginalExtension();
        $request->avatar->move(storage_path($path), $imageName);

        $request->user()->update(['avatar' => 'avatars/'.$imageName]);
        return $this->respondSuccess('Avatar updated successfully');
    }

    public function getProfile(Request $request)
    {
        $user = Fractal::create($request->user(), new UserTransformer());
        return $this->respondSuccess('',['user' => $user]);
    }
}
