<?php

namespace App\Http\Controllers;

use App\HotelDetails;
use App\Http\Resources\HotelDetailsCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HotelDetailsController extends Controller
{
    public function store(Request $request)
    {
        //check if a hotel is already registered. Only one registration permitted.
        if (HotelDetails::count() < 1) {

            try {
                //validate request payload
                $validator = $this->validateDetails($request);

                if ($validator->fails()) {
                    return response()->json(['msg' => $validator->messages()->first(), 'code' => '01']);
                }

                // generate hotel image name
                $fileName = 'hotel_' . time() . '.jpg';
                $this->uploadImage($request, $fileName);

                $model = new HotelDetails([
                    'name' => $request->get('name'),
                    'address' => $request->get('address'),
                    'city' => $request->get('city'),
                    'state' => $request->get('state'),
                    'country' => $request->get('country'),
                    'zip_code' => $request->get('zip_code'),
                    'phone_number' => $request->get('phone_number'),
                    'email' => $request->get('email'),
                    'image' => $fileName,
                ]);

                $model->save();

                if ($model) {
                    return response()->json(['msg' => 'hotel details saved.', 'code' => '00']);
                } else {
                    return response()->json(['msg' => 'hotel details could not be saved.', 'code' => '01']);
                }
            } catch (Exception $e) {
                return response()->json(['msg' => 'hotel details could not be saved.', 'code' => '02']);
            }
        } else {
            return response()->json(['msg' => 'you can only save one hotel.', 'code' => '01']);
        }
    }

    public function index()
    {
        return new HotelDetailsCollection(HotelDetails::all());
    }

    public function edit($id)
    {
        $model = HotelDetails::find($id);
        return response()->json($model);
    }

    public function update($id, Request $request)
    {
        //check if hotel already exist
        $model = HotelDetails::find($id);
        if ($model) {
            //validate request payload
            $validator = $this->validateDetails($request);

            if ($validator->fails()) {
                return response()->json(['msg' => $validator->messages()->first(), 'code' => '01']);
            }

            $model->name = $request->get('name');
            $model->city = $request->get('city');
            $model->address = $request->get('address');
            $model->state = $request->get('state');
            $model->country = $request->get('country');
            $model->zip_code = $request->get('zip_code');
            $model->phone_number = $request->get('phone_number');
            $model->email = $request->get('email');
            $model->save();

            if ($model) {
                return response()->json(['msg' => 'hotel details updated.', 'code' => '00']);
            } else {
                return response()->json(['msg' => 'hotel details could not be updated.', 'code' => '01']);
            }
        } else {
            return response()->json(['msg' => 'This record does not exist.', 'code' => '01']);
        }

    }

    public function delete($id)
    {
        //hotel cannot be deleted
        return response()->json(['msg' => 'Request not supported.', 'code' => '01']);
    }

    /**
     * request validation method
     */
    protected function validateDetails($request)
    {
        return Validator::make($request->all(), [
            // 'image' => 'mimes:jpeg,jpg,png|required|max:10000',
            'name' => 'required',
            'email' => 'email',
            'city' => 'required',
            'state' => 'required',
            'address' => 'required',
            'zip_code' => 'required',
            'country' => 'required',
            'phone_number' => 'required',
        ]);
    }

    /**
     *  image upload
     */
    protected function uploadImage($request, $fileName)
    {
        $path = public_path() . "/images/rooms/" . $fileName;
        $img = $request->image;
        $img = substr($img, strpos($img, ",") + 1);
        $data = base64_decode($img);
        $success = file_put_contents($path, $data);
    }
}
