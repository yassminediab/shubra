<?php

namespace App\Http\Controllers\API;

use App\Address;
use App\Cart;
use App\CartProduct;
use App\City;
use App\Product;
use App\State;
use App\Transformers\AddressTransformer;
use App\Transformers\CartTransformer;
use App\Transformers\CityTransformer;
use App\Transformers\StateTransformer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Saad\Fractal\Fractal;

class AddressController extends ApiController
{
    public function createAddress(Request $request,$id = null)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required',
            'address' => 'required',
            'pincode' => 'required',
            'landmark' => 'required',
            'city_id' => 'required|exists:cities,id',
            'state_id' => 'required|exists:states,id',
            'address_type' => 'required',
            'mobile' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->respondBadRequest("", ['errors' => $validator->errors()]);
        }

        $user = $request->user();
        $data = $request->all();
        $data['user_id'] = $user ? $user->id: null;
        $address = Address::create($data);

        return $this->respondSuccess('Address created successfully',$address);
    }

    public function getAddress($id )
    {
        $address = Address::with('city','state')->find($id);
        if(!$address) {
            return $this->respondNotFound('Address not found');
        }
        $transformedAddress = Fractal::create($address, new AddressTransformer());
        return $this->respondSuccess('',$transformedAddress);
    }

    public function getAddresses(Request $request)
    {
        $address = Address::with('city','state')->where('user_id', $request->user()->id)->get();
        $transformedAddress = Fractal::create($address, new AddressTransformer());
        return $this->respondSuccess('',$transformedAddress);
    }

    public function deleteAddress($id)
    {
        $address = Address::find($id);
        if(!$address) {
            return $this->respondNotFound('Address not found');
        }
        $address->delete();

        return $this->respondSuccess('Address deleted successfully');
    }

    public function editAddress(Request $request,$id)
    {
        $address = Address::find($id);
        if(!$address) {
            return $this->respondNotFound('Address not found');
        }

        $validator = Validator::make($request->all(), [
            'full_name' => 'required',
            'address' => 'required',
            'pincode' => 'required',
            'landmark' => 'required',
            'city_id' => 'required|exists:cities,id',
            'state_id' => 'required|exists:states,id',
            'address_type' => 'required',
            'mobile' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->respondBadRequest("", ['errors' => $validator->errors()]);
        }

        $user = $request->user();

        if($address->user_id != $user->id) {
            return $this->respondNotAuthenticated();
        }

        $data = $request->all();
        $address = Address::where('id',$id)->update($data);

        return $this->respondSuccess('Address updated successfully',$address);
    }

    public function listCities()
    {
        $cities = City::get();
        $cities = Fractal::create($cities, new CityTransformer());
        return $this->respondSuccess('',$cities);
    }

    public function listStates($id)
    {
        $states = State::where('city_id',$id)->get();
        $states = Fractal::create($states, new StateTransformer());
        return $this->respondSuccess('',$states);
    }
}
